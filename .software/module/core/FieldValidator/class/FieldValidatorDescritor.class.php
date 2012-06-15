<?php

class FieldValidatorDescriptor {
    
	public $name;
	public $class;
	public $rules=array();
	
	public function __construct($class,$name){
	    $this->class=$class;
		$this->name=$name;
	}
	public function getName(){
	    return $this->name;
	}
	public function setRulesContainer($rulesContainer){
	    $this->rulesContainer=$rulesContainer;
	}
	public static function readFromXML($xml){
	    $fieldValidator=new FieldValidatorDescriptor("FieldValidatorDescriptor",$xml->name."");
	    foreach($xml->rules->children() as $ruleXML){
	    	$fieldValidator->addRule(call_user_func(array($ruleXML->class."","readFromXML"),$ruleXML));
	    }
	    return $fieldValidator;
	}
	public function addRule($rule){
	    $this->rules[]=$rule;
	}
	public function isValid(){
	    $toReturn=true;
	    foreach($this->rules as $rule){
	        $result=$rule->isValid();
	        $toReturn=$result&&$toReturn;
	    }
	    return $toReturn;
	}
	
}
