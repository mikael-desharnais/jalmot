<?php
/**
* Used to load XML documents and eventually cache them
*/
class CoreXMLDocument{

	/**
	* Load and returns a XML document and eventually cache them
	* @return SimpleXMLElement the XML document required
	* @param File $filename File to load
	*/
	public static function parseFromFile($filename){
		if (!file_exists($filename->toURL())){
			throw new Exception('Trying to open unknown xml file '.$filename->toURL());	
		}else {
	    	return simplexml_load_file($filename->toURL());
		}
	}
}