<?php

class EncryptedFieldME extends SimpleFieldME {
    
	public function fetchElementsToSave($dataFetched){

		$function="set".ucfirst($this->key);
		$contentContainer = $this->model_editor->getParameterContainer();
		if (array_key_exists($this->key,$contentContainer)&&!empty($contentContainer[$this->key])){
			$dataFetched['simple']->$function(Model::getModel($this->model_editor->getModel())->getField($this->key)->getEncryptedValue($contentContainer[$this->key])->getValue());
		}
	}
}
