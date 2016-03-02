<?php
require_once "../addOn/ChiconIFTTTGateway.class.php";
function configure($args){
	$iftttGtw = new ChiconIFTTTGateway();
	$myConfig = $iftttGtw->getIftttConfigDescr("1 Green| 2 Orange | 3 Red");
	echo "<H4>Copy following JSON on your IFTTT Make channel - Choose value to set Green, Orange or Red when triggered </H4>";
	echo $myConfig;
	echo "<BR>";
	return $iftttGtw->getIftttConfigString();
}



?>