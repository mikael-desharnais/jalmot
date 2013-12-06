<?php

class LangRelationME {
    protected $key;
	protected $class;
	protected $name;
	
	public function __construct($class,$key,$name){
		$this->class=$class;
		$this->key=$key;
		$this->name = $name;
	}
	public static function readFromXML($xml){
	    $classname=$xml->class."";
	    $toReturn=new $classname($classname,$xml->key."",$xml->name."");
		return $toReturn;
	}
	public function getElement($parentElement){
		$lister = "lst".ucfirst($this->name);
		$element = $parentElement->$lister()->getModelDataElement();
		$elementLangQuery = $element->lstLang();
		$elementLang = $elementLangQuery->addConditionBySymbol('=',$elementLangQuery->getModel()->getField('idLang'), Resource::getCurrentLanguage()->getId())->getModelDataElement();
		$getter = "get".ucfirst($this->key);
		return new ElementBreadCrumbME(Resource::getCurrentLanguage()->getTranslation($element->getParentModel()->getName()." in breadcrumb"), $elementLang->$getter(),$element);
	}
}
