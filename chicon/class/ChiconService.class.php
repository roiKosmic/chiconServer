<?php
global $SRV_INSTANCE;
class ChiconService{
	
	private $myDB;
	private $srvLocalId=null;
	private $srvGlobalId=null;
	private $serialParentHdw = null;
	private $runScript;
	private $selectedLed;
	private $common_name;
	private $icon;
	private $description;
	
	
	private $SQL_SRV_GLOBAL_DESC = "SELECT * FROM service_list WHERE srvGlobalId = :srvId";
	private $SQL_SRV_LOCAL_DESC = "SELECT * FROM users_hdw_service_configuration WHERE srvLocalId = :srvId AND serial_hdw = :hdwSerial";
	private $SQL_SRV_LED_MAPPING = "SELECT * FROM users_hdw_service_led_mapping WHERE serial_hdw = :hdwSerial AND localId_service = :srvId AND id_led_service = :idLed";
	private $SQL_SRV_UPDATE_CONFIG_ARGS = "UPDATE users_hdw_service_configuration SET service_args = :configArgs WHERE srvLocalId = :srvId AND serial_hdw = :hdwSerial";
	private $SQL_SRV_LED_DESC = "SELECT id_led_service,common_name,description,icon,lt.led_type FROM led_service_list lsl, led_type lt WHERE lt.led_type = lsl.led_type AND id_service = :srvId";
	private $SQL_SRV_GET_FROM_HDWLED = "SELECT id_service,id_led_service,srvLocalId FROM users_hdw_service_led_mapping ulm, users_hdw_service_configuration udwc WHERE ulm.serial_hdw = udwc.serial_hdw AND udwc.srvLocalId = ulm.localId_service AND ulm.serial_hdw = :hdwSerial AND id_led_hdw = :hdwIdLed";
	private $SQL_ASSIGN_SERVICE_TO_USER = "INSERT into users_service_list (id,id_user,id_srv) VALUES (NULL, :uid, :srvId)";
	
	public function __construct($database,$globalId=null,$localId=null){
		
		$this->myDB = $database;
		$this->srvGlobalId = $globalId;
		if($globalId !=null){
			$result = $this->myDB->row($this->SQL_SRV_GLOBAL_DESC, array('srvId'=>$this->srvGlobalId));
			if($result !=null){
				$this->srvLocalId=$localId;
				$this->common_name = $result['common_name'];
				$this->icon = $result['icon'];
				$this->description = $result['description'];
			}
		
		}
	}
	public function assignServiceToUser($uid){
		$result = $this->myDB->query($this->SQL_ASSIGN_SERVICE_TO_USER, array('uid'=>$uid,'srvId'=>$this->srvGlobalId));
		if($result != 0){
			return true;
		}
		return false;
	}
	
	public function getIcon(){
		return $this->icon;
	}
	public function getDescription(){
		return $this->description;
	
	}
	public function getCommonName(){
		return $this->common_name;
	}
	
	public function getGlobalId(){
		return $this->srvGlobalId;
	}
	public static function withHardware($db,$id,$serialHdw){
		$instance = new self($db,null);
		$instance->srvLocalId = $id;
		$instance->serialParentHdw = $serialHdw;
		$instance->init();
		return $instance;
	}
	
	public static function withHdwLedId($db,$idLed,$serialHdw){
		$instance = new self($db,null);
		$result = $instance->myDB->row($instance->SQL_SRV_GET_FROM_HDWLED,array('hdwSerial'=>$serialHdw,'hdwIdLed'=> $idLed));
		if($result != null){
			$instance->srvGlobalId = $result['id_service'];
			$instance->selectedLed = $result['id_led_service'];
			$instance->srvLocalId = $result['srvLocalId'];
			$instance->serialParentHdw = $serialHdw;
			$instance->init();
			return $instance;
		}
		return null;
	}
	
	public function getSrvLocalId(){
		return $this->srvLocalId;
		
	}
	private function init(){
		//Include the exec script file and config script
		if($this->getSrvGlobalId() !=null){
			$result = $this->myDB->row($this->SQL_SRV_GLOBAL_DESC, array('srvId'=>$this->srvGlobalId));
			if($result !=null){
				global $SRV_INSTANCE;
				$SRV_INSTANCE = $this;
				include("../".$result['exec_script']);
				include("../".$result['config_script']);
				return true;
			}
		}
		return false;
	}
	
