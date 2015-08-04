<?php
	require('../class/ChiconUser.class.php');
	require('../class/DB.class.php');
	if(isset($_GET['username']) and isset($_GET['password'])){
		$db = new DB();
		$user = new ChiconUser($db);
		if($user->login($_GET['username'],$_GET['password'])){
			$userDetails = array("user"=>array("username"=>$_GET['username']));
			$result = array("result"=>array("code"=>200,"data"=>$userDetails));
			echo json_encode($result);
		}else{
			$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Unknown user")));
			echo json_encode($result);
		}
	}else if(isset($_GET['logout'])){
		$db = new DB();
		$user = new ChiconUser($db);
		if($user->isLoggedIn()){
			$userDetails = array("user"=>array("username"=>$user->getUsername()));
			$user->logout();
			$result = array("result"=>array("code"=>200,"data"=>$userDetails));
			echo json_encode($result);
		}else{
			$result = array("result"=>array("code"=>400,"data"=>array("error"=>"No Session to logout")));
			echo json_encode($result);
		}
	
	}else if(isset($_GET['isLoggedIn'])){
		$db = new DB();
		$user = new ChiconUser($db);
		if($user->isLoggedIn()){
			$userDetails = array("user"=>array("username"=>$user->getUsername()));
			$result = array("result"=>array("code"=>200,"data"=>$userDetails));
		}else{
			$result = array("result"=>array("code"=>200,"data"=>"notLogged"));
		}
		echo json_encode($result);
	}else{
		$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Unknown user")));
		echo json_encode($result);
	}

?>