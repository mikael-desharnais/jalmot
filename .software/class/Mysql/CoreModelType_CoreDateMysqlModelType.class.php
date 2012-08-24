<?php

class CoreDateMysqlModelType extends ModelType{
    public static function parse($value){
        return Date::parseFromTimeStamp(strtotime($value));
    }
    public static function toSQL($value){
        return "FROM_UNIXTIME(".$value->getTimeStamp().")";
    }
}



