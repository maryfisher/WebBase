<?php

class authentication_Login{
	
	private static $table;
	private static $instance;
	private $status = array();
	
	private __construct(){
		$status['isLoggedIn'] = false;
		$table = 'login';
	}
	
	public static function getInstance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new helper_Login();
		}
		return self::$instance;
	}
	
	public static function setTable($table){
		self::$table = $table;
	}
	
	public static function getTable(){
		return self::$table;
	}
	
	public static function userLogin($name, $pass, $isCookie = true, $sessionName = "login"){
		
		//pass encrypten
		$instance = self::getInstance();
		
		$db = Database::getInstance();
		$rows = $db->fetchArray(self::$table, "name=" . $name . "AND pass=" . $pass);
		
		if(!empty($rows)){
			$instance->$status['isLoggedIn'] = true;
			$instance->$status['isCookie'] = $isCookie;
			$instance->$status['userRights'] = self::getUserRights($name);
			
			if($isCookie){
				$cookie = new authentication_Cookie()
				$instance->$status['id'] = $cookie;
			}else{
				//self::$instance->$status['id'] = 
			
			}
			
			$answer = true;
		}else{
			$answer = false;
		}
			
		return $answer;
	}
	
	public static function userLogout(){
		$instance = self::getInstance();
		$instance->$status['isLoggedIn'] = false;
	}
	
	public static function getStatus(){
		$instance = self::getInstance();
		if(isset($instance->$status['isLoggedIn'])){
			return $instance->$status['isLoggedIn'];
		}else{
		
		}
	}
	
	public static function getUser(){
		if(isset(self::$instance)){
			$user = array('userRights' => self::$instance->$status['userRights']);
			if(self::$instance->$status['isCookie']){
				$cookie = self::$instance->$status['id'];
				$user['id'] = $cookie->getValue();
			}else{
				$session = self::$instance->$status['id'];
				$user['id'] = $_SESSION[$session];
			}
			
			return $user;
		}
		
		return null;
	}
	
	public static function addUser($name, $pass){
		//hier könnte noch abgefragt werden, ob user das right hat
		//checken, ob gutes Passwort
		if(self::getUserRights($pass)){
			//pass encrypten
			$db = Database::getInstance();
			$rows = $db->fetchArray(self::$table, "name=" . $name);
			if(empty($rows)){
				$db->insertData(self::$table, array("name" => $name, "pass" => $pass);
			}else{
				return "Username schon vergeben!";
			}
		}else{
			return "Passwort entspricht nicht den erforderlichen Kriterien!";
		}
		
		return "Anlegen des Users erfolgreich!";
	}
	
	public static function getUserRights($name = null){
		
		//hier aus der Datenbank holen, welche Rechte der User hat, was er schreiben darf und was nicht
	}
	
	public static function checkForGoodPassword($password) {
		
		//das hier mit Ajax verwenden, so dass die Sicherheit bereits beim Eintippen abgefragt wird
		
		if(strlen($password) < 8) {
			return false;
		}
		if(!preg_match("/\d/", $password)) {
			return false;
		}
		if(!preg_match("/[a-z]/i", $password)) {
			return false;
		}
		
		return true;
	}
}

?>