<?php

class ContextMenuDescriptor {
    public static function readFromXML($name,$xml){
        $classname=$xml->class."";
        $contextMenuDescriptor=new $classname();
        $contextMenuDescriptor->setClass($classname);
        $contextMenuDescriptor->setName($name);
        $contextMenuDescriptor->setConfParams(XMLParamsReader::read($xml));
        foreach($xml->elements->children() as $elementXML){
            $input_class=$elementXML->class."";
            $element=call_user_func(array($input_class,"readFromXML"),$contextMenuDescriptor,$elementXML);
            $contextMenuDescriptor->addElement($element);
        }
        return $contextMenuDescriptor;
    }
    
    
    public $confParams=array();
    public $name;
    public $class;
    public $elements=array();
    
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
	public function getName(){
	    return $this->name;
	}
	public function setName($name){
	    $this->name=$name;
	}
	public function setClass($class){
	    $this->class=$class;
	}
}
