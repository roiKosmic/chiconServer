
<?php
define('weather_api_url',"http://api.openweathermap.org/data/2.5/find?type=like&q=");



function configure($args){
	
	if(isset($args['city'])){
		$url = file_get_contents(weather_api_url.$args['city']);
		$arr = json_decode($url,true);
		if(strcmp($arr['cod'],"200")==0){
			echo myForm($arr);
			return null;
		}else{
			echo("<H1>Error city not found !</H1>");
			return null;
		}
	}
	if(isset($args['city_select'])){
		if(!isset($args['forecast'])){
			echo myForm($args);
			return null;
		}else{
			return "city_select=".$args['city_select']."&forecast=".$args['forecast'];
		}
	}
	
	echo myForm();
	return null;
	
}

function myForm($cityTab=null){
	if(!isset($cityTab)){
		$str = "<H4>Please enter your city</H4>";
		$str .= "<INPUT name='city' type='text'/>";
		return $str;
	}
	if(isset($cityTab['city_select'])){
		$str = "<input type='radio' name='forecast' value='current'>Current<br>";
		$str .= "<input type='radio' name='forecast' value='tom'>Tomorow<br>";
		$str .= "<input type='radio' name='forecast' value='dat'>Day after tommorow<br>";
		$str .= "<input type='hidden' name='city_select' value='".$cityTab ['city_select']."'/>";

		return $str;
	}
		$str = "<H4>Choose your city from the list</H4>";
		$str .= "<SELECT name='city_select'>";
		/*Build city list from openweather response*/
		foreach ($cityTab["list"] as $value) {  
			$str .="<option value='".$value['id']."'>";
			$str .=$value['name']."-".$value['sys']['country'];
			$str .="</OPTION>";
		}
		$str .="</SELECT>";
		return $str;
	
}



?>


