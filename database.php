<?php
	require_once ('config.php');
	class loc{

		private $mMysqli;

		function __construct(){      
			$this->mMysqli = new mysqli(DB_HOST, 
										DB_USER, 
										DB_PASSWORD, 
										DB_DATABASE
									    );    
		}

		function __destruct(){
			$this->mMysqli->close(); 
		}

		public function updateUserCurrentLocation($value,$value1){
        	$this->mMysqli->query("UPDATE location SET lat1 = '$value', lng1 ='$value1' WHERE id = 1");
        }

        public function updateUserDestinationLocation($value,$value1,$value2){
        	$this->mMysqli->query("UPDATE location SET dest = '$value', lat2 ='$value1', lng2 ='$value2' WHERE id = 1");
        }

        public function getData(){
			$query = $this->mMysqli->query('SELECT * FROM location WHERE id=1');
			$row = $query->fetch_row();

			$distance = $this->calDistance($row[2],$row[3],$row[4],$row[5]);
			$distance = floor($distance*100)/100;
			$time = $distance/60;
			$time = floor($time*100)/100;

			$data = array('destination'=>$row[1],
						         'lat1'=>$row[2],
						 		 'lng1'=>$row[3],
						 		 'lat2'=>$row[4],
						 		 'lng2'=>$row[5],
						     'distance'=>$distance,
						         'time'=>$time);

			return $data;
        }

        public function calDistance($lat1,$lng1,$lat2,$lng2){
        	$R = 6371;
        	$d_lat = $this->toRad($lat2-$lat1);
        	$d_lng = $this->toRad($lng2-$lng1);
        	$lat1  = $this->toRad($lat1);
        	$lat2  = $this->toRad($lat2);
        	$a = sin($d_lat/2)*sin($d_lat/2)+sin($d_lng/2)*sin($d_lng/2)*cos($lat1)*cos($lat2);
        	$c = 2 * atan2(sqrt($a), sqrt(1-$a));
        	$distance = $R * $c;

        	return $distance;
        }

        public function toRad($value){
        	return $value * pi() / 180;
        }
    }
?>