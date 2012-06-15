<?php

	class CoreEmptyFile{

	public function __construct(){
	}
	
	public function getFile(){
		return "";
	}
	
	public function isFolder(){
		return false;
	}
	public function isFile(){
		return false;
	}
	public function exists(){
		return false;
	}
	public function getDirectory(){
		return "";
	}
	public function getExtension(){
		return "";
	}
	public function toURL(){
		return "";
	}
	public function toURLAppendToDirectory(){
		return "";
	}
	public function toFullURL(){
		return "";
	}
	public static function createFromURL($url){
	    return "";
	}
	public function write($toWrite){
		throw(new Exception());
	}
	public function AppendRightToDirectory($folder){
		return $this;
	}
}
?>
