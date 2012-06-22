<?php
/**
* Unknown File Class implementation
* To be used when a method has to return a File object and can't find one
*/
	class CoreEmptyFile{
	/**
	* Does nothing
	*/
	public function __construct(){
	}
	/**
	* returns an empty string
	* @return string empty string
	*/
	public function getFile(){
		return "";
	}
	/**
	* returns false
	* @return boolean false
	*/
	public function isFolder(){
		return false;
	}
	/**
	* returns false
	* @return boolean false
	*/
	public function isFile(){
		return false;
	}
	/**
	* returns false
	* @return boolean false
	*/
	public function exists(){
		return false;
	}
	/**
	* returns an empty string
	* @return string empty string
	*/
	public function getDirectory(){
		return "";
	}
	/**
	* returns an empty string
	* @return string empty string
	*/
	public function getExtension(){
		return "";
	}
	/**
	* returns an empty string
	* @return string empty string
	*/
	public function toURL(){
		return "";
	}
	/**
	* returns an empty string
	* @return string empty string
	*/
	public function toURLAppendToDirectory(){
		return "";
	}
	/**
	* returns an empty string
	* @return string empty string
	*/
	public function toFullURL(){
		return "";
	}
	/**
	* Throws an exception
	* @throws Exception
	*/
	public function write($toWrite){
		throw(new Exception());
	}
}


?>
