<?php
require_once "../addOn/ChiconIFTTTGateway.class.php";
function configure($args){
	$iftttGtw = new ChiconIFTTTGateway();
	$myConfig = $iftttGtw->getIftttConfigDescr("--- Add R,G,B value here ---");
	echo "<H4>Copy following JSON on your IFTTT Make channel - add values </H4>";
	echo $myConfig;
	echo "<BR>";
	return $iftttGtw->getIftttConfigString();
}



?>