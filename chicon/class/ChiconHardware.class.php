<?php



class ChiconHardware{

	private $myDB;
	private $serialNumber =null;
	private $magicNumber;
	private $SQL_REGISTER = "SELECT * FROM users_hdw_list WHERE magicNumber_hdw = :magicNumber AND registered = 1";
	private $SQL_HDW_EXIST = "SELECT * FROM users_hdw_list WHERE serial_hdw = :sHdw"; 
	private $SQL_SERIAL = "SELECT serial_hdw FROM users_hdw_list WHERE magicNumber_hdw = :magicNumber";
	private $SQL_SRV_REGISTER = "SELECT * FROM users_hdw_service_configuration WHERE srvLocalId = :idSrv AND serial_hdw = :serialHdw";
	private $SQL_CONFIG = "SELECT srvlocalid,id_led_Hdw,exec_freq, led_type FROM users_hdw_service_configuration sc , service_list sl, users_hdw_service_led_mapping lm, led_service_list ls WHERE sc.id_service = sl.srvGlobalId AND lm.localId_service = sc.srvLocalId AND ls.id_service = sl.srvGlobalId AND ls.id_led_service =  lm.id_led_service AND sc.serial_hdw = :serialHdw ORDER BY srvlocalid";
	private $SQL_GET_HDW_CONFIG = "SELECT hdwl.common_name,ledhdwl.led_capability, uhdwl.serial_hdw, ledhdwl.id_led_hdw,uhdwlm.id_led_service ,
											lsl.led_type,led_type.icon led_icon, uhdwlm.localId_service,uhdwlconf.id_service,srvl.icon
									FROM hdw_list hdwl, users_hdw_list uhdwl, led_hdw_list ledhdwl 
											LEFT JOIN users_hdw_service_led_mapping uhdwlm 
												ON uhdwlm.serial_hdw = :serialHdw2  AND uhdwlm.id_led_hdw=ledhdwl.id_led_hdw 
											LEFT JOIN users_hdw_service_configuration uhdwlconf
												ON uhdwlm.localId_service = uhdwlconf.srvLocalId
											LEFT JOIN service_list srvl 
												ON uhdwlconf.id_service = srvl.srvGlobalId
											LEFT JOIN led_service_list lsl 
												ON lsl.id_service =  srvl.srvGlobalId AND lsl.id_led_service = uhdwlm.id_led_service
											LEFT JOIN led_type 
												ON lsl.led_type = led_type.led_type
									WHERE uhdwl.id_hdw = hdwl.id  AND ledhdwl.id_hdw =hdwl.id AND  uhdwl.serial_hdw = :serialHdw
									ORDER BY id_led_hdw
									";
	private $SQL_ASSIGN_SERVICE = "INSERT INTO `users_hdw_service_configuration`(`srvLocalId`, `serial_hdw`, `id_service`, `service_args`) VALUES ('',:sHdw,:globalId,'')";
	private $SQL_UNASSIGN_SERVICE = "DELETE FROM `users_hdw_service_configuration` WHERE srvLocalId=:localId";
	private $SQL_MAP_LED = "INSERT INTO `users_hdw_service_led_mapping`(`id`, `serial_hdw`, `localId_service`, `id_led_hdw`, `id_led_service`) VALUES ('',:sHdw,:localId,:idLedHdw,:idLedSrv)";
	private $SQL_UPDATE_MN = "UPDATE `users_hdw_list` SET `magicNumber_hdw`=:mn, registered=1  WHERE serial_hdw=:sHdw";
	private $SQL_HDW_ENROLLED = "SELECT * FROM users_hdw_list WHERE serial_hdw=:sHdw AND registered=1 ";
	private $SQL_HDW_ASSIGN_TO_USER = "INSERT INTO `users_hdw_list` (`id`,`id_user`,`id_hdw`,`serial_hdw`,`firmware_hdw`,`magicNumber_hdw`,`registered`) VALUES ('',:userId,2,:sHdw,'0.1a','',1)";
	private $SQL_HDW_DEL_FROM_USER = "DELETE FROM `users_hdw_list` WHERE serial_hdw = :sHdw AND id_user = :userId";
	
	
	public function __construct($database,$mN){
		$this->myDB = $database;
		$this->magicNumber = $mN;
		
	}
	
	public static function withSerial($db,$serialNumber){
		$instance = new self($db,null);
		$instance->serialNumber = $serialNumber;
		if($instance->exist()) return $instance;
		return null;
	}
	public static function toAssign($db,$serialNumber){
		$instance = new self($db,null);
		$instance->serialNumber = $serialNumber;
		return $instance;
	}
	public function exist(){
		$result = $this->myDB->row($this->SQL_HDW_EXIST, array('sHdw'=>$this->serialNumber));
		if($result !=null){
			return true;
		}
		return false;
	}
	public function isEnrolled(){
		$result = $this->myDB->row($this->SQL_HDW_ENROLLED, array('sHdw'=>$this->serialNumber));
		if($result !=null){
			return true;
		}
		return false;
	}
	function generateMagicNumber(){
		$mn = md5(uniqid('',true));
		$result = $this->myDB->query($this->SQL_UPDATE_MN, array('sHdw'=>$this->serialNumber,'mn'=>$mn));
		$this->magicNumber= $mn;
		return $mn;
	
	}
	public function getAssociatedServices(){
		if($this->serialNumber != null ){
			require('../class/ChiconServiceFabric.class.php');
			$serviceFabric = new ChiconServiceFabric($this->myDB);
			return $serviceFabric->getHdwServices($this->serialNumber);
		}
		
		return null;
	}
	
