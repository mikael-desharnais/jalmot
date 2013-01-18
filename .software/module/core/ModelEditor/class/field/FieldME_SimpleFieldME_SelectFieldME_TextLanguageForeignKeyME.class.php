<?php

class TextLanguageForeignKeyME extends SelectFieldME {
    
	public function getUsefullData($dataFetched){
	    return array('reference'=>$dataFetched[$this->getConfParam('listReference')],'simple'=>$dataFetched['simple']);
	}
	protected function getValue($element){
		$getter="get".ucfirst($this->key);
		return $element->$getter();
	}
	protected function getNameValue($element){
		$getter="get".ucfirst($this->getConfParam('nameKey'));
		return $element->$getter();
	}
	public function fetchElementsToSave($dataFetched){
	    $function="set".ucfirst($this->getName());
	    if (Ressource::getParameters()->valueExists($this->getName())){
	    	$dataFetched['simple']->$function(Ressource::getParameters()->getValue($this->getName()));
	    }
	}
}
