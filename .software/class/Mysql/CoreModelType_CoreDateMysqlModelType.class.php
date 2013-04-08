<?php

class CoreDateMysqlModelType extends ModelType{
    public static function parse($value){
        return Date::parseFromTimeStamp(strtotime($value));
    }
    public static function toSQL($value){
    	if (!is_object($value)){
    		$value = Date::getNow();
    	}
        return "CONVERT_TZ(FROM_UNIXTIME(".$value->getTimeStamp()."),@@system_time_zone,'GMT')";
    }
}



