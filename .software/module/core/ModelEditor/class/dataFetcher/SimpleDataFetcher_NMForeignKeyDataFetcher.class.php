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
	    $model = Model::getModel($this->getConfParam('reference'));
	   $reference = $model->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$model)->getModelData();
	   return array($this->getConfParam('relation')=>$db_lines,$this->getConfParam('reference')=>$reference);
	}
}
