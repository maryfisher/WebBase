<?php
class FrontController{
	private $request;
	private $module;
	private $controller;
	private $action;
	private static $instance;
	private function __construct() {}
	
	public static function run() {
		self::$instance = new FrontController();
		self::$instance->init();
		self::$instance->handleRequest();
	}
	
	private static function getInstance() {
		
		return self::$instance;
	}
	
	public function init() {
		$this->globalStripSlashes();
		/*$applicationHelper = ApplicationHelper::instance();
		$applicationHelper->init();*/
	}
	
	public function handleRequest() {
		$this->request = new Request();
		$baseUrl = dirname($_SERVER['SCRIPT_NAME']) == DIRECTORY_SEPARATOR ? "" : dirname($_SERVER['SCRIPT_NAME']);
		View::setBaseUrl($baseUrl);
		$lang = isset($_GET['lang']) ? $_GET['lang'] : "";
		View::setLangPath($lang);
		
		$this->getController();
		
		if(! isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHTTPRequest"){
			$this->defaultAction();
		}else {
			
			$this->ajaxAction();
		}
	}
	
	private function getController(){
      
      	if(!$this->checkForController()){
			//hier stattdessen Errorseite anzeigen
			$this->request->setErrorRequest();
		    $this->checkForController();
		}
		  
		$controllername = $this->request->getController() . "Controller";
		
		$controller = new $controllername();
		$actionname = $this->request->getAction() . "Action";
		$controller->defaultAction();
		$controller->$actionname();
   }
   
   private function checkForController() { 
     
   		$module = $this->request->getModule();
		$controller = $this->request->getController();
		$action = $this->request->getAction() . "Action";
		
		$path = APPLICATION_PATH . DIRECTORY_SEPARATOR ."controller" . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $controller . "Controller.php";
		$class = $controller . "Controller";
		
		if (file_exists($path)) {
			require_once($path);
			if(class_exists($class) && is_callable(array($class, $action))){
			    return true;
			}
		}
      
      	return false;   
	}
	
	private function defaultAction() {
		
		$view = View::getInstance();
		
		$view->invokeView($this->request->getModule() . DIRECTORY_SEPARATOR . $this->request->getController() . DIRECTORY_SEPARATOR, $this->request->getAction() . ".php");
	}
	
	private function ajaxAction() {
		
    	$view = View::getInstance();
    	//$view->invokeFile($this->request->getController() . DIRECTORY_SEPARATOR . $this->request->getAction() . ".php");
		$view->invokeFile($this->request->getController() . "/" . $this->request->getAction() . ".php");
  	}
	
	/*public function invokeView( $target ) {
		include( "view/$target.php" );
		exit;
	}*/
		
	private function globalStripSlashes(){
		//if (get_magic_quotes_gpc() == 1){
			$_GET = array_map(array('FrontController', 'sanitizeInput'), $_GET);
			$_POST = array_map(array('FrontController', 'sanitizeInput'), $_POST);
			$_REQUEST = array_map(array('FrontController', 'sanitizeInput'), $_REQUEST);
			//$_REQUEST = array_map('FrontController::recursiveStripSlashes', $_REQUEST);
			
		//}
	}
	
	private function sanitizeInput($data){
		if (is_array($data)){
			return array_map('FrontController::sanitizeInput', $data);
		}else{
			//Rückgabe des berichtigten Wertes
			if(!empty($data)){
				$data = trim($data);
				//$data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);
				$data = utf8_decode($data);
				//echo $data;
				$data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
				$data = filter_var($data, FILTER_SANITIZE_MAGIC_QUOTES);
				
			}
			return $data;
			
		}
	}

	private function recursiveStripSlashes($data){
	//Prüfen, ob der Wert ein Array ist
		
		if (is_array($data)){
			//Rekursiver Aufruf dieser Methode 
			return array_walk($this->recursiveStripSlashes, $data);
		}else{
			//Rückgabe des berichtigten Wertes
			if(!empty($data)){
				//$data = strip_tags($data);
				//$data = htmlentities($data, ENT_QUOTES);
		  		$data = htmlspecialchars($data, ENT_QUOTES);			
				$data = trim($data);
				//$data = stripslashes($data);
				
				$db = Database::getInstance();
				$data = $db->escapeString($data);
				
			}
		
		return $data;
			
		}
	}
	
	public static function getRequestParams() {
		return self::$instance->request->getParams();
	}
}
?>