<?php
/**
* The MySqlSession class implements all methods to use a
* database based session management instead of using text files.
* This has the benefit that all session data can be accessed
* at a central place. This class is supported since the PHP version 5.0.5
* because it uses the register_shutdown_function() function to ensure that
* all session values are stored before the PHP representation is destroyed.
*
* @package SessionManager
* @subpackage MySqlSession
* @version 1.0
* @date 09/04/2013
* @author Andreas Wilhelm <info@avedo.net>
* @copyright Andreas Wilhelm
* @link http://www.avedo.net
*/ 
class MySqlSession implements SessionHandlerInterface {
/**
* @var pdo $pdo The PDO object used to access the database
* @access private
*/
private $pdo = null;
/**
* Sets the user-level session storage functions which are used
* for storing and retrieving data associated with a session.
*
* @access public
* @param pdo $pdo The PDO object used to access the database
* @return void
*/
	public function __construct() {
	// Assign the pdo object, ...
		$this->pdo = new DB();
		// ... change the ini configuration, ...
		
		// Finally ensure that the session values are stored.
		
}
/**
* Is called to open a session. The method
* does nothing because we do not want to write
* into a file so we don't need to open one.
*
* @access public
* @param String $save_path The save path
* @param String $session_name The name of the session
* @return Boolean
*/
	public function open($save_path, $session_name) {
		
		return true;
	}
/**
* Is called when the reading in a session is
* completed. The method calls the garbage collector.
*
* @access public
* @return Boolean
*/
	public function close() {
		$this->gc(100);
	return true;
	}
/**
* Is called to read data from a session.
*
* @access public
* @access Integer $id The id of the current session
* @return Mixed
*/
	public function read($id) {
		// Create a query to get the session data, ...
		$select = "SELECT * FROM `sessions` WHERE `sessions`.`id` = :id";
		$result = $this->pdo->row($select,array('id'=>$id));
		if( !$result ) {
			$insert = "INSERT INTO `sessions` (id, last_updated) VALUES (:id, :time)";
			$t = time();
			$result = $this->pdo->query($insert,array("time"=>$t,"id"=>$id));
			if($result > 0) {
				return true;
			}
			return null;
		}
		return $result["value"];
	}

/**
* Writes data into a session rather
* into the session record in the database.
*
* @access public
* @access Integer $id The id of the current session
* @access String $sess_data The data of the session
* @return Boolean
*/
public function write($id, $sess_data) {
	// Validate the given data.

	if( $sess_data == null ) {
		return true;
	}
	// Setup the query to update a session, ...
	$update = "UPDATE `sessions` SET `sessions`.`last_updated` = :time, `sessions`.`value` = :data WHERE `sessions`.`id` = :id;";
	// ... prepare the statement, ...
	$t = time();
	$result = $this->pdo->query($update,array("time"=>$t,"data"=>$sess_data,"id"=>$id));
	if($result > 0) {
		return true;
	} else {
		// The session does not exists create a new one, ...
		$insert = "INSERT INTO `sessions` (id, last_updated, value) VALUES (:id, :time,:data);";
		$result = $this->pdo->query($insert,array("time"=>$t,"data"=>$sess_data,"id"=>$id));
		if($result > 0) {
			return true;
		}
	}
	return false;	
}
/**
* Ends a session and deletes it.
*
* @access public
* @access Integer $id The id of the current session
* @return Boolean
*/
public function destroy($id) {
// Setup a query to delete the current session, ...
	$delete = "DELETE FROM `sessions` WHERE `sessions`.`id` = :id";
	$result = $this->pdo->query($delete,array("id"=>$id));
	
	return $result;
}
/**
* The garbage collector deletes all sessions from the database
* that where not deleted by the session_destroy function.
* so your session table will stay clean.
*
* @access public
* @access Integer $maxlifetime The maximum session lifetime
* @return Boolean
*/
public function gc($maxlifetime) {
	// Set a period after that a session pass off.
	$maxlifetime = strtotime("-20 minutes");
	// Setup a query to delete discontinued sessions, ...
	$delete = "DELETE FROM `sessions` WHERE `sessions`.`last_updated` < :maxlifetime;";
	$result = $this->pdo->query($delete,array("maxlifetime"=>$maxlifetime));
	return $result;
}
}
?>

