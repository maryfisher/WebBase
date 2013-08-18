<?php
class authentication_Cookie {
	private $cookiename;
	private $cookievalue;
	private $cookieexpiry;
	
	public function __construct (){
		$num_args = func_num_args();
		if($num_args > 0){
			$args = func_get_args();
			$this->cookiename = $args[0];
			$this->cookievalue = $args[1];
			$this->cookieexpiry = $args[2];
			$this->cookieset();
		}
	}
	
	public function cookieset (){
		try { 
				
			if ($this->cookiename != "" && $this->cookievalue != "" && $this->cookieexpiry != ""){
				
			setcookie ($this->cookiename, $this->cookievalue, time() + $this->cookieexpiry);
			} else {
				throw new exception ("Sorry, you must assign a name and expiry date for the cookie.");
			}
		} catch (exception $e){
			echo $e->getmessage();
		}
	}
	//A function to change the value of the cookie.
	public function change ($newvalue){
		$_COOKIE[$this->cookiename] = $newvalue;
	}
	//A function to retrieve the current value of the cookie.
	public function getValue (){
		return $_COOKIE[$this->cookiename];
	}
	//A function to remove the cookie.
	public function remove (){
		$this->change ("");
	}
}

class authentication_Cookie {
	private $created;
	private $userid;
	private $version;
	// our mcrypt handle
	private $td;
	// mcrypt information
	static $cypher = 'blowfish';
	static $mode = 'cfb';
	static $key = 'choose a better key';
	// cookie format information
	static $cookiename = 'USERAUTH';
	static $myversion = '1';
	// when to expire the cookie
	static $expiration = '600';
	// when to reissue the cookie
	static $warning = '300';
	static $glue = '|';
	
	public function _ _construct($userid = false) {
		$this->td = mcrypt_module_open ($cypher, '', $mode, '');
		if($userid) {
			$this->userid = $userid;
			return;
		}else {
			if(array_key_exists(self::$cookiename, $_COOKIE)) {
				$buffer = $this->_unpackage($_COOKIE[self::$cookiename]);
			}else {
				throw new AuthException(“No Cookie”);
			}
		}
	}
	public function set() {
		$cookie = $this->_package();
		set_cookie(self::$cookiename, $cookie);
	}
	
	public function validate() {
		if(!$this->version || !$this->created || !$this->userid) {
			throw new AuthException("Malformed cookie");
		}
		if ($this->version != self::$myversion) {
			throw new AuthException("Version mismatch");
		}
		if (time() - $this->created > self::$expiration) {
			throw new AuthException("Cookie expired");
		} else if ( time() - $this->created > self::$resettime) {
			$this->set();
		}
	}
	
	public function logout() {
		set_cookie(self::$cookiename, '', 0);
	}

	private function _package() {
		$parts = array(self::$myversion, time(), $this->userid);
		$cookie = implode($glue, $parts);
		return $this->_encrypt($cookie);
	}
	private function _unpackage($cookie) {
		$buffer = $this->_decrypt($cookie);
		list($this->version, $this->created, $this->userid) = explode($glue, $buffer);
		if($this->version != self::$myversion || !$this->created || !$this->userid){
			throw new exception();
		}
	}
	
	private function _encrypt($plaintext) {
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size ($td), MCRYPT_RAND);
		mcrypt_generic_init ($this->td, $this->key, $iv);
		$crypttext = mcrypt_generic ($this->td, $plaintext);
		mcrypt_generic_deinit ($this->td);
		return $iv.$crypttext;
	}

	private function _decrypt($crypttext) {
		$ivsize = mcrypt_get_iv_size($this->td);
		$iv = substr($crypttext, 0, $ivsize);
		$crypttext = substr($crypttext, $ivsize);
		mcrypt_generic_init ($this->td, $this->key, $iv);
		$plaintext = mdecrypt_generic ($this->td, $crypttext);
		mcrypt_generic_deinit ($this->td);
		return $plaintext;
	}

	private function _reissue() {
		$this->created = time();
	}
}
?>