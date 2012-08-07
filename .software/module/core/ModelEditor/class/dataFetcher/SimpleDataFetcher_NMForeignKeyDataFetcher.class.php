<?php

class NMForeignKeyDataFetcher extends SimpleDataFetcher {
    
	public function fetchData($modelEditorDescriptor){
	    $fetchedData=$modelEditorDescriptor->getFetchedData();
	    $db_lines=array();
	    if ($modelEditorDescriptor->getSource()!=ModelData::$SOURCE_NEW){
	        $methodName="lst".ucfirst($this->getConfParam('relation'));
	        $db_lines=$fetchedData['simple']->$methodName()
	        				->getModelData(true);
	    }
	   $reference = Ressource::getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,Model::getModel($this->getConfParam('reference')))->getModelData(true);
	   return array($this->getConfParam('relation')=>$db_lines,$this->getConfParam('reference')=>$reference);
	}
}
