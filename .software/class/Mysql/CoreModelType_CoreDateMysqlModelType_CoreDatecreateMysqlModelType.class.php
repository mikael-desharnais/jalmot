<?php

class CoreDatecreateMysqlModelType extends DateMysqlModelType{

    public static function toSQL($value){
    	if($value == ''){
    		return parent::toSQL(Date::getNow()->getTimeStamp());
    	}else{
    		return parent::toSQL($value);
    	}
    }
}



