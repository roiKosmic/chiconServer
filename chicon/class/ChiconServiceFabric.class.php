<?php
require('../class/ChiconService.class.php');
class ChiconServiceFabric{
	
	private $myDB;
	private $servicesArray = array(); 
	
	private $SQL_GET_ALL_SERVICES = "SELECT srvGlobalId FROM service_list";
	private $SQL_GET_USER_SERVICES = "SELECT srvGlobalId,common_name,icon,description FROM service_list,users_service_list WHERE srvGlobalId = id_srv AND id_user =:uid";
	private $SQL_GET_HDW_SERVICES = "SELECT srvGlobalId,srvLocalId,common_name,icon,description FROM service_list,users_hdw_service_configuration WHERE srvGlobalId = id_service AND serial_hdw =:serial";
	
	public function __construct($database){
		$this->myDB = $database;
		
	}
	
	public static function createFabricFromCart($cart,$db){
		$instance = new self($db);
		$instance->serviceArray = $cart->getServices;
		
	}
	
	
	
	public function getUserServices($uid){
		$result = $this->myDB->query($this->SQL_GET_USER_SERVICES, array('uid'=>$uid));
				if($result != null){
					foreach($result as $r){
						$this->servicesArray[] = new ChiconService($this->myDB,$r['srvGlobalId']);
					}
					foreach($this->servicesArray as $service){
						$jsonArray [] = $service->getServiceDescription();
					}
					$returnArray = array("srvList"=>$jsonArray);
					return $returnArray;
				}
		return null;
	}
	
	public function getHdwServices($sHdw){
		$result = $this->myDB->query($this->SQL_GET_HDW_SERVICES, array('serial'=>$sHdw));
				if($result != null){
					foreach($result as $r){
						$this->servicesArray[] = new ChiconService($this->myDB,$r['srvGlobalId'],$r['srvLocalId']);
					}
					foreach($this->servicesArray as $service){
						$jsonArray [] = $service->getServiceDescription();
					}
					$returnArray = array("srvList"=>$jsonArray);
					return $returnArray;
				}
		return null;
	
	}
	
	public function getAllServices(){
		$result = $this->myDB->query($this->SQL_GET_ALL_SERVICES);
			foreach($result as $r){
				$this->servicesArray[] = new ChiconService($this->myDB,$r['srvGlobalId']);
			}	
		
		foreach($this->servicesArray as $service){
			$jsonArray [] = $service->getServiceDescription();
		
		}
			$returnArray = array("srvList"=>$jsonArray);
		return $returnArray;
	}

}
?>