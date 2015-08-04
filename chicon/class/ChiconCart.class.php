<?php
require('ChiconService.class.php');

class ChiconCart{

	private $services;
	
	public function __construct(){
		$this->services = array();
	}
	
	public static function restoreFromSession(){
		if(isset($_SESSION['CART'])){
			$instance = unserialize($_SESSION['CART']);
			
		}else{
			$instance = new ChiconCart();
		}
		return $instance;	
	
	}
	public function addService($serviceId){
		//TODO Check if service is already owned by User
		//TODO Check if service is already in the cart
		$this->services[] = $serviceId;
		$this->saveToSession();
		return true;
	}
	
	public function removeService($srvId){
		for($i=0;$i<count($this->services);$i++){
			if($this->services[$i]==$srvId){
				unset($this->services[$i]);
				$this->services = array_values($this->services);
				$this->saveToSession();
				return true;
			}
		
		}
		
		return false;
	}
	
	public function removeAll(){
		unset($this->services);
		$this->services = array();
		$this->saveToSession();
		return true;
	}
	
	public function saveToSession(){
		 $_SESSION['CART'] = serialize($this);
		 return true;
	}
	
	public function confirmOrder($db,$uid){
		$confirmed = array();
		$unconfirmed = array();
		if (count($this->services)!=0){
			foreach($this->services as $serviceId){
				$serviceItem = new ChiconService($db,$serviceId);
				if($serviceItem->assignServiceToUser($uid)){
					$confirmed[]=(int)$serviceItem->getGlobalId();
				}else{
					$unconfirmed[] =(int)$serviceItem->getGlobalId();
				}
				unset($serviceItem);
			}
			$this->removeAll();
			$returnArray = array("cart"=>array("confirmed"=>$confirmed,"unConfirmed"=>$unconfirmed));
			return $returnArray;
		}
		return $returnArray = array("cart"=>null);
	}
	
	public function countItems(){
		return count($this->services);
	
	}
	public function getDescription($db){
		if (count($this->services)!=0){
			foreach($this->services as $serviceId){
				$serviceItem = new ChiconService($db,$serviceId);
				$jsonArray [] = $serviceItem->getServiceDescription();
				unset($serviceItem);
			}
			$returnArray = array("cart"=>$jsonArray);
			return $returnArray;
		}
		return $returnArray = array("cart"=>null);
	
	}
	 public function getServices(){
		return $this->services;
	 }
	

}
?>