<?php
define('airQuality_api_url',"http://www.lcsqa.org/surveillance/indices/prevus/jour/xml/");

	function execute($args){
		parse_str($args,$arr);	
		switch ($arr['forecast']){
			case "current":
				$myDate = date("Y-m-d"); 
				break;
			case "tom":
				$myDt = new DateTime('tomorrow');
				$myDate = $myDt->format('Y-m-d');
				break;	
		}
		$API_URL = airQuality_api_url.$myDate;
		$nodes = simplexml_load_file($API_URL);
		foreach ($nodes as $myNode){
			if($arr['city_select']== $myNode->agglomerationCodeInsee){
				$indice = $myNode->valeurIndice;
				break;
			}
		}	
		if($indice<5){
			$returnValue = 1;
		}else if($indice <8){
			$returnValue = 2;
		}else if($indice < 11){
			$returnValue = 3;
		}
		$myArray = array( 0 => array( "id" => 1, "value" => array($returnValue)));
		
		return $myArray;
	}
	
	function ledSelected($led,$args=null){
		return null;
	}
	
?>