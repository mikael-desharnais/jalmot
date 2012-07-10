<?php
/**
* A rule for the Field Validator
*/
abstract class RuleFV {
	/**
	* The target of this rule
	*/
	public $target;
	/**
	* The class of this rule
	*/
	public $class;
	/**
	* The configuration parameters for this rule
	*/
	public $confParams;
	/**
	* Defines the class and target of this rule
	* @param string $class The class of this rule
	* @param string $target The tarhet of this rule
	*/
	public function __construct($class,$target){
	    $this->class=$class;
		$this->target=$target;
	}
	/**
	* Returns The configuration parameters of this rules
	* @return array The configuration parameters of this rules
	*/
	public function getConfParams(){
	    return $this->confParams;
	}
	/**
	* Defines The configuration parameters of this rules
	* @param array $confParams The configuration parameters of this rules
	*/
	public function setConfParams($confParams){
	    $this->confParams=$confParams;
	}
	/**
	* Returns a configuration parameter given its key
	* @return mixed The configuration parameter corresponding to the given key
	* @param string $key the key corresponding to the Configuration Parameter
	*/
	public function getConfParam($key){
	    if (array_key_exists($key,$this->confParams)){
	    	return $this->confParams[$key];
	    }else {
	        return "";
	    }
	}
	/**
	* Returns an instance of RuleFV as described in the SimpleXMLElement given in parameter
	* @return RuleFV  an instance of RuleFV as described in the SimpleXMLElement given in parameter
	* @param SimpleXMLElement $xml The SimpleXMLElement describing the RuleFV to create
	*/
	public static function readFromXML($xml){
	    $classname=$xml->class."";
	    $rule=new $classname($classname,$xml->target."");
	    $rule->setConfParams(XMLParamsReader::read($xml));
	    return $rule;
	}
	/**
	* Returns true if the Rule is Validated, false otherwise
	*/
	public abstract function isValid();
	
	
}


