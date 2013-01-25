<?php

class CoreStringMysqlModelType extends ModelType{
    
    public static function toSQL($value){
    	if (is_object($value)){
    		throw new Exception();
    	}
        return "'".addslashes($value)."'";
    }
}



