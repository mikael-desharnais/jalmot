<?php

class ModelListingME extends FieldME {
    
	protected $localKey;
	protected $referenceKey;
	
	public function __construct($class,$model_editor,$localKey,$referenceKey,$title){
		$this->model_editor=$model_editor;
		$this->localKey=$localKey;
		$this->referenceKey=$referenceKey;
		$this->title=$title;
		$this->class=$class;
	}
	public static function readFromXML($model_editor,$xml){
	    $classname=$xml->class."";
	    $toReturn=new $classname($classname,$model_editor,$xml->localKey."",$xml->referenceKey."",$xml->title."");
	    $toReturn->setConfParams(XMLParamsReader::read($xml));
		return $toReturn;
	}
	
	public function getUsefullData($dataFetched){
	    $xml=XMLDocument::parseFromFile(Ressource::getCurrentTemplate()->getFile(new File("xml/module/ModelListing/descriptor",$this->getConfParam('modelListing').".xml",false)));
	    $element = call_user_func(array($xml->class."","readFromXML"),$this->getConfParam('modelListing'),$xml);
	    $element->addFilter(new FilterML('=',$this->referenceKey,$this->getValue($dataFetched['simple'])));
	    $element->fetchData();
	    return $element;
	}
	protected function getValue($element){
		$getter="get".ucfirst($this->localKey);
		return $element->$getter();
	}
	public function fetchElementsToSave($dataFetched){
	}
	public function toHTML($dataFetched){
		if ($dataFetched['simple']->source==ModelData::$SOURCE_NEW){
			return "";
		}else {
			return parent::toHTML($dataFetched);
		}
	}}
