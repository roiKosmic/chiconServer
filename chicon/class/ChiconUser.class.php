<?php
class ChiconUser{
	
	private $myDB;
	private $userId;
	private $userName;
	private $SQL_GET_USER_HARDWARES = "SELECT common_name,serial_hdw,firmware_hdw,magicNumber_hdw FROM users_hdw_list uhdwl, hdw_list hdwl WHERE id_user = :uid AND hdwl.id = uhdwl.id_hdw";
	private $SQL_LOGIN = "SELECT id,password FROM users Where login = :login";
	private $SQL_CREATE_USER = "INSERT INTO `users`(`id`, `login`, `password`,`firstname`,`familyName`,`creationDate`,`confirmed`,`registerHash`) VALUES ('',:login,:password,:firstName,:familyName,NOW(),FALSE,:hash)";
	private $SQL_CHECK_USER_LOGIN = "SELECT * from users where login=:login";
	private $SQL_IS_USER_HARDWARE = "SELECT id FROM users_hdw_list WHERE id_user=:uid AND serial_hdw=:sHdw";
	private $SQL_CONFIRMED_REGISTRATION = "SELECT id FROM users where registerHash=:hash";
	private $SQL_UPDATE_REGISTRATION = "UPDATE `users`  SET `confirmed` = TRUE WHERE id=:uid";
	private $SQL_SELECT_HDW_TYPE = "SELECT model FROM hdw_list WHERE id=:hdwType";
	private $SQL_SELECT_KNOWN_HDW = "SELECT serial FROM known_hdw WHERE serial LIKE :model ORDER BY serial DESC";
	private $SQL_INSERT_NEW_SERIAL = "INSERT INTO known_hdw (`id`, `serial`,`insertionDate`,`user`) VALUES ('',:nSerial,NOW(),:user)";
	
	public function __construct($database){
		$this->myDB = $database;
		$this->userId='';
		$this->username='';
	}
	
	public function getServices(){
		if($this->isLoggedIn()){
			require('../class/ChiconServiceFabric.class.php');
			$serviceFabric = new ChiconServiceFabric($this->myDB);
			return $serviceFabric->getUserServices($this->userId);
		}
		
		return null;
	}
	public function isLoginAvailable($login){
		$result = $this->myDB->query($this->SQL_CHECK_USER_LOGIN, array('login'=>$login));
		if($result !=null){
			return false;
		}
		return true;
	}
	public function requestSerial($hdwType){
		$result = $this->myDB->row($this->SQL_SELECT_HDW_TYPE,array('hdwType'=>$hdwType));
		if($result != null){
			$model = $result['model'];
			$like = $model."%";
			$result = $this->myDB->row($this->SQL_SELECT_KNOWN_HDW,array('model'=>$like));
			if($result==null){
				$newSerial=sprintf("%09d", 1);
				$newSerial=$model.$newSerial;
			}else{
				$newSerial = ++$result['serial'];	
			}
			
			$result = $this->myDB->query($this->SQL_INSERT_NEW_SERIAL,array('nSerial'=>$newSerial,'user'=>$this->username));
			return $newSerial;
		}
		return null;
	}
	public static function addUser($db,$login,$password,$firstName,$familyName){
		$instance = new self($db);
		$hash = $instance->generateRegisterHash($login);
		$password = password_hash($password,PASSWORD_DEFAULT);
		if($instance->isLoginAvailable($login)){
			$result = $instance->myDB->query($instance->SQL_CREATE_USER, array('login'=>$login,'password'=>$password,'familyName'=>$familyName,'firstName'=>$firstName,'hash'=>$hash));
			if($result!=0){
				$instance->userId=$instance->myDB->lastInsertId();
				$instance->username=$login;
				require("../misc/registrationMail.php");
				require("../misc/mailFunc.php");
				sendRegistrationMail($login,$hash);
				return $instance;
			}
		
			
		}
		
		return null;
		
	}
	
	
	
	public static function confirmUserRegistration($db,$hash){
		$instance = new self($db);
		$result = $instance->myDB->row($instance->SQL_CONFIRMED_REGISTRATION,array('hash'=>$hash));
		if($result != null){
			$uid = $result['id'];
			$result = $instance->myDB->query($instance->SQL_UPDATE_REGISTRATION,array('uid'=>$uid));
			return true;
		}
		return false;
	}
	private function generateRegisterHash($login){
		return md5(urlencode($login).uniqid());
	}
	public function getDetails(){
		if($this->isLoggedIn()){
			$details = array("username"=> $_SESSION['username']);
			return $details;
		
		}
		return null;
	}
	
	public function getUsername(){
		return $this->username;
	}
	
	public function getUserId(){
		return $this->userId;
	
	}
	//TODO redesign like services with a fabric
	public function getHardwares(){
		if($this->isLoggedIn()){
			$result = $this->myDB->query($this->SQL_GET_USER_HARDWARES, array('uid'=>$this->userId));
				if($result != null){
					foreach($result as $r){
						$hdwName = $r['common_name'];
						$hdwSerial = $r['serial_hdw'];
						$hdwFirmware = $r['firmware_hdw'];
						$hdwMagicNumber = $r['magicNumber_hdw'];
						$srvArray [] = array("model" => $hdwName,"serial"=>$hdwSerial,"firmware"=>$hdwFirmware,"mn"=>$hdwMagicNumber);
					}
					$jsonArray = array("hdwList"=>$srvArray);
					return $jsonArray;
				}
		}
		
		return null;
	}

	public function isUserHardware($sHdw){
		if($this->isLoggedIn()){
			$result = $this->myDB->query($this->SQL_IS_USER_HARDWARE, array('uid'=>$this->userId,'sHdw'=>$sHdw));
				if($result != null){
					return true;
				}
		}
		return false;
		
	}
	
	public function login($username,$password){
		$result = $this->myDB->row($this->SQL_LOGIN, array('login'=>$username));
		
		if($result != null){
			if(password_verify($password,$result['password'])){
				session_start();
				$_SESSION['username'] = $username;
				$_SESSION['user_id']= $result['id'];
				$this->userId = $result['id'];
				$this->userName = $username;
				return true;
			}else{
				return false;
			
			}
			return false;
		}
		return false;
	}
	
	public function logout(){
		session_unset();
		session_destroy();
		return true;
	}
	public function isLoggedIn(){
		if(!isset($_SESSION)){
			session_start();
		}
		if(isset($_SESSION['username']) and isset($_SESSION['user_id'])){
			if($this->userId==''){
				$this->userId = $_SESSION['user_id'];
			}
			if($this->username ==''){
				$this->username = $_SESSION['username'];
			}
			return true;
		}
		return false;
	
	}
	

}

?>
