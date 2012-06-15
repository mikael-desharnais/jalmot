<?php
/**
 * Session manager, gives access to Session Data
 *
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */
 class CoreSessionManager{
	    /**
	     * Session content
	     * @access private
	     * @var array
	     */
	private $values;
		/**
		 * Starts the sssion and reads all values
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
		 * @param $key	 	Key to value
		 * @return 			value given the access key
		 */
	public function getValue($key){
		return $this->values[$key];
	}
		/**
		 * Returns  true if a value exists given the access key
		 * @param $key	 	Key to value
		 * @return 			 true if a value exists given the access key
		 */
	public function valueExists($key){
		return !empty($this->values[$key]);
	}
		/**
		 * Sets a value given the corresponding key
		 * @param $key	 	Key
		 * @param $value 	value
		 */
	public function setValue($key,$value){
		$_SESSION[$key]=$value;
		$this->values[$key]=$value;
	}
	public static function getCurrentSessionManager(){
	    return new SessionManager();
	}
}
?>