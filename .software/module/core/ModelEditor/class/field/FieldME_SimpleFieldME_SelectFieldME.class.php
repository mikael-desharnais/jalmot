<?php

class SelectFieldME extends SimpleFieldME {
    
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
	    $function="set".ucfirst($this->key);
	    $contentContainer = $this->model_editor->getParameterContainer();
	    if (array_key_exists($this->key,$contentContainer)){
	    	$dataFetched['simple']->$function($contentContainer[$this->key]);
	    }
	}
}
