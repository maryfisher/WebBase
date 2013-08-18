<?php
class database_DatabaseTable{
	
	protected $table;
	protected $Param = array();
	protected $db;
	
	public function __construct(){
		$this->db = Database::getInstance();
	}
	
	protected function fetchData($handler, $where, $all, $order){
		
		$rows = $this->db->fetchArray($this->table, $where, $all, $order);
		$comments = "";
		
		foreach($rows as $index => $row) {
			
			$comments .= $this->$handler($row);
		}
		
		return $comments;
	}
	
	protected function setParam($key, $value) {
		$this->Param[$key] = $value;
	}
	
	protected function getParam($key) {
		$param = $this->Param[$key];
		return $param;
	}
	
	public function insertData($data) {
		$this->db->insertData($this->table, $data);
	}
	
	public function deleteData($where){
		$this->db->deleteData($this->table, $where);
	}
	
	public function updateData($data, $where){
		$this->db->updateData($this->table, $where, $data);
	}
	
	public function fetchArray($where = "", $all = "*", $order = ""){
		$rows = $this->db->fetchArray($this->table, $where, $all, $order);
		
		return $rows;
	}
	
	public function fetchAssoc($where = "", $all = "*", $order = ""){
		$rows = $this->db->fetchAssoc($this->table, $where, $all, $order);
		
		return $rows;
	}
}
?>