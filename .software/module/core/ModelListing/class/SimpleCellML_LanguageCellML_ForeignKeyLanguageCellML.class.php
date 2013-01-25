<?php

class ForeignKeyLanguageCellML extends LanguageCellML {

	private $relation;
	
	public function toHTML($element){
		$listName = "lst".ucfirst($this->relation);
		$line_query=$element->$listName()->getModelDataElement()->lstLang();
		$line = $line_query->addConditionBySymbol('=',$line_query->getModel()->getField('idLang'), Ressource::getCurrentLanguage()->getId())
							->getModelDataElement();
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/SimpleCellML".(empty($this->instance)?"":"_".$this->instance).".phtml"));
		return ob_get_clean();
	}
	
	
	public static function readFromXML($xml){
		$cellDescriptor = parent::readFromXML($xml);
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
