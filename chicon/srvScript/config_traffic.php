<?php
define('GEOKEY','nuevedwswsaamm3yfpd4xcuq');
define('GEOCODE_API_URL','https://api.tomtom.com/lbs/geocoding/geocode');
define('MAPKEY','vpvt7n3rdxk3af8rhxyf952u');
define('TRAFFIC_API_URL','https://api.tomtom.com/lbs/services/route/3/');

function configure($args){
	
	if(isset($args['from']) and isset($args['to'])){
		$url = file_get_contents(GEOCODE_API_URL."?format=json&key=".GEOKEY."&query=".urlencode($args['from']));
		$arr = json_decode($url,true);
		echo myForm($arr['geoResponse']);
		
		
		$url = file_get_contents(GEOCODE_API_URL."?format=json&key=".GEOKEY."&query=".urlencode($args['to']));
		$arr = json_decode($url,true);
		echo myForm($arr['geoResponse'],true);
		
		return null;
	}elseif(isset($args['selectTo']) and isset($args['selectFrom']) and !isset($args['time'])){
		$coordinate = $args['selectTo'].":".$args['selectFrom'];
		$url = file_get_contents(TRAFFIC_API_URL.$coordinate."/Quickest/json?key=".MAPKEY."&includeTraffic=false&includeInstructions=false");
		$jsonArr = json_decode($url,true);
		$currentTime = $jsonArr['route']['summary']['totalTimeSeconds'];
		$args['time'] = $currentTime;
		echo myForm($args);
		return null;
	}elseif(isset($args['selectTo']) and isset($args['selectFrom']) and isset($args['min']) and isset($args['max']) and isset($args['time'])){
		$result = "coordinate=".$args['selectFrom'].":".$args['selectTo']."&min=".$args['min']."&max=".$args['max']."&time=".$args['time'];
		echo $result;
		return $result;
		
	}
	
	echo myForm();
	return null;
	
}

function myForm($address=null,$destAddr=false){
	if(isset($address["geoResult"])){
		if($destAddr){
			$selectName = "selectTo";
			$str="";
		}else{
			$selectName = "selectFrom";
			$str = "<H4>Confirm from and To address</H4>";
		}
		$str .= "<SELECT name='$selectName'>";
		/*Build address list from TomTom response*/
		foreach ($address["geoResult"] as $value) {  
			$str .="<option value='".$value['latitude'].",".$value['longitude']."'>";
			$str .=$value['formattedAddress'];
			$str .="</OPTION>";
		}
		$str .="</SELECT>";
		
		
		
		
		return $str;
	
	}elseif(isset($address['selectTo']) and isset($address['selectFrom'])){
			$str = "Normal Time : <input type='hidden' value=".$address['time']." name='time'/>";
			$str .= gmdate("H:i:s",$address['time']);
			$str .="<H4>Enter thresholds</H4>";
			$str .="Min : <INPUT name='min' type='text'/>";
			$str .="Max : <INPUT name='max' type='text'/>";
			$str .= "<INPUT type='hidden' name='selectTo' value='".$address['selectTo']."'/>";
			$str .= "<INPUT type='hidden' name='selectFrom' value='".$address['selectFrom']."'/>";
			return $str;
	
	}
	$str = "<H4>Enter From and To address</H4>";
	$str  .="<INPUT name='from' type='text'/>";
	$str .="<INPUT name='to' type='text' />";
	return $str;
	
	
}