<?php

class ForeignKeyCellML extends SimpleCellML {

	private $relation;
	
	public function toHTML($element){
		$listName = "lst".ucfirst($this->relation);
		$line=$element->$listName()->getModelDataElement();

		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/SimpleCellML.phtml"));
		return ob_get_clean();
	}
	
	public static function readFromXML($xml){
	    $cellDescriptor=new self($xml->key."",$xml->model."");
	    $cellDescriptor->setRelation($xml->relation."");
	    $cellDescriptor->setConfParams(XMLParamsReader::read($xml));
	    return $cellDescriptor;
	}
	public function setRelation($relation){
		$this->relation = $relation;
	}
	public function getRelation(){
		return $this->relation;
	}
}
