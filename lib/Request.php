<?php
class Request {
	private $request;
	private $Param;
	
	public function __construct() {
		$this->getBaseUrl();
		$this->handleRequest();
	}
	
	private function getBaseUrl(){
		
	}
	
	private function handleRequest() {
		$this->setDefaultRequest();
		
		$url = substr($_SERVER['REQUEST_URI'], 1);
		$urlParts = explode('/', $url);
		
		$baseUrl = dirname($_SERVER['SCRIPT_NAME']);
		$queryStr = $_SERVER['QUERY_STRING'] != "" ? "?" . $_SERVER['QUERY_STRING'] : "";
		
		if(in_array($queryStr, $urlParts)){
			array_pop($urlParts);
		}
		
		$baseUrlParts = explode('/', $baseUrl);
		array_shift($baseUrlParts);
		//echo count($baseUrlParts);
		
		if ($urlParts[count($urlParts) - 1] == '')  {
			array_pop($urlParts);
		}
		
		for($i=0;$i<count($baseUrlParts);$i++){
			//print("parts" . $urlParts[$i] . "baseurl:" . $baseUrlParts[$i]);
			if($urlParts[0] == $baseUrlParts[$i]){
				array_shift($urlParts);
			}
		}
		
		for ($i = 0; $i < 3; $i++) {
			if (isset($urlParts[0])) {
				$this->request[$i] = $urlParts[0];
				array_shift($urlParts);
			}
		}
		
		if (count($urlParts) > 0) {
			$this->Param = $urlParts;
		}
	}
	
	public function getRequest($key) {
		return $this->request[$this->order[$key]];
	}
	
	public function getParams(){
		return $this->Param;
	}
	
	public function getAction() {
		return $this->request[2];
	}
	
	public function getController() {
		return $this->request[1];
	}
	
	public function getModule() {
		return $this->request[0];
	}
	
	public function setDefaultRequest(){
      $this->request = array("index", "index", "index");
  	}
	
	public function setErrorRequest(){
      $this->request = array("error", "index", "index");
  	}
}
?>