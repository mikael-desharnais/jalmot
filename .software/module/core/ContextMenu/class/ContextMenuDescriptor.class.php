<?php
/**
* Wrapper for a ContextMenuDescriptor
* created from XML :
* <contextmenu>
* 	<class>[Class of the Descriptor to use]</class>
* 	<params>
* 	</param>
* 	<elements>
* 		[elements]
* 	</elements>
* </contextmenu>
* 
* A context menu descriptor has a class, a name, a list of Configuration parameters and contains a list of ElementCM
*/
class ContextMenuDescriptor {
    /**
    * Returns an instance of ContextMenuDescriptor based on the data contained by the XML given as parameter
    * @return ContextMenuDescriptor an instance of ContextMenuDescriptor based on the data contained by the XML given as parameter
    * @param string $name Name of the ContextMenuDescriptor
    * @param SimpleXMLElement $xml The xml describing the ContextMenuDescriptor
    */
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
    /**
    * Configuration parameters
    */
    public $confParams=array();
    /**
    * The name of the descriptor
    */
    public $name;
    /**
    * The class of the descriptor
    */
    public $class;
    /**
    * The list of ElementCM contained
    */
    public $elements=array();
	/**
	* Returns the Configuration parameters
	* @return array the Configuration parameters
	*/
	public function getConfParams(){
	    return $this->confParams;
	}
	/**
	* Defines the configuration parameters
	* @param array $confParams the Configuration parameters
	*/
	public function setConfParams($confParams){
	    $this->confParams=$confParams;
	}
	/**
	* Returns a configuration parameter given its key
	* If the key is not found, an empty string is returned
	* @return mixed a configuration parameter given its key
	* @param string $key the key to the configuration parameter
	*/
	public function getConfParam($key){
	    if (array_key_exists($key,$this->confParams)){
	    	return $this->confParams[$key];
	    }else {
	        return "";
	    }
	}
	/**
	* Adds an ElementCM to the ContextElementDescriptor
	* @param ElementCM $element The ElementCM to add to the ContextElementDescriptor
	*/
	public function addElement($element){
	    $this->elements[]=$element;
	}
	/**
	* Returns the name of this descriptor
	* @return string the name of this descriptor
	*/
	public function getName(){
	    return $this->name;
	}
	/**
	* Defines the name of this descriptor
	* @param string $name the name of this descriptor
	*/
	public function setName($name){
	    $this->name=$name;
	}
	/**
	* Defines the classname of this descriptor
	* @param string $class the classname of this descriptor
	*/
	public function setClass($class){
	    $this->class=$class;
	}
}


