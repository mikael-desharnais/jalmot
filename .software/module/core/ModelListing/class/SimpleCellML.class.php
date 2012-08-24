<?php

class SimpleCellML {
    
	protected $key;
	
	protected $listing;
	protected $confParams=array();
	
    public function __construct($key){
		$this->key=$key;
	}
	public function toHTML($line){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/SimpleCellML.phtml"));
		return ob_get_clean();
	}
	protected function getValue($line){
		$getter="get".ucfirst($this->key);
		return $line->$getter();
	}
	public static function readFromXML($xml){
	    $classname = $xml->class."";
	    $cellDescriptor=new $classname($xml->key."");
	    $cellDescriptor->setConfParams(XMLParamsReader::read($xml));
		return $cellDescriptor;
	}
	public function getListing(){
	    return $this->listing;
	}
	public function setListing($listing){
	    $this->listing=$listing;
	}
	public function getConfParams(){
	    return $this->confParams;
	}
	public function setConfParams($confParams){
	    $this->confParams=$confParams;
	}
	public function getConfParam($key){
	    return $this->confParams[$key];
	}
}
