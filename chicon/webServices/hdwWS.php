<?php
	require('../class/ChiconHardware.class.php');
	require('../class/ChiconService.class.php');
	require('../class/DB.class.php');
//	header('Content-type: application/json');
	$db = new DB();
	
	if(isset($_GET['mn'])){
		$mn = $_GET['mn'];
		$myHdw = new ChiconHardware($db,$mn);
		if($myHdw->isRegistered()){
			$myHdw->getSerialNumber();
			if(isset($_GET['srv'])){
				if($myHdw->isServiceRegistered($_GET['srv'])){
					$mySrv = ChiconService::withHardware($db,$_GET['srv'],$myHdw->getSerialNumber());
					$mySrv->run();
				}else{
					echo ("{error:'Unknown service'}");
				}
			}elseif(isset($_GET['cfg'])){
				$myHdw->getJsonConfig();
			}elseif(isset($_GET['led'])){
				$mySrv = ChiconService::withHdwLedId($db,$_GET['led'],$myHdw->getSerialNumber());
				if($mySrv != null){
					$mySrv->runSelectedLed();
					$mySrv->run();
				}else{
					echo ("{error:'Unknown service'}");
				}
				
			}
		}else{
			//TODO check if force registered is set, if yes force registered else send error
			echo ("{error:'Unknown hdw'}");
		}
	}else if(isset($_GET['sHdw']) and isset($_GET['rgt'])){
		$myHdw = ChiconHardware::withSerial($db,$_GET['sHdw']);
		if(!$myHdw->isEnrolled()){
			if(isset($myHdw)){
				$result = array("rgt"=>array("mn"=>$myHdw->generateMagicNumber()));
				echo json_encode($result);
			}else{
				echo ("{error:'Unknown hdw'}");
			}
		}else{
			echo ("{error:'Hdw Already Registered'}");
			
			
			
		}
	}else{
		echo ("{error:'Bad arguments'}");
	}

?>