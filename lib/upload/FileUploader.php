<?php

class upload_FileUploader{
	
	protected $mimes = array('img' => array ("image/jpeg", "image/pjpeg"));
	
	protected function imageUpload(){
		//bestätigen, dass es ein Image ist
	}
	
	public function saveFile($name, $type, $path){
		//hier den entsprechenden Typ testen
		
		if (!in_array ($_FILES[$name]['type'], $this->mimes[$type])){
			return false;
		}
		if($_FILES[$name]['size'] > 300000 || $_FILES[$name]['size'] == 0){
			return false;
			//Filesize testen
		}
		
		if (!move_uploaded_file($_FILES[$name]['tmp_name'], $path)){
			return false;
		}
		
		return true;
	}
}
?>