	public function assignToUser($uid){
		
		if($this->exist()){
		
			return false;
		}
		$result =  $this->myDB->query($this->SQL_HDW_ASSIGN_TO_USER, array('userId'=>$uid,'sHdw'=>$this->serialNumber));
		if($result !=0){
			$this->generateMagicNumber();
			return true;
		}
		return false;
	}
	
	public function delFromUser($uid){
		
		$result =  $this->myDB->query($this->SQL_HDW_DEL_FROM_USER, array('userId'=>$uid,'sHdw'=>$this->serialNumber));
		if($result !=0){
			return true;
		}
		return false;
	}
	
	public function isRegistered(){
		$result = $this->myDB->row($this->SQL_REGISTER, array('magicNumber'=>$this->magicNumber));
		if($result !=null){
			return true;
		}
		return false;
	}
	
	public function getMagicNumber(){
		return $this->magicNumber;
	
	}
	public function getSerialNumber(){
		if($this->serialNumber==null){
			$result = $this->myDB->row($this->SQL_SERIAL, array('magicNumber'=>$this->magicNumber));
			if($result !=null){
				$this->serialNumber = $result['serial_hdw'];
			}
		}
		return $this->serialNumber;
	}
	
	public function isServiceRegistered($id_srv){
		if($this->getSerialNumber() != null){
			$result = $this->myDB->row($this->SQL_SRV_REGISTER, array('serialHdw'=>$this->serialNumber,'idSrv'=>$id_srv));
			if($result !=null){
				return true;
			}
		}
		
		return false;
	
	}
	
	public function getJsonConfig(){
		if($this->getSerialNumber() != null){
			$result = $this->myDB->query($this->SQL_CONFIG, array('serialHdw'=>$this->serialNumber));
			if($result != null){
				$lastSrvLocalId = -1;
				foreach($result as $r){
					$srvLocalId = (int)$r['srvlocalid'];
					$srvFreq = (int)$r['exec_freq'];
					//LED
					$ledId = (int)$r['id_led_Hdw'];
					$ledType = (int)$r['led_type'];
					if($srvLocalId != $lastSrvLocalId){
						//New service create the config
						$srvArray [] = array("id"=> (int)$srvLocalId,"led"=>array(array("id"=>$ledId,"type"=>$ledType)),"freq" => (int)$srvFreq);
					}else{
						//Same service but LED description
						$srvArray[count($srvArray)-1]["led"][] = array("id"=> $ledId,"type" => $ledType);
					}
					$lastSrvLocalId = $srvLocalId;
				}
				$jsonArray = array("srvconfig"=>$srvArray);
				echo json_encode($jsonArray);
			}
		}
	
	}
	
	public function getHdwConfig(){
			$result = $this->myDB->query($this->SQL_GET_HDW_CONFIG, array('serialHdw2'=>$this->serialNumber,'serialHdw'=>$this->serialNumber));
			$i=0;
			foreach($result as $r){
				if($i==0){
					$hdwName = $r['common_name'];
					$serialHdw = $r['serial_hdw'];
					$i=1;
				}
						$ledId = $r['id_led_hdw'];
						$ledIcon = $r['led_icon'];
						$ledCapability = $r['led_capability'];
						$srvGlobalId = $r['id_service'];
						$srvLocalId = $r['localId_service'];
						$srvIdLed = $r['id_led_service'];
						$srvIcon = $r['icon'];
						$srvArray = array("globalId"=>$srvGlobalId,"localId"=>$srvLocalId,"ledId"=>$srvIdLed ,"icon"=>$srvIcon);
						$ledArray [] = array("id" => $ledId,"icon"=>$ledIcon,"capability"=>$ledCapability,"srv"=>$srvArray);
			}
			$hdwArray= array("name"=>$hdwName,"serial"=>$serialHdw,"ledList"=>$ledArray);
			$jsonArray = array("hdw"=>$hdwArray);
			return $jsonArray;
	
		
	}
	
	public function assignService($srvGlobalId){
		$result = $this->myDB->query($this->SQL_ASSIGN_SERVICE, array('sHdw'=>$this->serialNumber,'globalId'=>$srvGlobalId));
		return $this->myDB->lastInsertId();
	}
	
	public function unAssignService($srvLocalId){
		$result = $this->myDB->query($this->SQL_UNASSIGN_SERVICE, array('localId'=>$srvLocalId));
		return $result;
	
	}
	
	public function mapLed($srvLocalId,$ledHdwId,$ledSrvId){
		$result = $this->myDB->query($this->SQL_MAP_LED, array('sHdw'=>$this->serialNumber,'localId'=>$srvLocalId,'idLedHdw'=>$ledHdwId,'idLedSrv'=>$ledSrvId));
		return $this->myDB->lastInsertId();
	}
}

?>