<?php
	require('../class/ChiconUser.class.php');
	require('../class/DB.class.php');
	
	if(isset($_GET['hash'])){
		$hash= $_GET['hash'];
		$db = new DB();
		if(ChiconUser::confirmUserRegistration($db,$hash)){
			header("Location: ../webSite/register.html?confirmed=true");
		
		}else{
			header("Location: ../webSite/register.html?confirmed=false");
		}
	}else{
		echo "Internal Error";
		
	}

?>