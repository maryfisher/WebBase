<?php
class mail_Mail {
	
	protected $from;
	protected $emails;
	protected $title;
	
	public function __construct() {
	
	}
	
	public function sendSimpleMail($param){
		foreach($param as $key => $element) {
			if($key != "send"){
				$element = stripslashes($element);
				$maildaten .= "$key: $element\n";
			}
		}
		
		$mailer_datum = date("Y-m-d H:i:s");
		
		$mailinhalt = $this->from . " " . $this->title . " \n
		________________________\n
		$maildaten \n
		Zeit: $mailer_datum \n";
		
		for($i = 0; $i < count($this->emails); $i++){
  		    	mail($this->emails[$i], $this->from . " " . $this->title, $mailinhalt, "");
		}
	}
	
	public function sendMail($title, $emailtext, $emails){
		
		$header = $this->assignHeader();
		
		if(is_array($emails)){
			//hier noch auf 30 reduzieren??
			for($i = 0; $i < count($emails); $i++){
  		    		$header .= $emails[$i].", ";
  			}
			
  			
		}else{
			$header.= $emails;
		}
		
		for($i = 1; $i < count($this->emails); $i++){
	    		$header.= $this->emails[$i].", ";
  		}

		$header.="\r\n";

		$text = "<html><head><title>" . $title . "</title></head><body>" . $emailtext . $this->getSignature();
		
		mail($this->emails[0], $title, $text, $header);
	}
	
	protected function assignHeader(){
		$header = 'MIME-Version: 1.0'."\r\n";
		$header .= 'Content-type: text/html;charset=iso-8859-1'."\r\n";
		$header .= 'From: '.$this->from.' <'.$this->email.'>'."\r\n";
		$header .= 'Bcc:';
		
		return $header;
	}
	
	protected function getSignature(){
		return "</body></html>";
	}

	public function setTitle($title){
		$this->title = $title;
	}
}
?>