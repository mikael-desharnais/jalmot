<?php

class CoreDatecreateMysqlModelType extends CoreDateMysqlModelType{

    public static function toSQL($value){
    	if($value == ''){
    		return "FROM_UNIXTIME(".Date::getNow()->getTimeStamp().")";
    	}else{
        	return "FROM_UNIXTIME(".$value->getTimeStamp().")";
    	}
    }
}



