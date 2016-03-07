
<?php

function configure($args){
	
	if(isset($args['type']) and isset($args['region_select'])){
		return "type=".$args['type']."&region_select=".$args['region_select'];
		
	}
	echo myForm();
	return null;
	
}

function myForm(){

	$str = "<H4>Choose what your want to follow</H4>";
	
	$str .= "<input type='radio' name='type' checked value='poux'>Poux<br>";
	$str .= "<input type='radio' name='type' value='grippe'>Grippe<br>";
	$str .= "<input type='radio' name='type' value='gastro'>Gastro<br>";
	$str .= "<H4>Choose in which region (France only)</H4>";
	$str .= "<SELECT name='region_select'>";
	$str .="<option value='Loc_Reg94'>CORSE</option><option value='Loc_Reg93'>PROVENCE-ALPES-CÔTE D'AZUR</option><option value='Loc_Reg91'>LANGUEDOC-ROUSSILLON</option><option value='Loc_Reg83'>AUVERGNE</option><option value='Loc_Reg82'>RHÔNE-ALPES</option><option value='Loc_Reg74'>LIMOUSIN</option><option value='Loc_Reg73'>MIDI-PYRÉNÉES</option><option value='Loc_Reg72'>AQUITAINE</option><option value='Loc_Reg54'>POITOU-CHARENTES</option><option value='Loc_Reg53'>BRETAGNE</option><option value='Loc_Reg52'>PAYS DE LA LOIRE</option><option value='Loc_Reg43'>FRANCHE-COMTÉ</option><option value='Loc_Reg42'>ALSACE</option><option value='Loc_Reg41'>LORRAINE</option><option selected value='Loc_Reg31'>NORD-PAS-DE-CALAIS</option><option value='Loc_Reg26'>BOURGOGNE</option><option value='Loc_Reg25'>BASSE-NORMANDIE</option><option value='Loc_Reg24'>CENTRE</option><option value='Loc_Reg23'>HAUTE-NORMANDIE</option><option value='Loc_Reg22'>PICARDIE</option><option value='Loc_Reg21'>CHAMPAGNE-ARDENNE</option><option value='Loc_Reg11' >ÎLE-DE-FRANCE</option>";
	$str .="</SELECT>";
	return $str;
	
}



?>


