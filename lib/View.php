<?php
class View{
	
	private static $imgPath;
	private static $cssPath;
	private static $jsPath;
	private static $downloadPath;
	private static $langPath;
	private static $layoutPath;
	private static $baseUrl;
	
	private $Helper;
	private $Scripts = array();
	private $LibScripts = array();
	private $Styles = array();
	private $LibStyles = array();
	private $Title;
	private $bodyTop = "BodyTop";
	private $bodyBottom = "BodyBottom";
	/*private $TopStructure = array();
	private $BottomStructure = array();*/
	private $Params = array();
	//private $Structure = array();
	private $ViewFile;
	
	private static $instance;
	
	private function __construct() { 
		$this->init();
	}
	
	private function init() {
		//self::$baseUrl = dirname($_SERVER['PHP_SELF']);
		//das muss hier in wonaders ausgelagert werden, wo dass man es, wenn man nicht default layout hat, overriden kann
		$this->setLayoutPath('default');
		/*$imgPath = DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "default".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR;
		$cssPath = DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR ."default" . DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR;
		$jsPath = DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."default".DIRECTORY_SEPARATOR."js".DIRECTORY_SEPARATOR;
		$downloadPath = DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."default".DIRECTORY_SEPARATOR."downloads".DIRECTORY_SEPARATOR;*/
		$langPath = "";
		//$layoutPath = "default".DIRECTORY_SEPARATOR;
		$baseUrl = "";
	}
	
