<?php

class DatetimeFieldME extends SimpleFieldME {
    public function fetchElementsToSave($dataFetched){
        $function="set".ucfirst($this->getName());
        if (Ressource::getParameters()->valueExists($this->getName())){
        	$dataFetched['simple']->$function(Date::parseFromFormat("d/m/Y H:i",Ressource::getParameters()->getValue($this->getName())));
        }
    }
}
