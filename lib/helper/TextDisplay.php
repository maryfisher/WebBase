<?php

class helper_TextDisplay{
	
	public static function convertBrackets($data, $isDisplayed = true){	
		if(is_array($data)){
			return array_map(array('helper_TextDisplay', 'convertBrackets'), $data);
		}else{
			if(!empty($data) && is_string($data)){
				if($isDisplayed){
					$data = str_replace("[", "<", $data);
					$data = str_replace("/]", "/>", $data);
					$data = str_replace("]", ">", $data);
					//$data = str_replace('\"', '"', $data); das hier an anderer Stelle, erst beim displayen
				}else{
					$data = str_replace("<", "[", $data);
					$data = str_replace("/>", "/]", $data);
					$data = str_replace(">", "]", $data);
				}
			}
		
			return $data;
		}
	}
	
	public static function prepareForDisplay($data){
		if(is_array($data)){
			return array_map(array('helper_TextDisplay', 'prepareForDisplay'), $data);
		}else{
			if(!empty($data) && is_string($data)){
				//Umlaute??
				$data = stripslashes($data);
				$data = str_replace('\"', '"', $data);
				$data = str_replace("\'", "'", $data);
			}
		
			return $data;
		}
	}
	
	public static function stripTags(){
	
	}
}
?>