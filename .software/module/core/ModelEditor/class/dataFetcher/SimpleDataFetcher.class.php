<?php

class SimpleDataFetcher {
    protected $model_editor;
    
    public function __construct($model_editor){
        $this->model_editor=$model_editor;
	}
		
	public function fetchData($modelEditorDescriptor){
	    $model=Model::getModel($this->model_editor->getModel());
	    if ($modelEditorDescriptor->getSource()==ModelData::$SOURCE_NEW){
	        $element=$model->getInstance();
	        $element->data_source=Ressource::getDataSource();
	    }else {
	        $query=Ressource::getDataSource()->getModelDataRequest(ModelDataRequest::$SELECT_REQUEST,$model);
	        foreach($modelEditorDescriptor->getId() as $key_element=>$value_element){
	            $query=$query->addConditionBySymbol('=',$model->getField($key_element), $value_element);
	        }
	        $element=	$query->getModelDataElement(true);
	    }
	    return array("simple"=>$element);
	}
}
