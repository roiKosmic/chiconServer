
<?php
require_once "../addOn/ChiconTwitterGateway.class.php";

function configure($args){
	if(isset($args['tweet'])){
		while(!ChiconTwitterGateway::isAuthenticationCompleted())
		{
			
		}
		$tweeterSession = ChiconTwitterGateway::getAccessToken();
		
		echo "<H3>Twitter auth completed with success<H3>";
		echo "<input type='hidden' name='access_token' value='".$tweeterSession['oauth_token']."'>";
		echo "<input type='hidden' name='access_token_secret' value='".$tweeterSession['oauth_token_secret']."'>";
		echo "<input type='hidden' name='auth' value='1'/>";
		echo "<H4>Please enter your matching regexp</H4>";
		echo "<input name='regexp' type='text'/>";
		echo "<H4>Please enter your matching schedule</H4>";
		echo " FROM <input name='fromHour' type='text'/> AM";
		echo " TO <input name='toHour' type='text'/> PM";
		return null;
		
	}elseif(isset($args['auth'])){
	
		return "access_token=".$args['access_token']."&access_token_secret=".$args['access_token_secret']."&fromHour=".$args['fromHour']."AM&toHour=".$args['toHour']."PM&&regexp=".$args['regexp']."&ack=0";
		
	}else{
		$t = ChiconTwitterGateway::getUserToken();
		echo "<input type='hidden' name='tweet' value='1'/>";
		
		return null;
	}
	
	
}




?>


