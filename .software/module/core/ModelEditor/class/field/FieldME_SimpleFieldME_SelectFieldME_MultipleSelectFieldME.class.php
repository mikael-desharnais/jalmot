<?php

class MultipleSelectFieldME extends SelectFieldME {
    
    public function getUsefullData($dataFetched){
        return array("data"=>$dataFetched[$this->getConfParam('relation')],"reference"=>$dataFetched[$this->getConfParam('listReference')]);
    }
    protected function getValue($element){
        $getter="get".ucfirst($this->key);
        return $element->$getter();
    }
    public function fetchElementsToSave($dataFetched){
        if (Ressource::getParameters()->valueExists($this->getName())){
	        foreach($dataFetched[$this->getConfParam('relation')] as $element){
	           $element->delete();
	        }
	        $toCreate = Ressource::getParameters()->getValue($this->getName());
	        $nameIdForeignKeyInRelation = "set".ucfirst($this->getConfParam('IdForeignKeyInRelation'));
	        $nameIdCurrentKeyInRelation = "set".ucfirst($this->getConfParam('IdCurrentKeyInRelation'));
	        $nameLocalKey = "get".ucfirst($this->getConfParam('localKey'));
	        
	        $dataFetched[$this->getConfParam('relation')]=array();
	        if (is_array($toCreate)){
		        foreach($toCreate as $id_foreign_key){
		            $element = Model::getModel($this->model_editor->getModel())->getRelation($this->getConfParam('relation'))->getDestination()->getModel()->getInstance();
		            $element->data_source=Ressource::getDataSource();
		            $element->$nameIdForeignKeyInRelation($id_foreign_key);
			    	if ($dataFetched['simple']->source!=ModelData::$SOURCE_NEW){
			    	    $element->$nameIdCurrentKeyInRelation($dataFetched['simple']->$nameLocalKey());
			    	}
			    	$dataFetched['simple']->addModelDataForChainSave($element);
		            $dataFetched[$this->getConfParam('relation')][]=$element;
		        }
	        }
	        $eventListener=new EventListener($dataFetched[$this->getConfParam('relation')]);
	        $functionEventListener=function ($target,$listener){
	            $primary_keys=$target->getPrimaryKeys();
	            foreach($listener as $element){
		            foreach($primary_keys as $key=>$value){
		                $function="set".ucfirst($key);
		                $element->$function($value);
		            }
	            }
	        };	  
		    if ($dataFetched['simple']->source==ModelData::$SOURCE_NEW){
		    	$eventListener->afterSavePerformed=$functionEventListener;
		        $dataFetched['simple']->addAfterSaveListener($eventListener);
		    }
        }
    }
}
