<?php 

class view_Header{
	protected $Scripts = array();
	protected $Styles = array();
	protected $Title;
	
	public function assignScript($script) {
		array_push($this->Scripts, $script);
	}
	
	public function assignStyle($style) {
		array_push($this->Styles, $style);
	}
	
	public function assignTitle($title) {
		$this->Title = "<title>".$title."</title>";
	}
	
	private function getScripts() {
		
		$scriptString = "";
		foreach($this->Scripts as $index => $script) {
			$scriptString .= '<script type="text/javascript" charset="utf-8" src="' . View::$baseUrl . View::$jsPath . $script . '.js"></script>';
		}
		
		return $scriptString;
	}
	
	private function getStyles() {
		$styleString = "";
		foreach($this->Styles as $index => $style) {
			$styleString .= '<style type="text/css" title="currentStyle">@import "' . View::$baseUrl . View::$cssPath . $style.'.css";</style>';
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
}
?>