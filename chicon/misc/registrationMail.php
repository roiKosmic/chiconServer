<?php

function sendRegistrationMail($mailAddr,$hash){
	$url = "http://www.chicon.fr/chicon/misc/confirmRegistration.php?hash=".$hash;
	sendMailFromContact($mailAddr,"Account creation Confirmation","You have just created an account on Chic'on website\nPlease confirmed your account at :\n\n".$url."\n\nThank You\n\n Chic'on Team");
	sendMailToContact($mailAddr,"Account Creation","A new account was created for ".$mailAddr);
	return true;
}


?>