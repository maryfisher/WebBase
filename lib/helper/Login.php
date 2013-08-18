<?php

class authentication_Login{
	
	private static $table;
	private $status = array();
	private static $instance;
	
	private __construct(){
		$status['isLoggedIn'] = false;
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
	
	public static function login($isCookie = true){
		self::$instance->$status['isLoggedIn'] = true;
		self::$instance->$status['isCookie'] = $isCookie;
		
		if($isCookie){
			//self::$instance->$status['id'] = 
		
		}else{
			//self::$instance->$status['id'] = 
		
		}
	}
	
	public static function logout(){
		self::$instance->$status['isLoggedIn'] = false;
	}
	
	public static function getStatus(){
		return self::$instance->$status['isLoggedIn'];
	}
	
	public static function getUser(){
		if(self::$instance->$status['isCookie']){
			$cookie = self::$instance->$status['id'];
			return 
		}
		return null;
	}
}

?>