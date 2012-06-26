<?php
/**
* The container for all String operation
*/
class CoreString{
	/**
	* Transforms string to upper case
	* @return String The string to upper case
	* @param String $string the string to transform
	*/
	public static function strtoupper($string){
		return strtoupper($string);
	}
	/**
	* Transforms string to lower case
	* @return String The string to lower case
	* @param String $string the string to transform
	*/
	public static function strtolower($string){
		return strtolower($string);
	}
}


?>
