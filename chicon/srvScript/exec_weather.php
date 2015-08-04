<?php
	define('CURRENT_WEATHER_API_URL',"http://api.openweathermap.org/data/2.5/weather?id=");
	define('FORECAST_WEATHER_API_URL',"http://api.openweathermap.org/data/2.5/forecast/daily?id=");
	define('QUERY',"&units=metric");
	
	function execute($args){
		parse_str($args,$arr);	
		switch ($arr['forecast']){
			case "current":
				$url = file_get_contents(CURRENT_WEATHER_API_URL.$arr['city_select'].QUERY);
				$jsonArr = json_decode($url,true);
				$weatherCode = $jsonArr['weather'][0]['id'];
				$rgb = mapWeatherCode($weatherCode);
				break;
			case "tom":
				$url = file_get_contents(FORECAST_WEATHER_API_URL.$arr['city_select'].QUERY);
				$jsonArr = json_decode($url,true);
				$weatherCode = $jsonArr['list'][1]['weather'][0]['id'];
				$rgb = mapWeatherCode($weatherCode);
				break;
			case "dat":
				$url = file_get_contents(FORECAST_WEATHER_API_URL.$arr['city_select'].QUERY);
				$jsonArr = json_decode($url,true);
				$weatherCode = $jsonArr['list'][2]['weather'][0]['id'];
				$rgb = mapWeatherCode($weatherCode);
				break;
		}
		
		$myArray = array(0 => array( "id" => 1, "value" => array($rgb[0],$rgb[1],$rgb[2])));
		return $myArray;
	}
	
	function mapWeatherCode($code){
	
		//Storm between 200 & 232
		if($code > 199 and $code < 233){
			$R = 120;
			$G = 120;
			$B = 120;
		}elseif(($code > 299 and $code < 322) or ($code < 523 and $code > 499)){
			//Rain between 300 & 321 + 500 & 522
			$R = 0;
			$G = 80;
			$B = 255;
		}elseif($code > 599 and $code < 622){
			//Snow 600 & 621
			$R = 255;
			$G = 255;
			$B = 255;
		}elseif($code > 700 and $code < 742){
			//Fog 701 & 741
			$R = 227;
			$G = 243;
			$B = 255;
		}elseif($code == 800 or $code == 801){
			//Sun 800
			$R = 255;
			$G = 255;
			$B = 0;
		}elseif($code == 802){			
			//Cloud 802
			$R = 180;
			$G = 180;
			$B = 180;
		}elseif($code == 803){		
			//803
			$R = 150;
			$G = 150;
			$B = 150;
		}elseif($code == 804){				
			//804
			$R = 120;
			$G = 120;
			$B = 120;
		}
		return array($R,$G,$B);
	}
	function ledSelected($led,$args=null){
		return null;
	}
	
?>