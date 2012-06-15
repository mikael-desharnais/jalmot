<?php

abstract class FieldME {
    
	protected $key;
	protected $title;
	protected $model_editor;
	protected $confParams=array();
	protected $class;
	
	
	protected abstract function getUsefullData($element);
	protected abstract function getValue($element);
	public abstract function fetchElementsToSave($dataFetched);
	
    public function __construct($class,$model_editor,$key,$title){
        $this->model_editor=$model_editor;
		$this->key=$key;
		$this->title=$title;
		$this->class=$class;
	}
	public function toHTML($dataFetched){
	    $element=$this->getUsefullData($dataFetched);
		ob_start();
		include(Ressource::getCurrentTemplate()->getFile(new File("html/module/ModelEditor",$this->class.".phtml",false))->toURL());
		return ob_get_clean();
	}
	
	
	public function getName(){
		return $this->key;
	}
	public static function readFromXML($model_editor,$xml){
	    $classname=$xml->class."";
	    $toReturn=new $classname($classname,$model_editor,$xml->key."",$xml->title."");
	    $toReturn->setConfParams(XMLParamsReader::read($xml));
		return $toReturn;
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
}
