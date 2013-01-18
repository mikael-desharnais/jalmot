<?php

class SimpleCellML {
    
	protected $key;
	protected $instance;
	
	protected $listing;
	protected $confParams=array();
	
    public function __construct($key){
		$this->key=$key;
	}
	public function toHTML($line){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/SimpleCellML".(empty($this->instance)?"":"_".$this->instance).".phtml"));
		return ob_get_clean();
	}
	protected function getValue($line){
		if (!is_object($line)){
			Log::Error('Trying to acess to key '.$this->key.' on a non object');
		}
		$getter="get".ucfirst($this->key);
		return $line->$getter();
	}
	public static function readFromXML($xml){
	    $classname = $xml->class."";
	    $cellDescriptor=new $classname($xml->key."");
	    $cellDescriptor->setConfParams(XMLParamsReader::read($xml));
	    $instance = $xml->instance."";
	    if (!empty($instance)){
	    	$cellDescriptor->setInstance($xml->instance."");
	    }
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
	public function setInstance($instance){
		$this->instance=$instance;
	}
	public function getKey(){
		return $this->key;
	}
}
