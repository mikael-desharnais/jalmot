<?php

class ForeignKeyLanguageCellML extends LanguageCellML {

	private $relation;
	
	public function toHTML($element){
		$listName = "lst".ucfirst($this->relation);
		$lineElement=$element->$listName()->getModelDataElement();
		if (!empty($lineElement)){
			$line = ModelLangRelation::getModelDataElement($lineElement,$this->getKey());
		}
		ob_start();
		include(Resource::getCurrentTemplate()->getURL("html/module/ModelListing/ForeignKeyCellML".(empty($this->instance)?"":"_".$this->instance).".phtml"));
		return ob_get_clean();
	}
	
	
	public static function readFromXML($xml){
		$cellDescriptor = parent::readFromXML($xml);
	    $cellDescriptor->setRelation($xml->relation."");
	    $cellDescriptor->setConfParams(XMLParamsReader::read($xml));
	    $instance = $xml->instance."";
	    if (!empty($instance)){
	    	$cellDescriptor->setInstance($xml->instance."");
	    }
	    return $cellDescriptor;
	}
	public function setRelation($relation){
		$this->relation = $relation;
	}
	public function getRelation(){
		return $this->relation;
	}
}
