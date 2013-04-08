<?php

class StaticHiddenFieldME extends SimpleFieldME {
    public function toHTML($dataFetched){
        return "";
    }

    public function fetchElementsToSave($dataFetched){
    	$function="set".ucfirst($this->key);
    	$dataFetched['simple']->$function($this->getConfParam('value'));
    }
}
