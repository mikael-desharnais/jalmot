<?php

class ForeignKeyDataFetcher extends SimpleDataFetcher {
    
	public function fetchData($modelEditorDescriptor){
	   $model = Model::getModel($this->getConfParam('reference'));
	   $reference = $model->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$model)->getModelData(true);
	   return array($this->getConfParam('reference')=>$reference);
	}
}
