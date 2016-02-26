<?php
	
	require_once('../class/DB.class.php');
	require_once('../class/ChiconHardware.class.php');
	require_once('../class/ChiconService.class.php');
	
	class ChiconIFTTTGateway{
		private $db;
		private $iftttKey = null;
		private $relatedHdw = null;
		
		public function __construct($key=null,$mn=null){
			$this->db = new DB();
			
			if($key == null and $mn == null ){
				global $SRV_INSTANCE;
				$myHdw = ChiconHardware::withSerial($this->db,$SRV_INSTANCE->getSerialParentHdw());
				if(isset($myHdw)){
					$this->iftttKey = md5(uniqid($myHdw->getMagicNumber(),true));
					$this->relatedHdw = $myHdw->getMagicNumber();
				}
			}else{
				$this->iftttKey = $key;
				$this->relatedHdw = $mn;
			}
			
		}
		
		public static function triggerFromIfTtt($entityBody){
			//$entityBody = file_get_contents('php://input');
			$json = json_decode($entityBody,true);
			if(isset($json['iftttKey']) and isset($json['mn'])){
				$instance = new self($json['iftttKey'], $json['mn']);
				$myHdw = new ChiconHardware($instance->db,$instance->relatedHdw);
				$ifttService = ChiconService::withIfTttKey($instance->db,$instance->iftttKey,$myHdw->getSerialNumber());
				if(isset($ifttService)){
					$args = $ifttService->getRegisteredSrvArguments();
					parse_str($args,$arr);
					$arr['values'] = $json['values'];
					$ifttService->setConfigArgs(http_build_query($arr));
					return true;
				}
			}
			return false;
		}
	
		function getIftttKey(){
			return $this->iftttKey;
		}
	
		function getIftttConfigDescr($valueDesc=null){
			$str = "{\"iftttKey\":\"".$this->iftttKey."\",\"mn\":\"".$this->relatedHdw."\",\"values\":[".$valueDesc."]}";
			return $str;

		}
		
		function getIftttConfigString(){
			return "iftttKey=".$this->iftttKey;
		
		}
	}

?>