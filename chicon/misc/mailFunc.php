<?php
    function sendMailToContact($fromAddr,$subject,$text){
		$headers ='From:'.$fromAddr."\n";
        $headers .='Reply-To: '.$fromAddr."\n";
        $headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
        $headers .='Content-Transfer-Encoding: 8bit';
        mail('contact@chicon.fr', $subject,$text, $headers); 
	}

	function sendMailFromContact($toAddr,$subject,$text){
		$headers ='From:contact@chicon.fr'."\n";
        $headers .='Reply-To: contact@chicon.fr'."\n";
        $headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
        $headers .='Content-Transfer-Encoding: 8bit';
        mail($toAddr, $subject,$text, $headers); 
	
	
	}

							
?>