<?php

class CoreModelDataCollection implements Iterator {
    private $content = array();
    private $position =0;
    public function current(){
        return $this->content[$this->position];
    }
    public function key (){
        return $this->position;
    }
    public function next (){
        ++$this->position;
    }
    public function rewind (){
        $this->position = 0;
    }
    public function valid (){
        return isset($this->content[$this->position]);
    }
    public function addModelData($dataModel){
    	   $this->content[]=$dataModel;
    }
    public function merge($ModelDataCollection){
        $this->content = array_merge($this->content,$ModelDataCollection->getContent());
        return $this;
    }
    public function getContent(){
        return $this->content;
    }
    public function getSize(){
    	return count($this->content);
    }
    public function __construct($model){
    	$this->model = $model;
    }
	public function __call($name,$arguments){
	    $action=substr($name,0,3);
	    $fieldName=strtolower(substr($name,3));
	    if ($action=="lst"){
	    	$destinationModel = $this->model->getRelation($fieldName)->getDestination()->getModel();
	    	$source_field_getter="get".ucFirst($this->model->getRelation($fieldName)->getSource()->getName());
	    	$query = $destinationModel->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$destinationModel);

	    	if (count($this->content)>0){
	    		$conditionContainer = $query->getConditionContainerInstance(ModelDataQueryConditionContainer::$MODEL_DATA_QUERY_CONDITION_CONTAINER_OR);
	    		$query->addConditionContainer($conditionContainer);
		    	foreach($this as $element){
		    		$conditionContainer->addConditionBySymbol('=',$this->model->getRelation($fieldName)->getDestination(),$element->$source_field_getter());
		    	}
	    	}else  {
	    		$query->addConditionBySymbol('=',1,2);
	    	}
	    	
	        return $query;
	    }
	    
	}
}

