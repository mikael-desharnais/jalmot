<?php

class CustomSelectFieldME extends SimpleFieldME {

	protected $options = array();
	
	public static function readFromXML($model_editor,$xml){
		$classname=$xml->class."";
		$toReturn=new $classname($classname,$model_editor,$xml->key."",$xml->title."");
		$toReturn->setConfParams(XMLParamsReader::read($xml));
		$instance = $xml->instance."";
		if (!empty($instance)){
			$toReturn->setInstance($xml->instance."");
		}
		foreach($xml->children()->options->children() as $option){
			$toReturn->addOption((string)$option->attributes()->value,(string)$option);
		}
		return $toReturn;
	}
	
	public function addOption($value,$text){
		$this->options[$value]=$text;
	}
	
}
