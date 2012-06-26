<?php
/**
* Session manager, gives access to Session Data
* 
* 
*/
 class CoreSessionManager{
	/**
	* Session values content
	* 
	*/
	private $values;
	/**
	* Starts the ssesion and reads all values
	* 
	*/
	public function __construct(){
		global $_SESSION;
		session_start();
		foreach($_SESSION as $key=>$value){
			$this->values[$key]=$value;
		}
	}
	/**
	* Returns a value given the access key
	* 
	* @return value given the access key
	*/
	public function getValue($key){
		return $this->values[$key];
	}
	/**
	* Returns  true if a value exists given the access key
	* 
	* @return true if a value exists given the access key
	*/
	public function valueExists($key){
		return !empty($this->values[$key]);
	}
	/**
	* Sets a value given the corresponding key
	* 
	*/
	public function setValue($key,$value){
		$_SESSION[$key]=$value;
		$this->values[$key]=$value;
	}
	/**
	* Returns an instance of Session Manager
	* @return SessionManager An instance of Session Manager
	*/
	public static function getCurrentSessionManager(){
	    return new SessionManager();
	}
}


?>