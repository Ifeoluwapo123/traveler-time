<?php
	require_once('database.php');
	$db = new loc();

	if(isset($_POST['lat1']) && isset($_POST['lng1'])){
		$db->updateUserCurrentLocation($_POST['lat1'],$_POST['lng1']);
		echo "Location successfully updated";
	}else if(isset($_POST['lat2']) && isset($_POST['lng2']) && isset($_POST['res'])){
		$db->updateUserDestinationLocation($_POST['res'],$_POST['lat2'],$_POST['lng2']);
		echo "Location Recorded";
	}
?>
