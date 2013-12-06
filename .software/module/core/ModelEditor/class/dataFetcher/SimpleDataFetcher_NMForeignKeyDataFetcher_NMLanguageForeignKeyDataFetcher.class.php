<?php

class NMLanguageForeignKeyDataFetcher extends NMForeignKeyDataFetcher {
    
	public function fetchData($modelEditorDescriptor){
	    $fetchedData=$modelEditorDescriptor->getFetchedData();
	    $db_lines=array();
	    if ($modelEditorDescriptor->getSource()!=ModelData::$SOURCE_NEW){
	        $methodName="lst".ucfirst($this->getConfParam('relation'));
	        $db_lines=$fetchedData['simple']->$methodName()
	        				->getModelData(true);
	    }
	    $model = Model::getModel($this->getConfParam('reference'))->getRelation('lang')->getDestination()->getModel();
	    if ($this->getConfParam('dataMode')!='adapted'){
	   		$model = Model::getModel($this->getConfParam('reference'))->getRelation('lang')->getDestination()->getModel();
	    	$reference = $model->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$model)
	    	->addConditionBySymbol('=',$model->getField('idLang'), Resource::getCurrentLanguage()->getId())
	    	->getModelData();
	    }else {
	    	$model = Model::getModel($this->getConfParam('reference'));
	   		$reference = ModelLangRelation::getModelData($model->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$model)->getModelData(),$this->getConfParam("adaptedModeKey"));
	    }
	   return array($this->getConfParam('relation')=>$db_lines,$this->getConfParam('reference')=>$reference);
	}
}
