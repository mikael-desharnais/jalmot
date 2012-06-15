<?php

class LanguageDataFetcher extends SimpleDataFetcher {
    
	public function fetchData($modelEditorDescriptor){
	    $fetchedData=$modelEditorDescriptor->getFetchedData();
	    $model=Model::getModel($this->model_editor->getModel());
	    $db_lines=array();
	    if ($modelEditorDescriptor->getSource()!=ModelData::$SOURCE_NEW){
	        $db_lines=$fetchedData['simple']->lstLang()
	        				->getModelData(true);
	    }
	    
	    $languages=Language::getAvailableLanguages();
	    $line_lang=array();
	    foreach($languages as $language){
	        $found=false;
	        foreach($db_lines as $db_line){
	            if ($db_line->getIdLang()==$language->getId()){
	                $found=true;
	            }
	        }
	        if (!$found){
	            $element=($model->getRelation('lang')->getDestination()->getModel()==$model?$model->getRelation('lang')->getSource()->getModel()->getInstance():$model->getRelation('lang')->getDestination()->getModel()->getInstance());
	            $element->data_source=Ressource::getDataSource();
	            $element->setIdLang($language->getId());
	            $db_lines[]=$element;
	        }
	    }
	    return array("lang"=>$db_lines);
	}
}
