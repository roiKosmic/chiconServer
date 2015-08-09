<?php
require "../addOn/ChiconTwitterGateway.class.php";
session_start();
$r = ChiconTwitterGateway::finishAuthentication();
echo "<HTML><BODY><SCRIPT>window.opener.document.getElementById('subCfg').click();window.close();</SCRIPT></BODY></HTML>";



?>
