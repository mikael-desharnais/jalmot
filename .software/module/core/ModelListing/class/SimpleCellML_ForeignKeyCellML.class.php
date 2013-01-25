<?php

class ForeignKeyCellML extends SimpleCellML {

	private $relation;
	
	public function toHTML($element){
		$listName = "lst".ucfirst($this->relation);
		$line=$element->$listName()->getModelDataElement();

		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/SimpleCellML".(empty($this->instance)?"":"_".$this->instance).".phtml"));
		return ob_get_clean();
	}
	
	public static function readFromXML($xml){
	    $cellDescriptor=parent::readFromXML($xml);
	    $cellDescriptor->setRelation($xml->relation."");
	    return $cellDescriptor;
	}
	public function setRelation($relation){
		$this->relation = $relation;
	}
	public function getRelation(){
		return $this->relation;
	}
}
