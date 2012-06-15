<?php

abstract class RuleFV {
    
	public $target;
	public $class;
	public $confParams;
	
	public function __construct($class,$target){
	    $this->class=$class;
		$this->target=$target;
	}
	
	public function getConfParams(){
	    return $this->confParams;
	}
	public function setConfParams($confParams){
	    $this->confParams=$confParams;
	}
	public function getConfParam($key){
	    if (array_key_exists($key,$this->confParams)){
	    	return $this->confParams[$key];
	    }else {
	        return "";
	    }
	}
	
	public static function readFromXML($xml){
	    $classname=$xml->class."";
	    $rule=new $classname($classname,$xml->target."");
	    $rule->setConfParams(XMLParamsReader::read($xml));
	    return $rule;
	}
	public abstract function isValid();
}
