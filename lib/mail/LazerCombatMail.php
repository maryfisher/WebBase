<?php
class mail_LazerCombatMail extends mail_Mail{
	
	protected $email = array("office@lazercombat.com");
	protected $from = "LazerCombat";
	
	public function __construct() {
	
	}
	
	protected function getSignature(){
		$footer = "<p>mfg,<br/>Dein LazerCombat Team</p>
  			<p>(Dies ist eine automatisch generierte Mail. Bitte nicht darauf antworten.)</p>" . parent::getSiganture();
			
		return $footer;
	}
}
?>