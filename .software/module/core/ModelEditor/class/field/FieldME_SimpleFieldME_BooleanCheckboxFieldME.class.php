<?php

class BooleanCheckboxFieldME extends SimpleFieldME {
	
	public function fetchElementsToSave($dataFetched){
		$function="set".ucfirst($this->getName());
		if (Ressource::getParameters()->getValue("action")=='save'){
			if (Ressource::getParameters()->valueExists($this->getName())){
				$dataFetched['simple']->$function(true);
			}else {
				$dataFetched['simple']->$function(false);
			}
		}
	}

}
