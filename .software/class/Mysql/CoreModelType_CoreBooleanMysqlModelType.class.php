<?php

class CoreBooleanMysqlModelType extends ModelType{
    
    public static function parse($value){
        return $value==1?false:true;
    }
    public static function toSQL($value){
        return $value?1:0;
    }
}



