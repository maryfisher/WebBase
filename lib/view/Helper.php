<?php

class view_Helper{
	protected $param;
	
	public function setParam($param){
		$this->param = $param;
	}
	
	public function getParam($key){
		if(is_array($this->param)){
			return $this->param[$key];
		}
		
		return $this->param;
	}
}

?>