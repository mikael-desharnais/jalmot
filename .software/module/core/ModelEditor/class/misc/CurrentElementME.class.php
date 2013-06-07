<?php

class CurrentElementME {
    protected $key;
	protected $class;
	
	public function __construct($class,$key){
		$this->class=$class;
		$this->key=$key;
	}
	public static function readFromXML($xml){
	    $classname=$xml->class."";
	    $toReturn=new $classname($classname,$xml->key."");
		return $toReturn;
	}
	public function getElement($parentElement){
		$element = $parentElement;
		if ($element->getSource()==ModelData::$SOURCE_NEW){
			return new ElementBreadCrumbME(Ressource::getCurrentLanguage()->getTranslation($element->getParentModel()->getName()." in breadcrumb"), Ressource::getCurrentLanguage()->getTranslation("New element"),$element);
		}else {
			$elementLangQuery = $element->lstLang();
			$elementLang = $elementLangQuery->addConditionBySymbol('=',$elementLangQuery->getModel()->getField('idLang'), Ressource::getCurrentLanguage()->getId())->getModelDataElement();
			$getter = "get".ucfirst($this->key);
			return new ElementBreadCrumbME(Ressource::getCurrentLanguage()->getTranslation($element->getParentModel()->getName()." in breadcrumb"), (!empty($elementLang)?$elementLang->$getter():""),$element);
		}
	}
}
