<?php

class LanguageCellML extends SimpleCellML {

	public function toHTML($element){
		$line_query=$element->lstLang();
		$line = $line_query->addConditionBySymbol('=',$line_query->getModel()->getField('idLang'), Ressource::getCurrentLanguage()->getId())
							->getModelDataElement();
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/SimpleCellML.phtml"));
		return ob_get_clean();
	}
	
	public static function readFromXML($xml){
	    $cellDescriptor=new self($xml->key."",$xml->model."");
	    $cellDescriptor->setConfParams(XMLParamsReader::read($xml));
	    return $cellDescriptor;
	}
}
