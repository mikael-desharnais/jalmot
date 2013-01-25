<?php

class SimpleFieldUpperCaseME extends SimpleFieldME {
    
	public function fetchElementsToSave($dataFetched){
	    $function="set".ucfirst($this->getName());
	    if (Ressource::getParameters()->valueExists($this->getName())){
	    	$dataFetched['simple']->$function(strtoupper(Ressource::getParameters()->getValue($this->getName())));
	    }
	}
}
