<?php
class CoreEAVModel extends Model{
    
    public static function readFromXML($class,$name,$xml){
    	$toReturn = parent::readFromXML($class,$name,$xml);
		return $toReturn;
    }
    public function __construct($name){
        parent::__construct($name);
        $this->modelDataClass="EAVModelData";
    }
    public function getField($name){
    }
}

