<?php
require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

class ChiconTwitterGateway{
	const CONSUMER_KEY = "Nm6mrKIUJSTA2t8ufe003M2Kd";
	const CONSUMER_SECRET = "M5U3Fwz25dNIKqf7M7W5MW5K50QWxUAAOs6Fyco4yqcIBTupuD";
	const CALL_BACK_URL = "http://127.0.0.1/chicon/addOn/o_auth.php";
	private $twtConnection;

	
	public function __construct($oauthToken, $oauthTokenSecret){
		$this->twtConnection = new TwitterOAuth(self::CONSUMER_KEY, self::CONSUMER_SECRET, $oauthToken, $oauthTokenSecret);
	}
	
	public function getPrivateMsg($twt_id=null,$order=null){
		if(!empty($twt_id)){
			if($order=="since_id"){
				$param=array("since_id"=>$twt_id);
			}else{
				$param=array("max_id"=>$twt_id);
			}
			 $this->twtConnection->get("direct_messages",$param);
		}else{
			 $this->twtConnection->get("direct_messages");
		}
		
		return $this->twtConnection->getLastBody();
		
	}
	
	public function getMatchingPrivateMsg($regexp,$twt_id=null,$order=null){
		$twt = $this->getPrivateMsg($twt_id,$order);
		$arr =null;
		foreach ($twt as $i){
			if(preg_match($regexp, $i->{'text'}, $matches)){
				$resultArr = array("id"=>$i->{'id_str'},"text"=>$i->{'text'},"matches"=>$matches);
				$arr[] = $resultArr;
			}
		}
		return $arr;
	}
	
	public static function getUserToken($call_back=null){
		$twitteroauth = new TwitterOAuth(self::CONSUMER_KEY, self::CONSUMER_SECRET);
		// Requesting authentication tokens, the parameter is the URL we will be redirected to
		$request_token = $twitteroauth->oauth('oauth/request_token', array('oauth_callback' => self::CALL_BACK_URL));
		$_SESSION['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
		$url = $twitteroauth->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
		echo " 1 - Authenticate with Tweeter: ";
		echo "<a style='color:#0252aa;text-decoration:none;cursor:pointer;' href=\"javascript:window.open('$url','mywindowtitle','width=500,height=150')\">Click Here</a>";
		
		return 1;
	
	}
	public static function isAuthenticationCompleted(){
		
		if(!isset($_SESSION['access_token'])){
			return false;
		}
		return true;
	}
	
	public static function getAccessToken(){
		if(!isset($_SESSION['access_token'])) return null;
		
		return $_SESSION['access_token'];
	}
	

	public static function finishAuthentication(){
		$request_token = [];
		 
		$request_token['oauth_token'] = $_SESSION['oauth_token'];
		$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

		if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
				echo "Error ! ";
				return false;
		}
		$connection = new TwitterOAuth(self::CONSUMER_KEY,self::CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
		$access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
		$_SESSION['access_token'] = $access_token;
		return true;
		
	}
	
	
}
?>