<?php

class CoreXMLDocument{
	public static function parseFromFile($filename){
	    return simplexml_load_file($filename->toURL());
	}
}
?>
