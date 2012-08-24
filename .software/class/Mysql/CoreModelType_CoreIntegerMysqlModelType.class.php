<?php

class CoreIntegerMysqlModelType extends ModelType{
    public static function parse($value){
        return (int)$value;
    }
    public static function toSQL($value){
        return (int)$value;
    }
}



