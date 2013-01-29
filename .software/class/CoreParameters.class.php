<?php
/**
 * Manages get and set parameters
 *
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */
 class CoreParameters{
	    /**
	     * Array of all parameters
	     * @access private
	     * @var array
	     */
	private $values=array();
	
	
	/**
	 * reads all values, POST overrides GET values
	 */
	public function __construct(){
		global $_GET,$_POST;
		foreach($_GET as $key=>$value){
			$this->values[$key]=$value;
		}
		foreach($_POST as $key=>$value){
			$this->values[$key]=$value;
		}
	}
		/**
		 * Reads a parameter given the key
		 * @param $key	 	key to parameter
		 * @return a parameter given the key
		 */
	public function getValue($key){
		if (array_key_exists($key,$this->values)){
			return $this->values[$key];
		}
	}
		/**
		 * Returns true if the  parameter exists given the key
		 * @param $key	 	key to parameter
		 * @return  true if the  parameter exists given the key
		 */
	public function valueExists($key){
		return isset($this->values[$key]);
	}
	/**
	 * Returns the current Parameters
	 * @return The current Parameters
	 */
	public static function getCurrentParameters(){
		return new Parameters();
	}
}
?>
