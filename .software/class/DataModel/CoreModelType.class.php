<?php
/** 
* TODO : Use it and implement 
*/
class CoreModelType{
    /**
    * TODO : See if it's used 
    */
    public static function getType($name){
    	return new ModelType();
    }
    /**
    * TODO : See if it's used 
    */
    public function checkValue($value){
        
    }
    public static function parse($value){
        return $value;
    }
    public static function toSQL($value){
        return $value;
    }
}



