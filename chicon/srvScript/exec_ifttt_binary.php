<?php

function execute($args){
	parse_str($args,$arr);
	if(isset($arr['values'])){
	
		$myArray = array( 0 => array( "id" => 1, "value" => array_map("intval",$arr['values'])));
		
		
	
	}else{
		$myArray = array( 0 => array( "id" => 1, "value" => array(0)));
	
	}
	
	return $myArray;
}

function ledSelected($led,$args=null){
	parse_str($args,$arr);
	if(isset($arr["values"])){
		$arr["values"]	= [0];
	}
	return http_build_query($arr);
}

?>