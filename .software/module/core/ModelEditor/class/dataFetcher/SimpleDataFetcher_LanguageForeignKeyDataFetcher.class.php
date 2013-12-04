<?php

class LanguageForeignKeyDataFetcher extends SimpleDataFetcher {
    
	public function fetchData($modelEditorDescriptor){
	   
	   if ($this->getConfParam('dataMode')!='adapted'){
	  		$model_lang = Model::getModel($this->getConfParam('reference'))->getRelation('lang')->getDestination()->getModel();
		   	$reference = $model_lang->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$model_lang)
	   											->addConditionBySymbol('=',$model_lang->getField('idLang'), Ressource::getCurrentLanguage()->getId())
	   											->getModelData(true);
	   }else {
		   	$model =  Model::getModel($this->getConfParam('reference'));
		   	$reference = ModelLangRelation::getModelData($model->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$model)->getModelData(),$this->getConfParam("adaptedModeKey"));
	   }
	   return array($this->getConfParam('reference')=>$reference);
	}
}