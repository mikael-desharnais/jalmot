<?php

class LanguageForeignKeyDataFetcher extends SimpleDataFetcher {
    
	public function fetchData($modelEditorDescriptor){
	   $model_lang = Model::getModel($this->getConfParam('reference'))->getRelation('lang')->getDestination()->getModel();
	   $reference = $model_lang->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$model_lang)
	   											->addConditionBySymbol('=',$model_lang->getField('idLang'), Ressource::getCurrentLanguage()->getId())
	   											->getModelData(true);
	   return array($this->getConfParam('reference')=>$reference);
	}
}