	public function getSrvGlobalId(){
		if($this->srvGlobalId==null and $this->serialParentHdw !=null){
			$result = $this->myDB->row($this->SQL_SRV_LOCAL_DESC, array('hdwSerial'=>$this->serialParentHdw,'srvId'=>$this->srvLocalId));
			if($result !=null){
				$this->srvGlobalId = $result['id_service'];
			}
		}
		return $this->srvGlobalId;
	
	}
	
	public function getRegisteredSrvArguments(){
		if($this->srvLocalId !=null and $this->serialParentHdw !=null){
			$result = $this->myDB->row($this->SQL_SRV_LOCAL_DESC, array('hdwSerial'=>$this->serialParentHdw,'srvId'=>$this->srvLocalId));
			if($result !=null){
				return $result['service_args'];
			}
		}
		return null;
	}
	
	public function run(){
		
		$result = execute($this->getRegisteredSrvArguments());
		$mapResult = $this->localMapping($result);
		$jsonArray = array("srvset"=>array("id" =>(int)$this->srvLocalId,"led"=>$mapResult));
		echo json_encode($jsonArray);
	}
	
	public function runSelectedLed(){
		$result = ledSelected($this->selectedLed,$this->getRegisteredSrvArguments());
		if($result != null){
			$this->setConfigArgs($result);	
		}
		return true;
	}
	
	public function config($args=null){
		$result = configure($args);
		return $result;
	}
	
	public function setConfigArgs($args){
		if($this->srvLocalId !=null and $this->serialParentHdw !=null){
			$result = $this->myDB->query($this->SQL_SRV_UPDATE_CONFIG_ARGS, array('configArgs' =>$args,'hdwSerial'=>$this->serialParentHdw,'srvId'=>$this->srvLocalId));
			if($result !=null){
				return true;
			}
		}
		return false;
	}
	
	private function getLocalLedGroupId($globalLedId){
		if($this->getSrvGlobalId() !=null and $this->serialParentHdw != null){
			$result = $this->myDB->row($this->SQL_SRV_LED_MAPPING, array('hdwSerial'=>$this->serialParentHdw,'srvId'=>$this->srvLocalId,'idLed'=>$globalLedId));
			if($result !=null){
				return $result['id_led_hdw'];
			}
		}
		return null;
	}
	
	public function localMapping($array){
		$processArray = $array;
		foreach($array as $ind => $ledGroup){
			foreach ($ledGroup as $key => $value){
				if($key == 'id'){
					$processArray[$ind][$key] = (int)$this->getLocalLedGroupId($value);
				}
			}
		}
		return $processArray;
	}
	public function getServiceDescription(){
		if($this->srvLocalId!=null){
			return array("globalId" => $this->getGlobalId(),"localId" => $this->srvLocalId,"name"=>$this->getCommonName(),"description"=>$this->getDescription(),"icon"=>$this->getIcon(),"ledList"=>$this->getLedDescriptionArray());
		}
		return array("id" => $this->getGlobalId(),"name"=>$this->getCommonName(),"description"=>$this->getDescription(),"icon"=>$this->getIcon(),"ledList"=>$this->getLedDescriptionArray());
	
	}
	public function getLedDescriptionArray(){
		if($this->getSrvGlobalId() !=null){
			$result = $this->myDB->query($this->SQL_SRV_LED_DESC, array('srvId'=>$this->srvGlobalId));
			foreach($result as $r){
						$ledId = $r['id_led_service'];
						$ledName = $r['common_name'];
						$ledDesc = $r['description'];
						$ledIcon = $r['icon'];
						$ledType = $r['led_type'];
						$ledArray [] = array("id" => $ledId,"name"=>$ledName,"description"=>$ledDesc,"icon"=>$ledIcon,"type"=>$ledType);
			}
				return $ledArray;
		}
		return null;
	}
	
	public function getLedDescription(){
		if($this->getSrvGlobalId() !=null){
			$jsonArray = array("ledList"=>$this->getLedDescriptionArray());
			return $jsonArray;
		}
		return null;
	}
}


?>