<?php

class BooleanCheckboxFieldME extends SimpleFieldME {
	
	public function fetchElementsToSave($dataFetched){
		$function="set".ucfirst($this->key);
		$contentContainer = $this->model_editor->getParameterContainer();
		if (Ressource::getParameters()->getValue("action")=='save'){
			if (array_key_exists($this->key,$contentContainer)){
				$dataFetched['simple']->$function(true);
			}else {
				$dataFetched['simple']->$function(false);
			}
		}
	}

}
