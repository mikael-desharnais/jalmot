<?php

class WindowManager extends Module{
    public static $configurations= array();
    public function getWindowConfiguration($name){
        if (!array_key_exists($name, WindowManager::$configurations)){
            $toReturn=array();
            $xmlFile = Resource::getCurrentTemplate()->getFile(new File("xml/module/WindowManager/configuration",$name.".xml",false),true);
            if ($xmlFile->exists()){
	            $xml=XMLDocument::parseFromFile($xmlFile);
	            foreach($xml as $param){
	                $attribute=$param->attributes();
	                $toReturn[$attribute->name.""]=$param."";
	            }
            }
            WindowManager::$configurations[$name]=$toReturn;
        }
        return WindowManager::$configurations[$name];
    }
}
