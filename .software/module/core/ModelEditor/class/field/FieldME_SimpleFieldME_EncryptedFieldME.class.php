<?php

class EncryptedFieldME extends SimpleFieldME {
    
	public function fetchElementsToSave($dataFetched){
	    $value=Ressource::getParameters()->getValue($this->getName());
	    if (!empty($value)){
		    $function="set".ucfirst($this->getName());
		    $dataFetched['simple']->$function(Model::getModel($this->model_editor->getModel())->getField($this->key)->getEncryptedValue($value)->getValue());
	    }
	}
}
