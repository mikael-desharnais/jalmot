<?php

class ModelListingME extends FieldME {
    
	public function getUsefullData($dataFetched){
	    $xml=XMLDocument::parseFromFile(Ressource::getCurrentTemplate()->getFile(new File("xml/module/ModelListing/descriptor",$this->getConfParam('modelListing').".xml",false)));
	    $element = call_user_func(array($xml->class."","readFromXML"),$xml);
	    $element->addFilter(new FilterML('=',$this->key,$this->getValue($dataFetched['simple'])));
	    $element->fetchData();
	    return $element;
	}
	protected function getValue($element){
		$getter="get".ucfirst($this->key);
		return $element->$getter();
	}
	public function fetchElementsToSave($dataFetched){
	}
}
