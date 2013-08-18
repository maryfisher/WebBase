<?php 
function __autoload($classname){
	Loader::autoload($classname);
}

class Loader{
	
	public static function autoload($classname){
		/*$names = explode("_", $classname);
		$require = implode("/", $names);*/
		$require = str_replace('_', DIRECTORY_SEPARATOR, $classname);
		$require .=".php";
		//echo $require;
		require_once(LIB_PATH . $require);
	}
}
?>