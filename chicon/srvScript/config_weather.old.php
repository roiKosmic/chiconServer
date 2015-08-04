<?php

function configure($args){
	if(isset($args)){
	
	
	}else{
		echo myForm();
	
	}


}

function myForm(){
	$str = "<INPUT name='city' type='text'/>";
	return $str;


}



?>


<HTML>
<BODY>
<Form method="GET">
<?
/*
FORM to get city 
First input city name
Second select city from a list retreive from openweathermap
Third get weather forecast on selected city
*/


/*Global variable*/
$weather_api_url ="http://api.openweathermap.org/data/2.1/find/name?q=";
$weather_forecast_url="http://api.openweathermap.org/data/2.2/forecast/city/";
$weather_forecast_query="?mode=daily_compact&units=metric";
$city = $_GET["city"];
$city_select = $_GET["city_select"];

if(isset($city)){
	/*Find city list*/
	$url = file_get_contents($weather_api_url.$city);
	$arr = json_decode($url,true);
	//print_r($arr);
	if(strcmp($arr['cod'],"200")==0){
		echo("<H1>Choose your city from the list</H1>");	
?>
		<SELECT name="city_select">
<?
		/*Build city list from openweather response*/
		foreach ($arr["list"] as $value) {  
			echo("<option value='".$value['id']."'>");
			echo($value['name']."-".$value['sys']['country']);
			echo("</OPTION>");
		}
 ?>
		</SELECT>
 <?
	}else{
		echo("<H1>Error city not found !</H1>");
	}

/*GET Weather forecast*/	
}elseif(isset($city_select)){
	echo("Weather forecast for city id $city_select");
	$url = file_get_contents($weather_forecast_url.$city_select.$weather_forecast_query);
	$arr = json_decode($url,true);
	//print_r($arr);
	/*Print forecasts*/
	foreach ($arr["list"] as $value){  
	 echo("<H2>DAY - ".$value['temp']."&deg;C - ".$value['weather'][0]['id']."</H2>");
	}

/*First form asking for city input*/
}else{
?>
	<INPUT name="city" type="text"/>
<?}?>
<input type="submit"/>
</FORM>
</BODY>
</HTML>

