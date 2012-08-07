<?php

class ForeignKeyDataFetcher extends SimpleDataFetcher {
    
	public function fetchData($modelEditorDescriptor){
	   $reference = Ressource::getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,Model::getModel($this->getConfParam('reference')))->getModelData(true);
	   return array($this->getConfParam('reference')=>$reference);
	}
}
