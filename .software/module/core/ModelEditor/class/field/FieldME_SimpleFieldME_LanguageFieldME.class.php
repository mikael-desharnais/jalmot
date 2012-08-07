<?php

class LanguageFieldME extends SimpleFieldME {

	protected $model_lang;

	public function setModelLang($model_lang){
	    $this->model_lang=$model_lang;
	}
	public function getUsefullData($dataFetched){
	    return $dataFetched['lang'];
	}

	public function getValueLang($line_lang,$id_lang){
		$getter="get".ucfirst($this->key);
		foreach($line_lang as $line){
			if ($line->getIdLang()==$id_lang){	
				return $line->$getter();
			}
		}
	}
	public static function readFromXML($model_editor,$xml){
	    $toReturn = parent::readFromXML($model_editor,$xml);
	    $toReturn->setModelLang($xml->model."");
		return $toReturn;
	}
	public function fetchElementsToSave($dataFetched){
	    $line_lang=$dataFetched['lang'];
	    $values=Ressource::getParameters()->getValue($this->getName());
	    $function="set".ucfirst($this->getName());
	    foreach($line_lang as $line_element){
	        $value=array_key_exists($line_element->getIdLang(), $values)?$values[$line_element->getIdLang()]:null;
	        $line_element->$function($value);
	        $eventListener=new EventListener($line_element);
	        $functionEventListener=function ($target,$listener){
	            $primary_keys=$target->getPrimaryKeys();
	            foreach($primary_keys as $key=>$value){
	                $function="set".ucfirst($key);
	                $listener->$function($value);
	            }
	        };
	         
	        if ($line_element->source==ModelData::$SOURCE_NEW){
	            $eventListener->afterSavePerformed=$functionEventListener;
	            $dataFetched['simple']->addAfterSaveListener($eventListener);
	        }
	        $dataFetched['simple']->addModelDataForChainSave($line_element);
	    }
	}
}
