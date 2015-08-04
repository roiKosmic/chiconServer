<?php


function configure($args){
	
	if(isset($args['periodValue']) and isset($args['eachValue'])){
		return "periodValue=".$args['periodValue']."&eachValue=".$args['eachValue']."&lastCheck=".time();
	}
	echo myForm();
	return null;
	
}

function myForm(){
		$str  ="<H4>Enter Reminder period</H4>";
		$str .= "Remind me from now every:";
		$str .= "<input name='eachValue' type='text'/>";
		$str .= "<SELECT name='periodValue'>";
		$str .= "<OPTION value='day'>Day</OPTION>";
		$str .= "<OPTION value='week'>Week</OPTION>";
		$str .= "<OPTION value='month'>Month</OPTION>";
		$str .="</SELECT>";
		
		return $str;	
	
}
?>