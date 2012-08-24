<?php

class CoreStringMysqlModelType extends ModelType{
    
    public static function toSQL($value){
        return "'".addslashes($value)."'";
    }
}



