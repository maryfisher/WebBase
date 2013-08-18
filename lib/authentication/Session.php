<?php

class authentication_Session{
	
	var $lifetime = 6000;
	
	public function __construct(){  
	
		session_set_save_handler(array ($this, '_open'), 
                                 array ($this, '_close'),
                                 array ($this, '_read'),
                                 array ($this, '_write'), 
                                 array ($this, '_destroy'), 
                                 array ($this, '_gc'));

        
        session_start();
        register_shutdown_function('session_write_close');
		
    }
	
	public function _open($path, $name) {        
        return true;
    }
	
	public function _close() {
            
        //Ruft den Garbage-Collector auf.
        $this->_gc();
        return true;
    }
	
	public function _read($sesID) {
		//$db = new Database();
		$db = Database::getInstance();
		
		$data="";
		$time=time();
		
		$query = "SELECT SessionData FROM sessions WHERE SessionId = '$sesID' AND Expires>$time";
        $result = $db->query($query);
		$rows=$db->rows;
		if($rows!=0){
			$row = mysql_fetch_assoc($result);
			$data = $row['SessionData'];
		}
        
    	return $data;
	}

	public function _write($sesID, $data) {
    	$db = Database::getInstance();
		
		$time = time()+$this->lifetime;
		$query = "SELECT * FROM sessions WHERE SessionId='$sesID'";
		$db->query($query);
		if($db->rows!=0){
			$query= "UPDATE sessions SET SessionData='$data', Expires=$time WHERE SessionId='$sesID'";
		}else{
			$query= "INSERT sessions (SessionId, SessionData, Expires) VALUES ('$sesID', '$data', $time)";
		}
		$result = $db->query($query);
		
        return true; //nicht result??
        
	}
	
	public function _destroy($sesID) {
		$db = Database::getInstance();
		$query = "DELETE FROM `sessions` WHERE `SessionId` ='$sesID'";

        $db->query($query);

    	return true;
	}
	
	public function _gc() {
		$db = Database::getInstance();
		
		$query = "DELETE FROM `sessions` WHERE Expires <UNIX_TIMESTAMP()";

        $db->query($query);

    	return true;
	}
}
?>