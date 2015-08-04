<?php

function sendMagicNumberHdwMail($mailAddr,$serial,$mn){
	sendMailFromContact($mailAddr,"Hardware assigned - Magic Number","You have assign an hardware to your account, magic number was generated successfully\n\nSerial : ".$serial."\nMagic Number: ".$mn."\n\nThank You\n\n Chic'on Team");
	sendMailToContact($mailAddr,"Hardware assigned - Magic Number","A new hardware was assigned to ".$mailAddr."\nSerial : ".$serial."\nMagic Number: ".$mn."\n");
	return true;
}


?>