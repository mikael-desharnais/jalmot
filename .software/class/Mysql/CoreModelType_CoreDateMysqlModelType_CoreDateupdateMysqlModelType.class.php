<?php

class CoreDateupdateMysqlModelType extends CoreDateMysqlModelType{

    public static function toSQL($value){
    	return "FROM_UNIXTIME(".Date::getNow()->getTimeStamp().")";

    }
}



