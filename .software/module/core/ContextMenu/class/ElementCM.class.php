<?php

abstract class ElementCM {
    public static function readFromXML($parentDescriptor,$xml){
        $classname=$xml->class."";
        $contextMenuElement=new $classname();
        $contextMenuElement->setClass($classname);
        $contextMenuElement->setTitle($xml->title."");
        $contextMenuElement->setConfParams(XMLParamsReader::read($xml));
        return $contextMenuElement;
    }
    
    
    public $confParams=array();
    public $title;
    public $class;
    
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
	public function addElement($element){
	    $this->elements[]=$element;
	}
	public function getTitle(){
	    return $this->title;
	}
	public function setTitle($title){
	    $this->title=$title;
	}
	public function setClass($class){
	    $this->class=$class;
	}
}
