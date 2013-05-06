<?php

class SimpleDataFetcher {
    protected $model_editor;
	protected $confParams=array();
    
    public function __construct($model_editor){
        $this->model_editor=$model_editor;
	}
	
	public static function readFromXML($model_editor,$xml){
	    $classname=$xml->class."";
	    $toReturn=new $classname($model_editor);
	    $toReturn->setConfParams(XMLParamsReader::read($xml));
	    return $toReturn;
	}
	

	public function fetchData($modelEditorDescriptor){
	    $model=Model::getModel($this->model_editor->getModel());
	    if ($modelEditorDescriptor->getSource()==ModelData::$SOURCE_NEW){
	        $element=$model->getInstance();
	        foreach($modelEditorDescriptor->getId() as $key_element=>$value_element){
	        	$setter = "set".ucfirst($key_element);
	        	$element->$setter($value_element);
	        }
	    }else {
	        $query=$model->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$model);
	        foreach($modelEditorDescriptor->getId() as $key_element=>$value_element){
	            $query=$query->addConditionBySymbol('=',$model->getField($key_element), $value_element);
	        }
	        $element=	$query->getModelDataElement(true);
	    }
	    return array("simple"=>$element);
	}
	public function getConfParams(){
	    return $this->confParams;
	}
	public function setConfParams($confParams){
	    $this->confParams=$confParams;
	}
	public function getConfParam($key){
	    if (array_key_exists($key,$this->confParams)){
	    	return $this->confParams[$key];
	    }else {
	        return "";
	    }
	}
}
