<?php
/**
* Descriptor for a field validator
* A field validator basically contains a set of rules that are tested with the method isValid consequently a field is validated or not
* Validations are done both in PHP and Javascript
*/
class FieldValidatorDescriptor {
	/**
	* The name of the descriptor
	*/
	public $name;
	/**
	* The class of the descriptor
	*/
	public $class;
	/**
	* The rules contained by this validator
	*/
	public $rules=array();
	/**
	* Initialises the name and class of the descriptor
	* @param string $class The class of the descriptor
	* @param string $name The name of the descriptor
	*/
	public function __construct($class,$name){
	    $this->class=$class;
		$this->name=$name;
	}
	/**
	* Returns the name of the descriptor
	* @return string the name of the descriptor
	*/
	public function getName(){
	    return $this->name;
	}
	/**
	* Returns an instance of fieldValidator as described with $xml
	* Uses readFromXML for rules on classes specified in XML
	* TODO : use class of the XML File
	* @return FieldValidatorDescriptor an instance of fieldValidator as described with $xml
	* @param SimpleXMLElement $xml The xml describing the object requested
	*/
	public static function readFromXML($xml){
	    $classname = $xml->class."";
	    $fieldValidator=new $classname("FieldValidatorDescriptor",$xml->name."");
	    foreach($xml->rules->children() as $ruleXML){
	    	$fieldValidator->addRule(call_user_func(array($ruleXML->class."","readFromXML"),$ruleXML));
	    }
	    return $fieldValidator;
	}
	/**
	* Adds a rule to the field validator
	* @param RuleFV $rule The rule to add
	*/
	public function addRule($rule){
	    $this->rules[]=$rule;
	}
	/**
	* Returns true if the rules contained are all valid, false otherwise
	* @return boolean true if the rules contained are all valid, false otherwise
	*/
	public function isValid(){
	    $toReturn=true;
	    foreach($this->rules as $rule){
	        $result=$rule->isValid();
	        $toReturn=$result&&$toReturn;
	    }
	    return $toReturn;
	}
	
}


