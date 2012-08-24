<?php

class SimpleFieldME extends FieldME {
    
	public function getUsefullData($dataFetched){
	    return $dataFetched['simple'];
	}
	protected function getValue($element){
		$getter="get".ucfirst($this->key);
		return $element->$getter();
	}
	public function fetchElementsToSave($dataFetched){
	    $function="set".ucfirst($this->getName());
	    if (Ressource::getParameters()->valueExists($this->getName())){
	    	$dataFetched['simple']->$function(Ressource::getParameters()->getValue($this->getName()));
	    }
	}
}
