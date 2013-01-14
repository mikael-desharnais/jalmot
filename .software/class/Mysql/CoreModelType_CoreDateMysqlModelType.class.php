<?php

class CoreDateMysqlModelType extends ModelType{
    public static function parse($value){
        return Date::parseFromTimeStamp(strtotime($value));
    }
    public static function toSQL($value){
    	if (!is_object($value)){
    		$value = Date::getNow();
    	}
        return "FROM_UNIXTIME(".$value->getTimeStamp().")";
    }
}



