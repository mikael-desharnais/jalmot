<?php

class CoreStringMysqlModelType extends ModelType{
    
    public static function toSQL($value){
    	if (is_object($value)){
    		print('<pre>++');
    		print(get_class($value));
    		debug_print_backtrace();
    		print('+++');
    		throw new Exception();
    	}
        return "'".addslashes($value)."'";
    }
}



