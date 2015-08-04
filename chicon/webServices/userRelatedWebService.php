<?php
	require('../class/ChiconUser.class.php');
	require('../class/DB.class.php');
	
	$db = new DB();
	$user = new ChiconUser($db);
	if($user->isLoggedIn()){
		if(isset($_GET['cmd'])){
			$cmd = $_GET['cmd'];
			switch($cmd){
				case "getServices":
					$result = array("result"=>array("code"=>200,"data"=>$user->getServices()));
					echo json_encode($result);
					break;
				case "getHardwares":
					$result = array("result"=>array("code"=>200,"data"=>$user->getHardwares()));
					echo json_encode($result);
					break;
				case "getDetails":
					$result = array("result"=>array("code"=>200,"data"=>$user->getDetails()));
					echo json_encode($result);
					break;
				case "getAssociatedServices":
					if(isset($_GET['hdwSerial']) and $user->isUserHardware($_GET['hdwSerial'])){
						require('../class/ChiconHardware.class.php');
						$hdwSerial = $_GET['hdwSerial'];
						$hardware = ChiconHardware::withSerial($db,$hdwSerial);
						$result = array("result"=>array("code"=>200,"data"=>$hardware->getAssociatedServices()));							
					}else{
						$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Bad command")));
					}
					echo json_encode($result);	
					break;
				case "getHdwConfig":
					if(isset($_GET['hdwSerial']) and $user->isUserHardware($_GET['hdwSerial'])){
						require('../class/ChiconHardware.class.php');
						$hdwSerial = $_GET['hdwSerial'];
						$hardware = ChiconHardware::withSerial($db,$hdwSerial);
						$result = array("result"=>array("code"=>200,"data"=>$hardware->getHdwConfig()));							
					}else{
						$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Bad command")));
					}
					echo json_encode($result);	
					break;
					
				case "assignService":
					if(isset($_GET['hdwSerial']) and isset($_GET['srvId']) and $user->isUserHardware($_GET['hdwSerial'])){
						require('../class/ChiconHardware.class.php');
						$hdwSerial = $_GET['hdwSerial'];
						$globalId = $_GET['srvId'];
						$hardware = ChiconHardware::withSerial($db,$hdwSerial);
						$result = array("result"=>array("code"=>200,"data"=>$hardware->assignService($globalId)));
					}else{
						$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Bad command")));
					}
					echo json_encode($result);
					break;
					
				case "unAssignService":
					if(isset($_GET['hdwSerial']) and isset($_GET['srvLocalId']) and $user->isUserHardware($_GET['hdwSerial'])){
						require('../class/ChiconHardware.class.php');
						$hdwSerial = $_GET['hdwSerial'];
						$localId = $_GET['srvLocalId'];
						$hardware = ChiconHardware::withSerial($db,$hdwSerial);
						$result = array("result"=>array("code"=>200,"data"=>$hardware->unAssignService($localId)));
					}else{
						$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Bad command")));
					}
					echo json_encode($result);
					break;
					
				case "configureService":
					if(isset($_GET['srvLocalId']) and isset($_GET['hdwSerial']) and $user->isUserHardware($_GET['hdwSerial'])){
						require('../class/ChiconHardware.class.php');
						require('../class/ChiconService.class.php');
						$hdwSerial = $_GET['hdwSerial'];
						$localId = $_GET['srvLocalId'];
						$myHdw = ChiconHardware::withSerial($db,$hdwSerial);
						if($myHdw->isServiceRegistered($localId)){
								$mySrv = ChiconService::withHardware($db,$_GET['srvLocalId'],$hdwSerial);
								$r = processConfig($mySrv,$myHdw);
								if(isset($r)){
									$mySrv->setConfigArgs($r);
								}
						}
					}
				break;
				
				case "mapLed":
					if(isset($_GET['srvLocalId']) and isset($_GET['hdwSerial']) and isset($_GET['ledHdwId']) and isset($_GET['ledSrvId']) and $user->isUserHardware($_GET['hdwSerial'])){
						require('../class/ChiconHardware.class.php');
						$hdwSerial = $_GET['hdwSerial'];
						$localId = $_GET['srvLocalId'];
						$ledHdwId = $_GET['ledHdwId'];
						$ledSrvId = $_GET['ledSrvId'];
						
						$myHdw = ChiconHardware::withSerial($db,$hdwSerial);
						if($myHdw->isServiceRegistered($localId)){
							$myHdw->mapLed($localId,$ledHdwId,$ledSrvId);
						}
					}
				break;
				
				case "assignToUser":
					if(isset($_GET['hdwSerial'])){
						require('../class/ChiconHardware.class.php');
						$hdwSerial = $_GET['hdwSerial'];
						$myHdw = ChiconHardware::toAssign($db,$hdwSerial);
						if($myHdw->assignToUser($user->getUserId())){
							$result = array("result"=>array("code"=>200,"data"=>"Hardware assigned"));
							//Envoyer assign E-mail avec magic Number
							require("../misc/magicNumberMail.php");
							require("../misc/mailFunc.php");
							sendMagicNumberHdwMail($user->getUserName(),$hdwSerial,$myHdw->getMagicNumber());
						}else{
							$result = array("result"=>array("code"=>400,"data"=>"Hardware already assigned"));
						}
					}else{
						$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Bad command")));
					}
					echo json_encode($result);
					break;
					
				case "requestSerial":
					if(isset($_GET['hdwType'])){
						$hdwType = $_GET['hdwType'];
						$serial = $user->requestSerial($hdwType);
						//Envoyer serial E-mail
						if(isset($serial)){
							require("../misc/serialMail.php");
							require("../misc/mailFunc.php");
							sendSerialHdwMail($user->getUserName(),$serial);
							$result = array("result"=>array("code"=>200,"data"=>"Serial Generated"));
						}else{
							$result = array("result"=>array("code"=>400,"data"=>"Serial Generation Error"));
						}
					}else{
						$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Bad command")));
					}
					echo json_encode($result);
				break;
					
				case "delFromUser":
					if(isset($_GET['hdwSerial'])){
						require('../class/ChiconHardware.class.php');
						$hdwSerial = $_GET['hdwSerial'];
						$myHdw = ChiconHardware::toAssign($db,$hdwSerial);
						if($myHdw->delFromUser($user->getUserId())){
							$result = array("result"=>array("code"=>200,"data"=>"Hardware deleted"));
						}else{
							$result = array("result"=>array("code"=>400,"data"=>"Hardware deletion error"));
						}
					}else{
						$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Bad command")));
					}
					echo json_encode($result);
					break;
				
				case "ledSelected":
					if(isset($_GET['hdwSerial']) and isset($_GET['led']) and $user->isUserHardware($_GET['hdwSerial'])){
						require('../class/ChiconService.class.php');
						$mySrv = ChiconService::withHdwLedId($db,$_GET['led'],$_GET['hdwSerial']);
						if($mySrv != null){
							$mySrv->runSelectedLed();
							$result = array("result"=>array("code"=>200,"data"=>"Led selection success"));
						}else{
							$result = array("result"=>array("code"=>400,"data"=>"unknown service"));
						}
					
					}else{
						$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Bad command")));
					}
					echo json_encode($result);
					break;
				case "getCartItemsCount":
					require('../class/ChiconCart.class.php');
					$myCart = ChiconCart::restoreFromSession();
					$result = array("result"=>array("code"=>200,"data"=>array("cart"=>$myCart->countItems())));
					echo json_encode($result);
					break;
					
				case "addToCart":
					if(isset($_GET['srvId'])){
						require('../class/ChiconCart.class.php');
						$srvId = $_GET['srvId'];
						$myCart = ChiconCart::restoreFromSession();
						if($myCart->addService($srvId)){
							$result = array("result"=>array("code"=>200,"data"=>array("cart"=>"Service Added")));
						}else{
							$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Add To Cart error")));
						}
					}else{
						$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Bad command")));
					}
					echo json_encode($result);
					break;
					
				case "getCartDescription":
					require('../class/ChiconCart.class.php');
					$myCart = ChiconCart::restoreFromSession();
					$result = array("result"=>array("code"=>200,"data"=>$myCart->getDescription($db)));;
					echo json_encode($result);
					break;
					
				case "removeCartItems":
					if(isset($_GET['srvId'])){
						require('../class/ChiconCart.class.php');
						$srvId = $_GET['srvId'];
						$myCart = ChiconCart::restoreFromSession();
						if($srvId=="all"){
							if($myCart->removeAll()){
								$result = array("result"=>array("code"=>200,"data"=>array("cart"=>"All services removed")));
							}else{
								$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Remove from cart Error")));
							}
						}else{
							if($myCart->removeService($srvId)){
								$result = array("result"=>array("code"=>200,"data"=>array("cart"=>"Service removed")));
							}else{
								$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Remove from cart Error, unknown service")));
							}
						}
					}else{
						$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Bad command")));
					}
					echo json_encode($result);
					break;
					
				case "confirmCartOrder":
					require('../class/ChiconCart.class.php');
					$myCart = ChiconCart::restoreFromSession();
					$result = array("result"=>array("code"=>200,"data"=>$myCart->confirmOrder($db,$user->getUserId())));
					echo json_encode($result);
					break;
					
				default:
					$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Bad command")));
					echo json_encode($result);
			}
			
			
		}
	
	}else{
		if(isset($_GET['cmd'])){
			$cmd = $_GET['cmd'];
			
				if($cmd=="addUser"){
					if(isset($_GET['asm']) and $_GET['asm']==''){
						if(isset($_GET['userLogin']) and isset($_GET['userPassword'])){
							$login = $_GET['userLogin'];
							$password = $_GET['userPassword'];
							$familyName=$_GET['familyName'];
							$firstName = $_GET['firstName'];
							$user = ChiconUser::addUser($db,$login,$password,$firstName,$familyName);
						
							if($user !=null){
								$result = array("result"=>array("code"=>200,"data"=>"User created"));	
							}else{
								$result = array("result"=>array("code"=>400,"data"=>array("error"=>"User not created")));
							}
						
						}else{
							$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Bad command parameter")));
						}
					}else{
						$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Spam suspected")));
					}
				}else{
						$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Unknown command")));
				}
		}else{
			$result = array("result"=>array("code"=>400,"data"=>array("error"=>"Not Authenticated")));
		}
		
		echo json_encode($result);
		
	}
	
	
	function processConfig($srv,$hdw){
		$hdwSerial = $hdw->getSerialNumber();
		$srvLocalId = $srv->getSrvLocalId();
		echo "<form id='configServiceForm' class='entryForm'>";
		echo "<input type='hidden' name='hdwSerial' value='$hdwSerial'/>";
		echo "<input type='hidden' name='srvLocalId' value='$srvLocalId'/>";
		echo "<input type='hidden' name='cmd' value='configureService'/>";
		$args = getConfigArguments();
		$result = $srv->config($args);
		if(!isset($result)){
			echo "<input type='submit' id='subCfg'/>";
		}else{
			echo "Your service is now configured<BR>";
		}
		echo "</form>";
		return $result;
	}
	
	function getConfigArguments(){
		$query = $_SERVER['QUERY_STRING'];
		parse_str($query, $q_array);
		$query = $_SERVER['QUERY_STRING'];
		parse_str($query, $q_array);

		unset($q_array['srvLocalId']);
		unset($q_array['hdwSerial']);
		unset($q_array['cmd']);
		return $q_array;
	
	}
	?>