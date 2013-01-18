<?php

class SimpleHeadCellML {
	protected $title;
	protected $instance;
    public function __construct($title){
		$this->title=$title;
	}
	public function toHTML(){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/SimpleHeadCellML".(empty($this->instance)?"":"_".$this->instance).".phtml"));
		return ob_get_clean();
	}
	public static function readFromXML($xml){
		$toReturn = new self($xml->title."");
	    $instance = $xml->instance."";
	    if (!empty($instance)){
	    	$toReturn->setInstance($xml->instance."");
	    }
		return $toReturn;
	}
	public function setInstance($instance){
		$this->instance=$instance;
	}
}
