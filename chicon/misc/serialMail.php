<?php

function sendSerialHdwMail($mailAddr,$serial){
	sendMailFromContact($mailAddr,"Serial request confirmation","You have just requested a serial for your hardware\nSerial generated is :".$serial."\n\nThank You\n\n Chic'on Team");
	sendMailToContact($mailAddr,"Serial request confirmation","A new serial was generated for ".$mailAddr."\nSerial : ".$serial);
	return true;
}


?>