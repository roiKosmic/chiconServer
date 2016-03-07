<?php
	require("../addOn/ChiconCSVManager.class.php");
	define("poux_data","http://ias.openhealth.fr/Portals/1/download/poux/Openhealth_poux_Regions.csv");
	define("gastro_data","http://ias.openhealth.fr/Portals/1/download/gastroenterite/Openhealth_Gastro_Regions.csv");
	define("grippe_data","http://ias.openhealth.fr/Portals/1/download/grippe/Openhealth_S-Grippal_Regions.csv");
	define("label","PERIODE");
	define("date_format","d-m-Y");
	
	function execute($args){
		parse_str($args,$arr);
		$type = $arr['type'];
		$region = $arr['region_select'];
		//echo "$type";
		switch($type){
			case "poux":
				$csvManager = new ChiconCSVManager(poux_data);
				$seuil_green = 105;
				$seuil_red = 200;
				
			break;
			case "gastro":
				$csvManager = new ChiconCSVManager(gastro_data);
				$seuil_green = 105;
				$seuil_red = 200;
			break;
			case "grippe":
				$csvManager = new ChiconCSVManager(grippe_data);
				$seuil_green = 3.5;
				$seuil_red = 350;
			break;
			default:
				return $myArray = array( 0 => array( "id" => 1, "value" => array(0)));
			
		}
		
		//On prend la valeur à j-2 on remonte jusqu'à j-4
		
		$goodValue = false;
		for($i=1;$i<5;$i++){
			$date = new DateTime();
			date_sub($date,new DateInterval("P".$i."D"));
			//echo "Looking at ".$date->format(date_format);
			$data = $csvManager->getCSVRow(label,$date->format(date_format));
			if($data[$region] !=null and $data[$region] !="NA"){
				$goodValue= true;
				//echo "Value".$data[$region];
				break;
			}
		}
		
		if($goodValue){
			$healthValue = $data[$region];
		}else{
			return $myArray = array( 0 => array( "id" => 1, "value" => array(0)));
		}
		
		if($healthValue <=$seuil_green){
			$returnValue =1;
		}else if($healthValue > $seuil_green and $healthValue <=$seuil_red){
			$returnValue=2;
		}else{
			$returnValue=3;
		}
		$myArray = array( 0 => array( "id" => 1, "value" => array($returnValue)));
		
		return $myArray;
	}
	
	function ledSelected($led,$args=null){
		return null;
	}
		
?>
