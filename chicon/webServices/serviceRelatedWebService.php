<?php
	require('../class/DB.class.php');
	$db = new DB();
	if(isset($_GET['cmd'])){
			$cmd = $_GET['cmd'];
			switch($cmd){
				case "getLedDescription":
					if(isset($_GET['srvId'])){
						require('../class/ChiconService.class.php');
						$srvId = $_GET['srvId'];
						$service = new ChiconService($db,$srvId);
						$result = array("result"=>array("code"=>200,"data"=>$service->getLedDescription()));							
					}else{
						$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Bad command")));
					}
					echo json_encode($result);	
				break;
				case "getAllServices":
					require('../class/ChiconServiceFabric.class.php');
					$serviceFabric = new ChiconServiceFabric($db);
					$result = array("result"=>array("code"=>200,"data"=>$serviceFabric->getAllServices()));
					echo json_encode($result);
				break;
				case "getServiceDescription":
					if(isset($_GET['srvId'])){
						require('../class/ChiconService.class.php');
						$srvId = $_GET['srvId'];
						$service = new ChiconService($db,$srvId);
						$result = array("result"=>array("code"=>200,"data"=>$service->getServiceDescription()));							
					}else{
						$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Bad command")));
					}
					echo json_encode($result);
				break;
			}
	}
	

					
?>