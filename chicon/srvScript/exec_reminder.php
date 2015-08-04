<?php

	
	function execute($args){
		parse_str($args,$arr);
		$periodValue = $arr['periodValue'];
		$lastCheck = $arr['lastCheck'];
		$eachValue = $arr['eachValue'];
		$days=abs((time()-$lastCheck)/(60*60*24));
		switch ($periodValue){
			case 'day':		
				if($days > $eachValue){
					$returnValue=1;
					if($days > 2){
						$returnValue=2;
					}
					if($days > 3){
						$returnValue=3;
					}
				}else{
					$returnValue=0;
				}
				break;
			case 'week':
				$week = $days/7;
				if($week > $eachValue){
					$returnValue=1;
					if($week > ($eachValue +0.5)){
						$returnValue=2;
					}
					if($week > ($eachValue +1)){
						$returnValue=3;
					}
				}else{
					$returnValue=0;
				}
				break;
			case 'month':
				$month = $days/30;
				if($month > $eachValue){
					$returnValue=1;
					if($month > ($eachValue +0.25)){
						$returnValue=2;
					}
					if($month > ($eachValue +0.5)){
						$returnValue=3;
					}
				}else{
					$returnValue=0;
				}
				break;
			
		}
		$myArray = array( 0 => array( "id" => 1, "value" => array($returnValue)));
		return $myArray;
	}
	
	function ledSelected($led,$args=null){
		if($args!=null){
			parse_str($args,$arr);
			return "periodValue=".$arr['periodValue']."&eachValue=".$arr['eachValue']."&lastCheck=".time();
		}
		return null;
	}
		
?>
