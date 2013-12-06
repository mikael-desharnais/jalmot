<?php
/**
* An icon in the DesktopManager Module
* Usually described in XML :
* 		<icon>
* 			<class>[Class to Use]</class>
* 			<image>[image file name]</image>
* 			<text>[text to use]</text>
* 			<instance></instance>
* 		</icon>
*/
class DesktopIcon {
	/**
	* The image to use for this icon
	*/
	protected $image;
	/**
	* The text to use for this icon
	*/
	protected $text;
	/**
	* The html instance for this icon
	*/
	protected $instance;
	/**
	* The configuration parameters
	*/
	protected $confParams;
	/**
	* Initiliases the HTML instance, image and text for this module
	* @param string $instance the HTML instance
	* @param string $image the image to use for this icon
	* @param string $text the text for this icon
	*/
	public function __construct($instance,$image,$text){
		$this->image=$image;
		$this->text=$text;
		$this->instance=$instance;
	}
	/**
	* Returns the configuration parameters
	* @return array  the configuration parameters
	*/
	public function getConfParams(){
	    return $this->confParams;
	}
	/**
	* Defines the configuration parameters
	* @param array $confParams  the configuration parameters
	*/
	public function setConfParams($confParams){
	    $this->confParams=$confParams;
	}
	/**
	* Returns the configuration parameter corresponding to the given key
	* @return mixed the configuration parameter corresponding to the given key
	* @param string $key The key corresponding to  the Configuration Parameter
	*/
	public function getConfParam($key){
	    return $this->confParams[$key];
	}
	/**
	* Returns the HTML output for this icon and specific instance
	* File used for output : [template]/html/module/DesktopManager/icons/DesktopIcon_[instance].phtml
	* if this file is not found : 
	* [template]/html/module/DesktopManager/icons/DesktopIcon_standard.phtml
	* @return string the HTML output for this icon and specific instance
	*/
	public function toHTML(){
		ob_start();
		$file=Resource::getCurrentTemplate()->getURL("html/module/DesktopManager/icons/DesktopIcon_".$this->instance.".phtml",true);
		if (empty($file)){
			$file=Resource::getCurrentTemplate()->getURL("html/module/DesktopManager/icons/DesktopIcon_standard.phtml");
		}
		include($file);
		return ob_get_clean();
	}
	/**
	* Returns an instance of DesktopIcon as described in XML object
	* @return DesktopIcon an instance of DesktopIcon as described in XML object
	* @param SimpleXMLElement $icon the xml describing the Icon
	*/
	public static function readFromXML($icon){
	    $classname = $icon->class."";
	    $desktopIcon=new $classname($icon->instance,$icon->image."",$icon->text."");
	    $desktopIcon->setConfParams(XMLParamsReader::read($icon));
	    return $desktopIcon;
	}
	
}


