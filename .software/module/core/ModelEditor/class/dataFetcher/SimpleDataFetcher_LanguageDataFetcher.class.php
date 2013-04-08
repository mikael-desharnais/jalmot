<?php

class LanguageDataFetcher extends SimpleDataFetcher {
    
	public function fetchData($modelEditorDescriptor){
	    $fetchedData=$modelEditorDescriptor->getFetchedData();
	    $model=Model::getModel($this->model_editor->getModel());
	    if ($modelEditorDescriptor->getSource()!=ModelData::$SOURCE_NEW){
	        $db_lines=$fetchedData['simple']->lstLang()
	        				->getModelData(true);
	    }else {
	    	$db_lines = new ModelDataCollection($model);
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
	            $modelLang = ($model->getRelation('lang')->getDestination()->getModel()==$model?$model->getRelation('lang')->getSource()->getModel():$model->getRelation('lang')->getDestination()->getModel());
	            $element=$modelLang->getInstance();
	            $element->setIdLang($language->getId());
	            $db_lines->addModelData($element);
	        }
	    }
	    return array("lang"=>$db_lines);
	}
}
