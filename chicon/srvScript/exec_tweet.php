<?php
	require "../addon/ChiconTwitterGateway.class.php";
	
	function execute($args){
		parse_str($args,$arr);
		$accessToken = $arr['access_token'];
		$accessTokenSecret = $arr['access_token_secret'];
		$fromHour = $arr['fromHour'];
		$toHour = $arr['toHour'];
		if(isset($arr['ack'])){
			$ack = $arr['ack'];
		}else{
			$ack = 0;
		}
		if(isset($arr['order'])){
			$order = $arr['order'];
		}else{
			$order=null;
		}
		if(isset($arr['lastTwtId'])){
			$lastTwtId = (string) $arr['lastTwtId'];
		}else{
			$lastTwtId = null;
		}
		$returnValue = 0;
		$pattern = $arr['regexp'];
		$twtAccess = new ChiconTwitterGateway($accessToken,$accessTokenSecret);
		
		
		$return = $twtAccess->getMatchingPrivateMsg($pattern,$lastTwtId,$order);
		//var_dump($return);
		if(!empty($return) and !empty($return[0]["id"])){
				
				//New matching tweet found at id
				$lastTwtId =  $return[0]["id"];
				$order = "max_id";
				//preg_match('/(\d)$/', $lastTwtId, $match);
				//$d = $match[1]-1;
				//$lastTwtId = substr($lastTwtId, 0, -1).$d;
				
				
				if($ack==1){
					//Needed because float to string conversion is hard to manage
					//preg_match('/(\d)$/', $lastTwtId, $match);
					//$d = $match[1]+1;
					//$lastTwtId = substr($lastTwtId, 0, -1).$d;
					$order="since_id";
					
				}else{
					//Check if the give tweet is matching the hour
					$twHour = $return[0]["matches"][2];
					if(strtotime($twHour) > strtotime($fromHour) and strtotime($twHour) < strtotime($toHour) and $ack==0){
						$returnValue = 1000;
					}
				}
			
		}
		$new_args = "access_token=".$accessToken."&access_token_secret=".$accessTokenSecret."&fromHour=".$fromHour."&toHour=".$toHour."&regexp=".$pattern;
		$new_args.="&ack=0&lastTwtId=".$lastTwtId."&order=".$order;
			
	
		global $SRV_INSTANCE;
		$SRV_INSTANCE->setConfigArgs($new_args);
		//echo $new_args;
		$myArray = array( 0 => array( "id" => 1, "value" => array($returnValue)));
		
		return $myArray;
	}
	
	function ledSelected($led,$args=null){
		if($args!=null){
			parse_str($args,$arr);
			$accessToken = $arr['access_token'];
			$accessTokenSecret = $arr['access_token_secret'];
			$fromHour = $arr['fromHour'];
			$toHour = $arr['toHour'];
			$lastTwtId = $arr['lastTwtId'];
			$new_args = "access_token=".$accessToken."&access_token_secret=".$accessTokenSecret."&fromHour=".$fromHour."&toHour=".$toHour."&regexp=".$pattern."&ack=1&lastTwtId=".$lastTwtId;
			return $new_args;
		}
		return null;
	}
		
?>
