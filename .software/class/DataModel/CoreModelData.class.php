<?php 
class CoreModelData{
    
    public static $SOURCE_FROM_DATASOURCE=0;
    public static $SOURCE_NEW=1;
    
    private $__parent_model;
	public $data_source;
	public $source;
	public $beforeUpdateListenerList=array();
	public $afterUpdateListenerList=array();
	public $beforeCreateListenerList=array();
	public $afterCreateListenerList=array();
	public $beforeSaveListenerList=array();
	public $afterSaveListenerList=array();
	public $modelsToChainCreate=array();
	public $modelsToChainUpdate=array();
	public $modelsToChainSave=array();

    public function __construct($pm){
        $this->__parent_model=$pm;
        $this->source=ModelData::$SOURCE_NEW;
    }
	public function __call($name,$arguments){
	    $action=substr($name,0,3);
	    $fieldName=strtolower(substr($name,3));
	    if ($action=="get"){
	        return $this->$fieldName;
	    }else if ($action=="set"){
	        $value=$arguments[0];
	        ModelType::getType($this->__parent_model->getField($fieldName)->getType())->checkValue($value);
	        $this->$fieldName=$value;
	    }else if ($action=="lst"){
	        $model=$this->__parent_model->getRelation($fieldName)->getDestination()->getModel();
	        $source_field=strtolower($this->__parent_model->getRelation($fieldName)->getSource()->getName());
	        $source_field_value=$this->$source_field;
	        return $this->data_source->getModelDataRequest(ModelDataRequest::$SELECT_REQUEST,$model)->addConditionBySymbol('=',$this->__parent_model->getRelation($fieldName)->getDestination(),$source_field_value);
	    }
	    
	}
	public function update(){
	    $this->propagateBeforeUpdate();
	    $this->data_source->update($this);
	    $this->propagateAfterUpdate();
	    $this->chainUpdate();
	}
	public function create(){
	    $this->propagateBeforeCreate();
	    $this->data_source->create($this);
	    $this->propagateAfterCreate();
	    $this->chainCreate();
	}
	public function save(){
	    $this->propagateBeforeSave();
	    $this->source==ModelData::$SOURCE_NEW?$this->create():$this->update();
	    $this->propagateAfterSave();
	    $this->chainSave();
	}
	public function delete(){
	    $this->data_source->delete($this);
	    $relations=$this->getParentModel()->getRelations();
	    foreach($relations as $relation){
	        if ($relation->getType()=='CascadeOnDelete'){
	            $functionName='lst'.ucfirst($relation->getName());
	            $queryResult=$this->$functionName()->getModelData();
	            foreach($queryResult as $line){
	                $line->delete();
	            }
	        }
	    }
	}
	public function getParentModel(){
		return $this->__parent_model;
	}
	public function getPrimaryKeys(){
		$toReturn=array();
		$fields=$this->__parent_model->getFields();
		foreach($fields as $field){
			if ($field->isPrimaryKey()){
				$toReturn[$field->getName()]=$this->{strtolower($field->getName())};
			}
		}
		return $toReturn;
	}
	public function addBeforeUpdateListener($listener){
	    if (!in_array($listener,$this->beforeUpdateListenerList)){
	        $this->beforeUpdateListenerList[]=$listener;
	    }
	}
	public function addAfterUpdateListener($listener){
	    if (!in_array($listener,$this->afterUpdateListenerList)){
	        $this->afterUpdateListenerList[]=$listener;
	    }
	}
	public function addBeforeSaveListener($listener){
	    if (!in_array($listener,$this->beforeSaveListenerList)){
	        $this->beforeSaveListenerList[]=$listener;
	    }
	}
	public function addAfterSaveListener($listener){
	    if (!in_array($listener,$this->afterSaveListenerList)){
	        $this->afterSaveListenerList[]=$listener;
	    }
	}
	public function addBeforeCreateListener($listener){
	    if (!in_array($listener,$this->beforeCreateListenerList)){
	        $this->beforeCreateListenerList[]=$listener;
	    }
	}
	public function addAfterCreateListener($listener){
	    if (!in_array($listener,$this->afterCreateListenerList)){
	        $this->afterCreateListenerList[]=$listener;
	    }
	}
	public function propagateBeforeUpdate(){
	    foreach($this->beforeUpdateListenerList as $listener){
	        $functionToExecute=$listener->beforeUpdatePerformed;
	        $functionToExecute($this,$listener->getListeningObject());
	    }
	}
	public function propagateAfterUpdate(){
	    foreach($this->afterUpdateListenerList as $listener){
	        $functionToExecute=$listener->afterUpdatePerformed;
	        $functionToExecute($this,$listener->getListeningObject());
	    }
	}
	public function propagateBeforeSave(){
	    foreach($this->beforeSaveListenerList as $listener){
	        $functionToExecute=$listener->beforeSavePerformed;
	        $functionToExecute($this,$listener->getListeningObject());
	    }
	}
	public function propagateAfterSave(){
	    foreach($this->afterSaveListenerList as $listener){
	        $functionToExecute=$listener->afterSavePerformed;
	        $functionToExecute($this,$listener->getListeningObject());
	    }
	}
	public function propagateBeforeCreate(){
	    foreach($this->beforeCreateListenerList as $listener){
	        $functionToExecute=$listener->beforeCreatePerformed;
	        $functionToExecute($this,$listener->getListeningObject());
	    }
	}
	public function propagateAfterCreate(){
	    
	    foreach($this->afterCreateListenerList as $listener){
	        $functionToExecute=$listener->afterCreatePerformed;
	        $functionToExecute($this,$listener->getListeningObject());
	    }
	}/*
	public function addModelForChainCreate($relation_model){
		if (!in_array($relation_model,$this->modelsToChainCreate)){
		    $this->modelsToChainCreate[]=$relation_model;
		}
	}
	public function chainCreate(){
	    foreach($this->modelsToChainCreate as $relation_model){
	        $relation_model->create();
	    }
	}
	public function addModelForChainUpdate($relation_model){
		if (!in_array($relation_model,$this->modelsToChainUpdate)){
		    $this->modelsToChainUpdate[]=$relation_model;
		}
	}
	public function chainUpdate(){
	    foreach($this->modelsToChainUpdate as $relation_model){
	        $relation_model->update();
	    }
	}*/
	public function addModelForChainSave($relation_model){
		if (!in_array($relation_model,$this->modelsToChainSave)){
		    $this->modelsToChainSave[]=$relation_model;
		}
	}
	public function chainSave(){
	    foreach($this->modelsToChainSave as $relation_model){
	        $relation_model->save();
	    }
	}
	
	
}

