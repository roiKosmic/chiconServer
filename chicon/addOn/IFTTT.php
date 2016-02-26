<?php
include("ChiconIFTTTGateway.class.php");

$json = file_get_contents('php://input');
if(isset($json)){
	if(ChiconIFTTTGateway::triggerFromIfTtt($json)){
		
	}else{
		header('HTTP/1.1 500 Internal Server Error');
	}

}else{
	header('HTTP/1.1 500 Internal Server Error');
}


?>