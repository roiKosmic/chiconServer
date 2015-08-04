<?php
include("./mailFunc.php");
$fromAddr = $_POST['fromAddr'];
$subject = $_POST['subject'];
$mailBody = $_POST['mailBody'];
$asm = $_POST['asm'];

if(isset($asm) && $asm==''){
	sendMailToContact($fromAddr,$subject,$mailBody);
	sendMailFromContact($fromAddr,"Confirmation","You have just send a message to the Chic'on Team. We will get back to you as soon as possible!\nThank You\n\n Chic'on Team");
}
$result = array("result"=>array("code"=>200,"data"=>"Message sent"));	
echo json_encode($result);
?>
