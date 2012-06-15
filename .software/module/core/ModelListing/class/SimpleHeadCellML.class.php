<?php

class SimpleHeadCellML {
	private $title;
    public function __construct($title){
		$this->title=$title;
	}
	public function toHTML(){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/SimpleHeadCellML.phtml"));
		return ob_get_clean();
	}
	public static function readFromXML($xml){
		return new self($xml->title."");
	}
}
