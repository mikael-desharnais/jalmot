<?php
/**
* The class for an element of ContextMenu (CM)
* Mostly described through XML :
* 		<element>
* 			<title>[TITLE of the ElementCM]</title>
* 			<class>[CLASS of the ElementCM]</class>
* 		</element>
* If you need to set other parameters or operate differently, create a new class extending elementCM and use its name as a <class/> value
*/
abstract class ElementCM {
    /**
    * Returns an instance of ElementCM as described in the XML given as parameter
    * @return ElementCM  an instance of ElementCM as described in the XML given as parameter
    * @param ContextMenuDescriptor $parentDescriptor The ContextMenuDescriptor that contains this ElementCM
    * @param SimpleXMLElement $xml XML containing the description of the elementCM
    */
    public static function readFromXML($parentDescriptor,$xml){
        $classname=$xml->class."";
        $contextMenuElement=new $classname();
        $contextMenuElement->setClass($classname);
        $contextMenuElement->setTitle($xml->title."");
        $contextMenuElement->setConfParams(XMLParamsReader::read($xml));
        return $contextMenuElement;
    }
    /**
    * The configuration parameters
    */
    public $confParams=array();
    /**
    * The title of this element
    */
    public $title;
    /**
    * The class of this element
    */
    public $class;
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
	* Returns the title of this element
	* @return string the title of this element
	*/
	public function getTitle(){
	    return $this->title;
	}
	/**
	* Defines the title of this ElementCM
	* @param string $title the title of this ElementCM
	*/
	public function setTitle($title){
	    $this->title=$title;
	}
	/**
	* Defines the class of this element
	* @param string $class  The class name of this element
	*/
	public function setClass($class){
	    $this->class=$class;
	}
}


