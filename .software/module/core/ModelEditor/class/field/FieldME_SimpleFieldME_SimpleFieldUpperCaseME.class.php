<?php

class SimpleFieldUpperCaseME extends SimpleFieldME {
    

	public function fetchElementsToSave($dataFetched){
		$function="set".ucfirst($this->key);
		$contentContainer = $this->model_editor->getParameterContainer();
		if (array_key_exists($this->key,$contentContainer)){
			$dataFetched['simple']->$function(strtoupper($contentContainer[$this->key]));
		}
	}
}
