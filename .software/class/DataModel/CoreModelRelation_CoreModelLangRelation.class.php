<?php

class CoreModelLangRelation extends ModelRelation{
	public static function getModelDataElement(ModelData $modelData,$fieldName,$appendLang=true){
		$langData = $modelData
							->lstLang()
							->getModelData();
		$langArray = array();
		foreach($langData as $lang){
			$langArray[$lang->getIdLang()]=$lang;
		}
		return self::analyseLangData($modelData,$langArray,$fieldName,$appendLang);
	}
	public static function getModelData(ModelDataCollection $modelDataCollection,$fieldName,$appendLang=true){
		$relationDestinationField = $modelDataCollection->getModel()->getRelation('lang')->getDestination();
		$relationSourceField = $modelDataCollection->getModel()->getRelation('lang')->getSource();
		
		$modelDataCollectionToReturn = new ModelDataCollection($relationDestinationField->getModel());
		$langCollection = $modelDataCollection
							->lstLang()
							->getModelData();
		$destinationGetter = "get".$relationDestinationField->getName();
		$sourceGetter = "get".$relationSourceField->getName();
		
		$langArray = array(); 
		foreach($langCollection as $element){
			if (!array_key_exists($element->$destinationGetter(),$langArray)){
				$langArray[$element->$destinationGetter()]=array();
			}
			$langArray[$element->$destinationGetter()][$element->getIdLang()]=$element;
		}
		foreach($modelDataCollection as $element){
			$modelDataCollectionToReturn->addModelData(self::analyseLangData($element, $langArray[$element->$sourceGetter()], $fieldName,$appendLang));
		}
		return $modelDataCollectionToReturn;
	}
	public static function analyseLangData($modelData,$langArray,$fieldName,$appendLang){
		$getter = "get".$fieldName;
		$setter = "set".$fieldName;
		if (array_key_exists(Ressource::getCurrentLanguage()->getId(),$langArray)&&$langArray[Ressource::getCurrentLanguage()->getId()]->$getter()!=""){
			return $langArray[Ressource::getCurrentLanguage()->getId()];
		}elseif(array_key_exists(Language::$defaultLanguageId, $langArray)&&$langArray[Language::$defaultLanguageId]->$getter()!=""){
			$language = Language::getLanguageById(Language::$defaultLanguageId);
			if ($appendLang){
				$langArray[Language::$defaultLanguageId]->$setter($langArray[Language::$defaultLanguageId]->$getter()." <sup>[".$language->getName()."]</sup>");
			}
			return $langArray[Language::$defaultLanguageId];
		}else{
			$found = false;
			foreach($langArray as $idLangElement=>$langElement){
				$language = Language::getLanguageById($idLangElement);
				if ($langElement->$getter()!=""){
					if ($appendLang){
						$langElement->$setter($langElement->$getter()." <sup>[".$language->getName()."]</sup>");
					}
					return $langElement;
				}
			}
			if (!$found){
				$instance = $modelData->getParentModel()->getRelation('lang')->getDestination()->getModel()->getInstance();
				$instance->$setter("[No translation in any language]");
				return $instance;
			}
		}
	}
}



