<?php

class view_BaseView{
	
	protected $Paths = array();
	protected $Params = array();
	private static $instance;
	
	public static function getInstance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new view_BaseView();
		}
		return self::$instance;
	}
	
	public function addFile($file, $name) {
		//hier schauen, ob das File ein php ist, wenn ja, dann anders vorgehen
		if(!strpos($file, ".php")) {
		
		  $path = self::getLayoutPath() . $file;
				
			if ( file_exists( $path ) ) {
				$data = file_get_contents( $path );
			}
			$this->setParam($name, $data);
		}else{
		  $this->setParam($name, $file);
		}
	}
	
	public static function imgPath(){
		return self::getInstance()->Paths['baseUrl'] . self::getInstance()->Paths['imgPath'];
	}
	
	public static function baseUrl(){
		return self::getInstance()->Paths['baseUrl'];
	}
	
	public static function setBaseUrl($url){
		self::getInstance()->Paths['baseUrl'] = $url;
	}
	
	public static function downloadPath(){
		return self::getInstance()->Paths['baseUrl'] . self::getInstance()->Paths['downloadPath'];
	}
	
	public static function layoutPath(){
		return self::getInstance()->Paths['layoutPath'];
	}
	
	protected static function getLayoutPath(){
		//return APPLICATION_PATH . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR."layout" . DIRECTORY_SEPARATOR . self::$layoutPath;
		return APPLICATION_PATH . "/view/layout/" . self::getInstance()->Paths['layoutPath'];;
	}
}

?>