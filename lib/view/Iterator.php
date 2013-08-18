<?php
class view_Iterator{
	protected $iterator;
	protected $rows;
	protected $file;
	
	public function __construct($rows, $file){
		$this->iterator = -1;
		$this->rows = $rows;
		$this->file = $file;
	}
	
	public function iterateParam(){
		//foreach($this->rows as $row){
			$this->iterator++;
			//echo ("iterator" . $this->iterator );
		//$this->param = $this->rows[$this->iterator];
			if(isset($this->rows[$this->iterator])){
				return $this->iterator;
				//include($path);
			}else{
				return null;
			}
		//}
	}
	
	public function getParam($key = null){
		//echo "key" . $key . "index" . $this->iterator;
		
		if(is_null($key)){
			//return $this->param;
			return $this->rows[$this->iterator];
		}else{
			//return $this->param[$key];
			//print_r($this->rows[$this->iterator][$key]);
			return $this->rows[$this->iterator][$key];
		}
	}
	
	public function getFile(){
		return $this->file;
	}
}
?>