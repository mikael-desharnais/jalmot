<?php

abstract class TesterFV {

    public $class;
    public $confParams;
    public $rules=array();
    
    public function __construct($class){
        $this->class=$class;
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
	public function addRule($rule){
		$this->rules[]=$rule;
	}
	public static function readFromXML($xml){
	    $classname=$xml->class."";
	    $tester=new $classname($classname);
	    $tester->setConfParams(XMLParamsReader::read($xml));
	    foreach($xml->rules->children() as $ruleXML){
	        $tester->addRule(call_user_func(array($ruleXML->class."","readFromXML"),$ruleXML));
	    }
	    return $tester;
	}
	public abstract function corresponds($element);	
    public function isValid(){
        $toReturn=true;
        foreach($this->rules as $rule){
            $result=$rule->isValid();
            $toReturn=$result&&$toReturn;
        }
        return $toReturn;
    }
}
