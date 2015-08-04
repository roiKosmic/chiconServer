<?php
	require('../class/ChiconUser.class.php');
	require('../class/DB.class.php');
	
	$db = new DB();
	$user = new ChiconUser($db);
	if($user->isLoggedIn()){
		echo $user->getServices();
	}else{
		echo "Please log in first";
	}
	
	
?>