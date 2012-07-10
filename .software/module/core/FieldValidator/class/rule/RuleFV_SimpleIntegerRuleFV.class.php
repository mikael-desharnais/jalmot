<?php
/**
* Rule that checks if a Field contains an integer
*/
class SimpleIntegerRuleFV extends RuleFV {
	/**
	* Returns an instance of RuleFV as described in the SimpleXMLElement given in parameter
	* @return RuleFV an instance of RuleFV as described in the SimpleXMLElement given in parameter
	* @param SimpleXMLElement $xml The SimpleXMLElement describing the RuleFV to create
	*/
	public static function readFromXML($xml){
	    $rule=new self($xml->class."",$xml->target."");
	    $rule->setConfParams(XMLParamsReader::read($xml));
	    return $rule;
	}
	/**
	* Returns true if the parameters which is targeted is an integer, false otherwise
	* @return boolean true if the parameters which is targeted is an integer, false otherwise
	*/
	public function isValid(){
	    $value=Ressource::getParameters()->getValue($this->target);
	    return preg_match('/^[0-9]*$/',$value);
	}
	
}


