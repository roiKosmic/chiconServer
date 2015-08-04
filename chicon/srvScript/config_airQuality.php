
<?php
define('airQuality_api_url',"http://www.lcsqa.org/surveillance/indices/prevus/jour/xml/");

function configure($args){
	if(isset($args['city_select'])){
		return "city_select=".$args['city_select']."&forecast=".$args['forecast'];
	}
	echo myForm();
	return null;
	
}

function myForm($cityTab=null){
	$today = date("Y-m-d"); 
	$API_URL = airQuality_api_url.$today;
	$nodes = simplexml_load_file($API_URL);
	$str = "<H4>Please choose your city</H4>";
	$str .= "<SELECT name='city_select'>";
	foreach ($nodes as $myNode){
        $city=$myNode->agglomeration;
		$cityCode = $myNode->agglomerationCodeInsee;
		$str .="<option value='".$cityCode."'>";
		$str .=$city;
		$str .="</OPTION>";
	}
	$str .="</SELECT>";
	$str .= "<H4>Please choose air quality forecast:</H4>";
	$str .= "<input type='radio' name='forecast' value='current'>Today<br>";
	$str .= "<input type='radio' name='forecast' value='tom'>Tomorow<br>";
	
	return $str;
		
}



?>


