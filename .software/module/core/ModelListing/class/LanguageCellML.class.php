<?php

/**
 *
 */
class LanguageCellML extends SimpleCellML {

	private $model_lang;
	public function __construct($key,$model_lang){
		parent::__construct($key);
		$this->model_lang=$model_lang;
	}
	public function toHTML($line){
		$line=$line->lstLang()
					->addConditionBySymbol('=',Model::getModel($this->model_lang)->getField('idLang'), Ressource::getCurrentLanguage()->getId())
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
