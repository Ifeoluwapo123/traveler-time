<?php
	require_once('database.php');
	$db = new loc();
?>
<html>
<head>
	<title>Travelers' Timer</title>
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="home.css">
</head>
<body>
	<div class="container">
		<div class="phone">
			<label class="phonename">TABLET</label>
			<div class="inner">
				<div class="screen">
					<h3 class="title">Travellers' Timer</h3>
					<div class="elements" id="elements">
						<div class="chatlists">
							<?php
							    $data = $db->getData();
							    ?>
							    <h4 style="margin-left: 10px;">Destination: 
							   		<em id="dest"><?php echo $data['destination']?></em>
							    </h4>
							    <p>Distance: <em><?php echo $data['distance']."Km"?></em></p>
							    <p>Car Drive: <em><?php 
							    	if($data['time'] < 0.5){
							    		echo "Less than 30minutes";
							    	}else {
							    		echo ceil($data['time'])."Hr(s)";
							    	}
							    	?></em></p>
							    <p id="lat1" style="display: none;"><?php echo $data['lat1']?></p>
							    <p id="lng1" style="display: none;"><?php echo $data['lng1']?></p>
							    <p id="lat2" style="display: none;"><?php echo $data['lat2']?></p>
							    <p id="lng2" style="display: none;"><?php echo $data['lng2']?></p>
							    <?php
							?>
							<p id="result"></p>
							<div id="map" style="width:100%; height:500px"></div> 
						</div>
						<div class="chatbox">
							<table><tr>
								<input type="text" id="textautocomplete" placeholder="Enter the address you are traveling to"/></tr>
								<tr><input type="button" onclick="requestPosition()" value="Update Your location" style="width:232px;"><button>Load new T-time</button></tr>
							</table>
						</div>	
					</div>
				</div>
			</div>
			<table>
				<td>
					<tr>
						<label class="mini"> </label>
						<label class="center"> O </label>
						<label class="back"> </label>
					</tr>
				</td>
			</table>
		</div>
	</div>
</body>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=yourapikey&libraries=places"></script>
<script type="text/javascript">
	var nav = null;

	function initMap(){
		let lat1 = document.getElementById('lat1').textContent,
			lng1 = document.getElementById('lng1').innerText,
			lat2 = document.getElementById('lat2').textContent,
			lng2 = document.getElementById('lng2').textContent,
			dest = document.getElementById('dest').textContent,
		    userloc = {lat: parseFloat(lat1), lng:parseFloat(lng1)},
		    destloc = {lat: parseFloat(lat2), lng:parseFloat(lng2)};

		var map = new google.maps.Map(document.getElementById('map'),{
			zoom: 5,
			center: destloc
		});
          
		var marker = new google.maps.Marker({
			position: destloc,
			map: map 
		});

		var markers = new google.maps.Marker({
			position: userloc,
			map: map 
		});

		var info = new google.maps.InfoWindow;
		var content = document.createElement('div');
		var strong = document.createElement('strong');
		strong.textContent = dest;
		content.appendChild(strong);

		marker.addListener('click', function(){
			info.setContent(content);
			info.open(map, marker);
		});
	}
	initMap();
	function requestPosition(){
		if(nav == null)
			nav = window.navigator;
		if(nav != null){
			var geoloc = nav.geolocation;
			if(geoloc != null)
				geoloc.getCurrentPosition(successCallback);
			else
			    alert('geolocation not supported');
		}else{
			alert('Navigator not found');
		}
	}

	function successCallback(position){
		var lat1 = position.coords.latitude,
		    lng1 = position.coords.longitude;

		$.ajax({
			type: 'POST',
    		url: 'location.php', 
    		data:$.param({'lat1': lat1, 'lng1':lng1}),

    		success: function(data){
    			document.getElementById('result').innerHTML = data;
    		},
    		error: function(){
    			alert('error');
    		} 
	    });
	}

	function initialize(){
		var autocomplete = new google.maps.places.Autocomplete(document.getElementById('textautocomplete'));

		google.maps.event.addListener(autocomplete, 'place_changed', function(){
			var place = autocomplete.getPlace();
			var lat2 = place.geometry.location.lat(),
			    lng2 = place.geometry.location.lng(),
			    res = document.getElementById('textautocomplete').value;

			$.ajax({
				type: 'POST',
	    		url: 'location.php', 
	    		data:$.param({'lat2': lat2, 'lng2':lng2, res:res}),

	    		success: function(data){
	    			document.getElementById('result').innerHTML = data;
	    		},
	    		error: function(){
	    			alert('error');
	    		} 
		    });
	    });
	}
	google.maps.event.addDomListener(window, 'load', initialize);
</script>
</html>
