<?php

class IconMMML {
    
	protected $key;
	
	protected $listing;
	protected $confParams=array();
	protected $editable=true;
	
    public function __construct($key){
		$this->key=$key;
	}
	public function toHTML($line){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/MediaManager/IconMMML.phtml"));
		return ob_get_clean();
	}
	protected function getValue($line){
		$getter="get".ucfirst($this->key);
		return $line->$getter();
	}
	public static function readFromXML($xml){
	    $cellDescriptor=new self($xml->key."");
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
	protected function getId($line){
		$primary_keys=$line->getPrimaryKeys();
		$toReturn="";
		foreach($primary_keys as $name=>$value){
			$toReturn.=($toReturn==""?"":"-").$value;
		}
		return $toReturn;
	}
	protected function getParams($line){
		$primary_keys=$line->getPrimaryKeys();
		$toReturn="";
		foreach($primary_keys as $name=>$value){
			$toReturn.=($toReturn==""?"":"&")."id[".$name."]=".$value;
		}
		return $toReturn;
	}
	protected function isDirectory($line){
	    if ($line->getParentModel()->getName()=="MediaDirectory"){
	        return true;
	    }else {
	        return false;
	    }
	}
	public function setEditable($editable) {
	    $this->editable=$editable;
	}
	public function isEditable($line){
	    if (isset($line->editable)){
	    	return $line->editable;
	    }else {
	        return true;
	    }
	}
}
