<?php
class Database {

	private $link;
	private $host = "localhost";
	//private $host = "www.lazercombat.com";
	private $password="Talleyrand2";
	//private $password = "aWM5iy";
	private static $user="root";
	private static $database = "la000350_0005";
	//private static $database = "la000350_0003";
	
	private static $instance;
	
	//private static $host="localhost";
	private $rows = 0;
	
	private function __construct(){
		
		$this->link = @mysql_connect($this->host, self::$user, $this->password) or die (print "Die Datenbank zur Zeit leider nicht verf&uuml;gbar. Bitte versuch es zu einem sp&auml;teren Zeitpunkt erneut.");
		
		@mysql_select_db(self::$database,$this->link) or die (print("Die Datenbank zur Zeit leider nicht verf&uuml;gbar. Bitte versuch es zu einem sp&auml;teren Zeitpunkt erneut."));
	}
	
	public static function getInstance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new Database();
		}
		return self::$instance;
	}
	
	public static function defineDatabase($db = "la000350_0004") {
		if ($db != self::$database) {
			self::$user = "la000350_0003";
			self::$database = $db;
			self::$instance = null;
			self::getInstance();
		}
	}
	
	public function __destruct() {
		@mysql_close($this->link);
	}
	
	public function query($query){ 
		
		$result = mysql_query($query, $this->link) or die (print($query));
		//(print("Die Anfrage kann zur Zeit leider nicht durchgef&uuml;hrt werden."));
			
		if (preg_match("/SELECT/", $query)) {
			$this->rows = mysql_num_rows($result);
		}
		
		return $result;
		
	}
	
	public function countRows($from, $count, $where){
		$query = "SELECT COUNT($count) FROM $from WHERE $where";
		$result = $this->query($query);
		
		if($result){
			$rows = array();
			//array_push($result)
			$rows = mysql_fetch_array($result);
		}
		
		return $rows[0];
	}
	
	public function fetchArray($from, $where, $all = "*", $order = "") {
		$where = ($where != "") ? " WHERE " . $where : "";
		$order = $order != "" ? " ORDER BY " . $order : "";
		$query = "SELECT $all FROM $from $where $order";
		$result = $this->query($query);
		
		if ($result) {
			$rows = array();
			while ($row = mysql_fetch_array($result)) {
				array_push($rows, $row);
			}
		}
		
		return $rows;
	}
	
	public function fetchAssoc($from, $where, $all = "*", $order = "") {
		
		//hier noch $all anschauen, wenn es ein array ist, aufspalten
		$where = $where != "" ? " WHERE " . $where : "";
		$order = $order != "" ? " ORDER BY " . $order : "";
		$query = "SELECT $all FROM $from $where $order";
		
		$result = $this->query($query);
		
		$rows = array();
		
		while($resultassoc = mysql_fetch_assoc($result)){
			
			foreach($resultassoc as $fieldname => $fieldvalue) {
				$row[$fieldname] = $fieldvalue;
				
			}
			array_push($rows, $row);
		}
		
		$this->rows = mysql_num_rows($result);
		
		return $rows;
	}
	
	public function insertData($into, $data) {
		//hier noch strippen
		$fields = "(";
		$values = "(";
		
		$i=0;
		$length = count($data) - 1;
		
		foreach($data as $key => $value) {
			//$fields .= $this->escapeString($key);
			$fields .= $key;
			//hier noch überprüfen, ob numeric
			if (is_string($value)) {
				$value = "'" . $value . "'";
			}
			//$values .= $this->escapeString($value);
			$values .= $value;
			if($i!= $length) {
				$fields .= ",";
				$values .= ",";
			}
			$i++;
		}
		
		//hinten abschneiden ??
		$fields .= ")";
		$values .=")";
		
		$query = "INSERT INTO $into $fields VALUES $values";
		$this->query($query);
	}
	
	public function updateData($from, $where, $data){
		
		$sets = "";
		$i = 0;
		$length = count($data) - 1;
		
		foreach($data as $key => $value) {
			if (is_string($value)) {
				$value = "'" . $value . "'";
			}
			$sets .= $key . "=" . $value;
			
			if($i!= $length) {
				$sets .= ",";
			}
			$i++;
		}
		
		$query = "UPDATE $from SET $sets WHERE $where";
		$this->query($query);
	}
	
	public function deleteData($from, $where){
		$where = $where != "" ? " WHERE " . $where : "";
		$query = "DELETE FROM $from $where";
		
		$result = $this->query($query);
	}
	
	public function escapeString($string){
		
		if(!is_numeric($string)){
			//$string="'".mysql_real_escape_string($string,$this->link)."'";
			$string = mysql_real_escape_string($string, $this->link);
		}
		
		return $string;
	}
	
	public function rows(){
		return $this->rows;
	}
}
?>