	public static function getInstance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new View();
		}
		return self::$instance;
	}
	
	public function assignScript($script, $isLib = false) {
		if(!$isLib){
			array_push($this->Scripts, $script);
		}else {
			array_push($this->LibScripts, $script);
		}
	}
	
	public function assignStyle($style, $isLib = false) {
		if(!$isLib){
			array_push($this->Styles, $style);
		}else {
			array_push($this->LibStyles, $style);
		}
	}
	
	public function assignTitle($title) {
		$this->Title = "<title>".$title."</title>";
	}
	
	private function getScripts() {
		
		$scriptString = "";
		foreach($this->LibScripts as $index => $script) {
			$scriptString .= '<script type="text/javascript" charset="utf-8" src="' . self::$baseUrl . "/public/lib/js/" . $script . '.js"></script>';
		}
		foreach($this->Scripts as $index => $script) {
			$scriptString .= '<script type="text/javascript" charset="utf-8" src="' . self::$baseUrl . self::$jsPath . $script . '.js"></script>';
		}
		
		return $scriptString;
	}
	
	private function getStyles() {
		$styleString = "";
		foreach($this->LibStyles as $index => $style) {
			$styleString .= '<style type="text/css" title="currentStyle">@import "' . self::$baseUrl . "/public/lib/css/" . $style.'.css";</style>';
		}
		foreach($this->Styles as $index => $style) {
			$styleString .= '<style type="text/css" title="currentStyle">@import "' . self::$baseUrl . self::$cssPath . $style.'.css";</style>';
		}
		
		return $styleString;
	}
	
	public function getHead() {
	
		$this->addFile("Head.html", "head");
		$headString = $this->getParam('head');
		$headString .= $this->getStyles();
		$headString .= $this->getScripts();
		$headString .= $this->Title;
		
		$headString .= "</head>";
		
		return $headString;
	}
	
	public function setParam($key, $value) {
		$this->Param[$key] = helper_TextDisplay::prepareForDisplay($value);
	}
	
	public function getParam($key) {
		if(is_string($key)){
		  	$param = $this->Param[$key];	
		}elseif(is_array($key)){
			$param = $this->Param[$key[0]];
			for($i=1;$i<count($key); $i++){
				$param = $param[$key[$i]];
			}
		}
		
		if(is_string($param)){
			if(strpos($param, ".php")) {
				$path = self::getLayoutPath() . self::$langPath . $param;
				include($path);
				return "";
			}
		}
		return $param;
	}
	
	public function invokeView($filepath, $file) {
		echo $this->getHead();
		$this->getBody(true);
		//include file
		if(isset($this->ViewFile)){
			$filepath = $this->ViewFile;
		}else{
			$filepath = $filepath . self::$langPath . $file;
		}
		$path = APPLICATION_PATH . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . $filepath;
		if(file_exists($path)) {
			include($path);
		}else{
			echo $path;
			//errorfile
			
			/*$url = self::baseUrl . "/". $filepath ."/". $file ."/".;
		
			$this->setParam('url', $url);
			$this->setParam('lang', $this->getLang());
			include(APPLICATION_PATH . "/view/general/error/". self::$langPath . "nofile.php");*/
		}
    	//."index.php");
		$this->getBody(false);
	}
	
	public function invokeFile($file){
	  	$this->addFile($file, "ajax");
    	echo $this->getParam("ajax");  
  	}
	
	/*public function assignBodyTop($file) {
		$this->bodyTop = $file;
		//$this->TopStructure[$id] = $file;
	}
	
	public function assignBodyBottom($file) {
		$this->bodyBottom = $file;
		//$this->BottomStructure[$id] = $file;
	}*/
	
	public function setLayoutPath($path) {
		/*self::$layoutPath = $path . DIRECTORY_SEPARATOR;
		self::$imgPath = DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . self::$layoutPath . "images" . DIRECTORY_SEPARATOR;
		self::$jsPath = DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . self::$layoutPath . "js" . DIRECTORY_SEPARATOR;
		self::$cssPath = DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . self::$layoutPath . "css" . DIRECTORY_SEPARATOR;
		self::$downloadPath = DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . self::$layoutPath . "downloads" . DIRECTORY_SEPARATOR;*/
		self::$layoutPath = $path . "/";
		
		self::$imgPath = "/" . "public" . "/" . self::$layoutPath . "images" . "/";
		self::$jsPath = "/" . "public" . "/" . self::$layoutPath . "js" . "/";
		self::$cssPath = "/" . "public" . "/" . self::$layoutPath . "css" . "/";
		self::$downloadPath = "/" . "public" . "/" . self::$layoutPath . "downloads" . "/";
		
		self::$layoutPath = self::$layoutPath . self::$langPath;
	}
	
	public function getBody($top) {
		$file = ($top) ? $this->bodyTop : $this->bodyBottom;
		$path = self::getLayoutPath();
		$path .= $file . ".php";
		include($path);
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
		return self::$baseUrl . self::$imgPath;
	}
	
	public static function baseUrl(){
		return self::$baseUrl;
	}
	
	public static function setBaseUrl($url){
		self::$baseUrl = $url;
	}
	
	public static function downloadPath(){
		return self::$baseUrl . self::$downloadPath;
	}
	
	public function changeViewFile($filepath, $file){
		$this->ViewFile = $filepath . self::$langPath . $file;;
	}
	
	public static function layoutPath(){
		return self::$layoutPath;
	}
	
	private static function getLayoutPath(){
		//return APPLICATION_PATH . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR."layout" . DIRECTORY_SEPARATOR . self::$layoutPath;
		return APPLICATION_PATH . "/view/layout/" . self::$layoutPath;
	}
	
	/*protected function iterateParam($file, $key){
		$params = $this->getParam($key);
		if(is_array($params)){
			foreach($params as $item){
				$this->Helper = new Helper();
				$this->Helper->setParam($item);
				include(APPLICATION_PATH . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "layout" . DIRECTORY_SEPARATOR . "helper" . DIRECTORY_SEPARATOR . self::$langPath . $file);
			}
		}
	}*/
	
	protected function iterateParam($file, $key, $newkey = null){
		$params = $this->getParam($key);
		$path = APPLICATION_PATH . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "layout" . DIRECTORY_SEPARATOR . "helper" . DIRECTORY_SEPARATOR . self::$langPath;
		
		if(is_array($params)){
			$rows = $params;
		}elseif($params instanceof view_Iterator){
			$rows = $params->getParam();
		}
		
		$iterator = new view_Iterator($rows, $file);
		
		if(!is_null($newkey)){
			$this->setParam($newkey, $iterator);
			$index = $iterator->iterateParam();
			if(!is_null($index)){
				$this->Helper = $iterator;
				include($path . $iterator->getFile());
			}
		}elseif(is_array($params)){
			foreach($params as $row){
				$index2 = $iterator->iterateParam();
				$this->Helper = $iterator;
				if(!is_null($index2)){
					include($path . $file);
				}
			}
		}
		
		if($params instanceof view_Iterator){
			$index2 = $params->iterateParam();
			
			if(!is_null($index2)){
				$this->Helper = $params;
				include($path . $params->getFile());
			}
		}
	}
	
	public static function setLangPath($path){
		self::$langPath = $path != "" ? $path . DIRECTORY_SEPARATOR : "";
		self::$layoutPath = self::$layoutPath . self::$langPath;
	}
}
?>