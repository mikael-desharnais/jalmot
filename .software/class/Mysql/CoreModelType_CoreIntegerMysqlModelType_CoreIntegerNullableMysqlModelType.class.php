<?php

class CoreIntegerNullableMysqlModelType extends IntegerMysqlModelType{
    public static function parse($value){
    	if (!empty($value)){
    		return parent::parse($value);
    	}else {
    		return null;
    	}
    }
    public static function toSQL($value){
    	if (!empty($value)){
    		return parent::toSQL($value);
    	}else {
        	return 'NULL';
    	}
    }
}



