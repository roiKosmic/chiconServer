<?php

//define('MAPKEY','vpvt7n3rdxk3af8rhxyf952u');
//define('TRAFFIC_API_URL','https://api.tomtom.com/lbs/services/route/3/');

//print_r(execute("coordinate=50.639419,3.019652:50.338736,3.554807&min=30&max=50&time=2000"));
	
	function execute($args){
		parse_str($args,$arr);
		$coordinate = $arr['coordinate'];
		$minThreshold = $arr['min'];
		$maxThreshold = $arr['max'];
		$normalTotalTime = $arr['time'];
		
		$url = file_get_contents(TRAFFIC_API_URL.$coordinate."/Quickest/json?key=".MAPKEY."&includeTraffic=true&includeInstructions=false");
		$jsonArr = json_decode($url,true);
		$currentTime = $jsonArr['route']['summary']['totalTimeSeconds'];
		
		if($currentTime <= $normalTotalTime){
			$value = 1;
		}elseif(percentage($normalTotalTime,$currentTime) < $minThreshold){
			$value = 1;
		}elseif(percentage($normalTotalTime,$currentTime) > $maxThreshold){
			$value = 3;
		}else{
			$value = 2;
		}		
		$myArray = array( 0 => array( "id" => 1, "value" => array($value)));
		return $myArray;
	}
		
	function percentage($nbr, $total) {
		return 100*($total - $nbr)/$nbr;
	}
	
	function ledSelected($led,$args=null){
		
		return null;
	}
		
?>
