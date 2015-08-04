<?php
	function execute($args){
		$tricolorLed = rand(0,2);
		$binaryLed = rand(0,1);
		$fadLedR = rand(0,255);
		$fadLedG = rand(0,255);
		$fadLedB = rand(0,255);
		$myArray = array(array( "id" => 1, "value" => array($tricolorLed)),array( "id" => 2, "value" => array($binaryLed)),array( "id" => 3, "value" => array($fadLedR,$fadLedG,$fadLedB)));
		return $myArray;
	}

	function ledSelected($led,$args=null){
		return null;
	}
	
?>