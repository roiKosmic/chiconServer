<?php

require("MySqlSession.class.php");
class LogMeIn {

	//table fields
	var $user_table = 'USERS'; //Users table name
	var $user_column = 'login'; //USERNAME column (value MUST be valid email)
	var $pass_column = 'password'; //PASSWORD column
	var $user_level = 'userlevel'; //(optional) userlevel column
	//encryption
	var $encrypt = false; //set to true to use md5 encryption for the password

	private $myDB;
	private $session_handler;
	//connect to database
	public function __construct($database){
		$this->myDB = $database;
	}
	//login function
	function login($table, $username, $password){
		//make sure table name is set
		if($this->user_table == ""){
			$this->user_table = $table;
		}
		//check if encryption is used
		if($this->encrypt == true){
			$password = md5($password);
		}
		//execute login via qry function that prevents MySQL injections
		$result = $this->myDB->row("SELECT * FROM ".$this->user_table." WHERE ".$this->user_column."=:username AND ".$this->pass_column." = :password" , array('username'=>$username, 'password'=>$password));
		if($result != false){
			
			$this->session_handler = new MySqlSession();
			session_set_save_handler(
				array($this->session_handler, 'open'),
				array($this->session_handler, 'close'),
				array($this->session_handler, 'read'),
				array($this->session_handler, 'write'),
				array($this->session_handler, 'destroy'),
				array($this->session_handler, 'gc')
			);
			register_shutdown_function('session_write_close');
			
			session_start();
			$_SESSION['username'] = $username;
			$_SESSION['user_id']= $result['id'];
			
			return true;
		}else{			
			return false;
		}
	}
	
	
	public function loginform($formname, $formclass, $formaction){
		echo'
		<form name="'.$formname.'" method="post" id="'.$formname.'" class="'.$formclass.'" enctype="application/x-www-form-urlencoded" action="'.$formaction.'">
		<div><label for="username">Username</label>
		<input name="username" id="username" type="text"></div>
		<div><label for="password">Password</label>
		<input name="password" id="password" type="password"></div>
		<input name="action" id="action" value="login" type="hidden">
		<div>
		<input name="submit" id="submit" value="Login" type="submit"></div>
		</form>
		';
}

//logout function
function logout(){
	session_destroy();
	return true;
}
/*****
//check if loggedin
function logincheck($logincode, $user_table, $pass_column, $user_column){
//conect to DB
$this->dbconnect();
//make sure password column and table are set
if($this->pass_column == ""){
$this->pass_column = $pass_column;
}
if($this->user_column == ""){
$this->user_column = $user_column;
}
if($this->user_table == ""){
$this->user_table = $user_table;
}
//exectue query
$result = $this->qry("SELECT * FROM ".$this->user_table." WHERE ".$this->pass_column." = '?';" , $logincode);
$rownum = mysql_num_rows($result);
//return true if logged in and false if not
if($row != "Error"){
if($rownum > 0){
return true;
}else{
return false;
}
}
}
//reset password
function passwordreset($username, $user_table, $pass_column, $user_column){
//conect to DB
$this->dbconnect();
//generate new password
$newpassword = $this->createPassword();
//make sure password column and table are set
if($this->pass_column == ""){
$this->pass_column = $pass_column;
}
if($this->user_column == ""){
$this->user_column = $user_column;
}
if($this->user_table == ""){
$this->user_table = $user_table;
}
//check if encryption is used
if($this->encrypt == true){
$newpassword_db = md5($newpassword);
}else{
$newpassword_db = $newpassword;
}
//update database with new password
$qry = "UPDATE ".$this->user_table." SET ".$this->pass_column."='".$newpassword_db."' WHERE ".$this->user_column."='".stripslashes($username)."'";
$result = mysql_query($qry) or die(mysql_error());
$to = stripslashes($username);
//some injection protection
$illegals=array("%0A","%0D","%0a","%0d","bcc:","Content-Type","BCC:","Bcc:","Cc:","CC:","TO:","To:","cc:","to:");
$to = str_replace($illegals, "", $to);
$getemail = explode("@",$to);
//send only if there is one email
if(sizeof($getemail) > 2){
return false;
}else{
//send email
$from = $_SERVER['SERVER_NAME'];
$subject = "Password Reset: ".$_SERVER['SERVER_NAME'];
$msg = "
Your new password is: ".$newpassword."
";
//now we need to set mail headers
$headers = "MIME-Version: 1.0 rn" ;
$headers .= "Content-Type: text/html; \r\n" ;
$headers .= "From: $from \r\n" ;
//now we are ready to send mail
$sent = mail($to, $subject, $msg, $headers);
if($sent){
return true;
}else{
return false;
}
}
}
//create random password with 8 alphanumerical characters
function createPassword() {
$chars = "abcdefghijkmnopqrstuvwxyz023456789";
srand((double)microtime()*1000000);
$i = 0;
$pass = '' ;
while ($i <= 7) {
$num = rand() % 33;
$tmp = substr($chars, $num, 1);
$pass = $pass . $tmp;
$i++;
}
return $pass;
}
//login form
function loginform($formname, $formclass, $formaction){
//conect to DB
$this->dbconnect();
echo'
<form name="'.$formname.'" method="post" id="'.$formname.'" class="'.$formclass.'" enctype="application/x-www-form-urlencoded" action="'.$formaction.'">
<div><label for="username">Username</label>
<input name="username" id="username" type="text"></div>
<div><label for="password">Password</label>
<input name="password" id="password" type="password"></div>
<input name="action" id="action" value="login" type="hidden">
<div>
<input name="submit" id="submit" value="Login" type="submit"></div>
</form>
';
}
//reset password form
function resetform($formname, $formclass, $formaction){
//conect to DB
$this->dbconnect();
echo'
<form name="'.$formname.'" method="post" id="'.$formname.'" class="'.$formclass.'" enctype="application/x-www-form-urlencoded" action="'.$formaction.'">
<div><label for="username">Username</label>
<input name="username" id="username" type="text"></div>
<input name="action" id="action" value="resetlogin" type="hidden">
<div>
<input name="submit" id="submit" value="Reset Password" type="submit"></div>
</form>
';
}
//function to install logon table
function cratetable($tablename){
//conect to DB
$this->dbconnect();
$qry = "CREATE TABLE IF NOT EXISTS ".$tablename." (
userid int(11) NOT NULL auto_increment,
useremail varchar(50) NOT NULL default '',
password varchar(50) NOT NULL default '',
userlevel int(11) NOT NULL default '0',
PRIMARY KEY (userid)
)";
$result = mysql_query($qry) or die(mysql_error());
return;
}

*****************/

}?>