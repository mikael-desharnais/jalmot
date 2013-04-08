<?php

class CoreDateupdateMysqlModelType extends DateMysqlModelType{

    public static function toSQL($value){
    	return parent::toSQL(Date::getNow()->getTimeStamp());

    }
}



