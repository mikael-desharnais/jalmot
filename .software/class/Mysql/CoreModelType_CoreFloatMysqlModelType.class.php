<?php

class CoreFloatMysqlModelType extends ModelType{
    public static function parse($value){
        return (float)$value;
    }
    public static function toSQL($value){
        return (float)$value;
    }
}



