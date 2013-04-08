<?php

class BooleanCellML extends  SimpleCellML {

	public $trueText;
	public $falseText;
	
	protected function getValue($line){
		$value = parent::getValue($line);
		if ($value){
			return Ressource::getCurrentLanguage()->getTranslation($this->trueText);
		}else {
			return Ressource::getCurrentLanguage()->getTranslation($this->falseText);
		}
	}
	public static function readFromXML($xml){
		$cellDescriptor = parent::readFromXML($xml);
		$cellDescriptor->setTrueText($xml->trueText."");
		$cellDescriptor->setFalseText($xml->falseText."");
		return $cellDescriptor;
	}
	public function setTrueText($trueText){
		$this->trueText=$trueText;
	}
	public function setFalseText($falseText){
		$this->falseText=$falseText;
	}
}
