<?php

class TextME extends FieldME {
    
	public function getUsefullData($dataFetched){
	    return $dataFetched['simple'];
	}
	protected function getValue($element){
		$getter="get".ucfirst($this->key);
		return $element->$getter();
	}
	public function fetchElementsToSave($dataFetched){
	}
}
