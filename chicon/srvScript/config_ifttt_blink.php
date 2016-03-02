<?php
require_once "../addOn/ChiconIFTTTGateway.class.php";
function configure($args){
	$iftttGtw = new ChiconIFTTTGateway();
	$myConfig = $iftttGtw->getIftttConfigDescr("1");
	echo "<H4>Copy following JSON on your IFTTT Make channel - Binary LED will blink (1s interval) when triggered </H4>";
	echo $myConfig;
	echo "<BR>";
	return $iftttGtw->getIftttConfigString();
}



?>