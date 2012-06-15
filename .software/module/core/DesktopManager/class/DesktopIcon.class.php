<?php

class DesktopIcon {
    
	protected $image;
	protected $text;
	protected $instance;
	protected $confParams;
	
	public function __construct($instance,$image,$text){
		$this->image=$image;
		$this->text=$text;
		$this->instance=$instance;
	}
	public function getConfParams(){
	    return $this->confParams;
	}
	public function setConfParams($confParams){
	    $this->confParams=$confParams;
	}
	public function getConfParam($key){
	    return $this->confParams[$key];
	}
	public function toHTML(){
		ob_start();
		$file=Ressource::getCurrentTemplate()->getURL("html/module/DesktopManager/icons/DesktopIcon_".$this->instance.".phtml",true);
		if (empty($file)){
			$file=Ressource::getCurrentTemplate()->getURL("html/module/DesktopManager/icons/DesktopIcon_standard.phtml");
		}
		include($file);
		return ob_get_clean();
	}
	public static function readFromXML($icon){
	    $desktopIcon=new DesktopIcon($icon->instance,$icon->image."",$icon->text."");
	    $desktopIcon->setConfParams(XMLParamsReader::read($icon));
	    return $desktopIcon;
	}
	
}
