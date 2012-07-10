<?php
/**
* Abstract class for testers a tester is used in  a switcher. It is a Case, for example :
* <class>[Switcher class]</class>
* <target>[target name]</target>
* <cases>
* <case>
* <class>TesterFV</class>
* <params>
* <param type="simple" name="value">0</param>
* </params>
* <rules>
* <rule type="normal">
* ...
*/
abstract class TesterFV {
    /**
    * The class of the tester
    */
    public $class;
    /**
    * The configuration parameters
    */
    public $confParams;
    /**
    * The rules to execute if this tester corresponds to the value of the switch
    */
    public $rules=array();
    /**
    * Initialises the class of the tester
    * @param String $class the class of the tester
    */
    public function __construct($class){
        $this->class=$class;
    }
    /**
    * Returns the configuration parameters
    * @return array the configuration parameters
    */
    public function getConfParams(){
        return $this->confParams;
    }
    /**
    * Defines the configuration parameters
    * @param array $confParams the configuration parameters
    */
    public function setConfParams($confParams){
        $this->confParams=$confParams;
    }
    /**
    * Returns the configuration value corresponding to the given key
    * @return mixed the configuration value corresponding to the given key
    * @param string $key the key to the configuration value
    */
    public function getConfParam($key){
        if (array_key_exists($key,$this->confParams)){
            return $this->confParams[$key];
        }else {
            return "";
        }
    }
	/**
	* Adds a rule to this tester, they will be tester if this tester corresponds to the value of its Switcher
	* @param RuleFV $rule The rule to add to this tester
	*/
	public function addRule($rule){
		$this->rules[]=$rule;
	}
	/**
	* Returns an instance of this class as described in the $xml SimpleXMLElement
	* @return TesterFV an instance of this class as described in the $xml SimpleXMLElement
	* @param SimpleXMLElement $xml The SimpleXMLElement describing the object
	*/
	public static function readFromXML($xml){
	    $classname=$xml->class."";
	    $tester=new $classname($classname);
	    $tester->setConfParams(XMLParamsReader::read($xml));
	    foreach($xml->rules->children() as $ruleXML){
	        $tester->addRule(call_user_func(array($ruleXML->class."","readFromXML"),$ruleXML));
	    }
	    return $tester;
	}
	/**
	* Returns true if the rules contained by this tester are valid, false otherwise
	* @return boolean true if the rules contained by this tester are valid, false otherwise
	*/
	public function isValid(){
	    $toReturn=true;
	    foreach($this->rules as $rule){
	        $result=$rule->isValid();
	        $toReturn=$result&&$toReturn;
	    }
	    return $toReturn;
	}
	/**
	* Abstract methods that should return true if the TesterFV corresponds to the given value, false otherwise
	* @param mixed $element The value to test for correspondance
	*/
	public abstract function corresponds($element);	


}




