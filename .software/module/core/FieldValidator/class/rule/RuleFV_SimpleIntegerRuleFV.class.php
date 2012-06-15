<?php

class SimpleIntegerRuleFV extends RuleFV {
    
	public static function readFromXML($xml){
	    $rule=new self($xml->class."",$xml->target."");
	    $rule->setConfParams(XMLParamsReader::read($xml));
	    return $rule;
	}
	
	public function isValid(){
	    $value=Ressource::getParameters()->getValue($this->target);
	    return preg_match('/^[0-9]*$/',$value);
	}
	
}
