<?php
/**
* Model Data Object 
* A model data is like the row of a Mysql Table
* A ModelData has a source : it is either new or create from a data source.
*/
class CoreModelData{
    /**
    * Constant for Source Type : existing data
    */
    public static $SOURCE_FROM_DATASOURCE=0;
    /**
    * Constant for Source Type : new data
    */
    public static $SOURCE_NEW=1;
    /**
    * The parent model of this ModelData
    */
    private $__parent_model;
	/**
	* The datasource that created this DataModel or that should be used to save it
	* When creating a ModelData, you will have to define the datasource to use.
	*/
	public $data_source;
	/**
	* The source type of this DataModel
	* it should be either one of this Class constants (from database or new element)
	*/
	public $source;
	/**
	* List of all listeners for the event beforeUpdate
	*/
	protected $beforeUpdateListenerList=array();
	/**
	* List of all listeners for the event afterUpdate
	*/
	protected $afterUpdateListenerList=array();
	/**
	* List of all listeners for the event beforeCreate
	*/
	protected $beforeCreateListenerList=array();
	/**
	* List of all listeners for the event afterCreate
	*/
	protected $afterCreateListenerList=array();
	/**
	* List of all listeners for the event beforeSave
	*/
	protected $beforeSaveListenerList=array();
	/**
	* List of all listeners for the event afterSave
	*/
	protected $afterSaveListenerList=array();
	/**
	* List of all models to create after having created this ModelData
	*/
	protected $modelsToChainCreate=array();
	/**
	* List of all models to update after having update this ModelData
	*/
	protected $modelsToChainUpdate=array();
	/**
	* List of all models to save after having saved this ModelData
	*/
	protected $modelsToChainSave=array();
    /**
    * Initialises the parent model and sets the source to new
    * @param Model $pm The parent model of this DataModel
    */
    public function __construct($pm){
        $this->__parent_model=$pm;
        $this->source=ModelData::$SOURCE_NEW;
    }
    /**
     * Initialises the parent model and sets the source to new
     * @return Array returns an associative array containing only the data of this ModelData
     */
    public function toArray(){
        $toReturn = array();
        $fields=$this->__parent_model->getFields();
        foreach($fields as $field){
            $toReturn[$field->getName()]=$this->{strtolower($field->getName())};
        }
        return $toReturn;
    }
	/**
	* Magic method that catches calls to :
	*   set[FIELDNAME] : allows to set a value of a field of this DataModel
	*   get[FIELDNAME] :  returns a value  of a field of this DataModel
	*   lst[RELATIONNAME] : returns a ModelDataQuery to fetch the elements linked to this DataModel through the Relation
	* @return Mixed If set : return nothing, If get : returns the value of the field of the this DataModel, if lst : the ModelDataQuery to fetch the elements linked to this DataModel through the Relation 
	* @param string $name name of the method called
	* @param array $arguments the arguments used to call this method
	*/
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
	        return $this->data_source->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$model)->addConditionBySymbol('=',$this->__parent_model->getRelation($fieldName)->getDestination(),$source_field_value);
	    }
	    
	}
	/**
	* Prefer the use of the Save Method
	* Triggers the before Update Event
	* Updates the Data Model using the Data source
	* Triggers the after Update Event
	* Updates the other DataModel which required to be updated after the update of this element
	*/
	public function update(){
	    $this->propagateBeforeUpdate();
	    $this->data_source->update($this);
	    $this->propagateAfterUpdate();
	    $this->chainUpdate();
	}
	/**
	* Prefer the use of the Save Method
	* Triggers the before Create Event
	* Creates the Data Model using the Data source
	* Triggers the after Create Event
	* Creates the other DataModel which required to be created after the creation of this element
	*/
	public function create(){
	    $this->propagateBeforeCreate();
	    $this->data_source->create($this);
	    $this->propagateAfterCreate();
	    $this->chainCreate();
	}
	/**
	* Triggers the before Save Event
	* Updates or Creates according to the source type using the Data source (New => create , From data source => update)
	* Triggers the after Save Event
	* Saves the other DataModel which required to be saved after the save of this element
	*/
	public function save(){
	    $this->propagateBeforeSave();
	    $this->source==ModelData::$SOURCE_NEW?$this->create():$this->update();
	    $this->propagateAfterSave();
	    $this->chainSave();
	}
	/**
	* Deletes this DataModel in DB using the DataSource
	* It also deletes the DataModel that are linked to this DataModel through relations with type CascadeOnDelete
	*/
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
	/**
	* Return the parent Model of this ModelData
	* @return Model the parent Model of this ModelData
	*/
	public function getParentModel(){
		return $this->__parent_model;
	}
	/**
	* Returns the values of the primary keys of this DataModel
	* @return array Array with keys equals to field names and values equals to values for this DataModel
	*/
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
	/**
	* Adds a listener for the Before Update Event
	* @param EventListener $listener the listener to the Before Update Event
	*/
	public function addBeforeUpdateListener($listener){
	    if (!in_array($listener,$this->beforeUpdateListenerList)){
	        $this->beforeUpdateListenerList[]=$listener;
	    }
	}
	/**
	* Adds a listener for the After Update Event
	* @param EventListener $listener the listener to the After Update Event
	*/
	public function addAfterUpdateListener($listener){
	    if (!in_array($listener,$this->afterUpdateListenerList)){
	        $this->afterUpdateListenerList[]=$listener;
	    }
	}
	/**
	* Adds a listener for the Before Save Event
	* @param EventListener $listener the listener to the Before Save Event
	*/
	public function addBeforeSaveListener($listener){
	    if (!in_array($listener,$this->beforeSaveListenerList)){
	        $this->beforeSaveListenerList[]=$listener;
	    }
	}
	/**
	* Adds a listener for the After Save Event
	* @param EventListener $listener the listener to the After Save Event
	*/
	public function addAfterSaveListener($listener){
	    if (!in_array($listener,$this->afterSaveListenerList)){
	        $this->afterSaveListenerList[]=$listener;
	    }
	}
	/**
	* Adds a listener for the Before Create Event
	* @param EventListener $listener the listener to the Before Create Event
	*/
	public function addBeforeCreateListener($listener){
	    if (!in_array($listener,$this->beforeCreateListenerList)){
	        $this->beforeCreateListenerList[]=$listener;
	    }
	}
	/**
	* Adds a listener for the After Create Event
	* @param EventListener $listener the listener to the After Create Event
	*/
	public function addAfterCreateListener($listener){
	    if (!in_array($listener,$this->afterCreateListenerList)){
	        $this->afterCreateListenerList[]=$listener;
	    }
	}
	/**
	* Triggers the Before Update Event
	*/
	public function propagateBeforeUpdate(){
	    foreach($this->beforeUpdateListenerList as $listener){
	        $functionToExecute=$listener->beforeUpdatePerformed;
	        $functionToExecute($this,$listener->getListeningObject());
	    }
	}
	/**
	* Triggers the After Update Event
	*/
	public function propagateAfterUpdate(){
	    foreach($this->afterUpdateListenerList as $listener){
	        $functionToExecute=$listener->afterUpdatePerformed;
	        $functionToExecute($this,$listener->getListeningObject());
	    }
	}
	/**
	* Triggers the Before Save Event
	*/
	public function propagateBeforeSave(){
	    foreach($this->beforeSaveListenerList as $listener){
	        $functionToExecute=$listener->beforeSavePerformed;
	        $functionToExecute($this,$listener->getListeningObject());
	    }
	}
	/**
	* Triggers the After Save Event
	*/
	public function propagateAfterSave(){
	    foreach($this->afterSaveListenerList as $listener){
	        $functionToExecute=$listener->afterSavePerformed;
	        $functionToExecute($this,$listener->getListeningObject());
	    }
	}
	/**
	* Triggers the Before Create Event
	*/
	public function propagateBeforeCreate(){
	    foreach($this->beforeCreateListenerList as $listener){
	        $functionToExecute=$listener->beforeCreatePerformed;
	        $functionToExecute($this,$listener->getListeningObject());
	    }
	}
	/**
	* Triggers the After Create Event
	*/
	public function propagateAfterCreate(){
	    
	    foreach($this->afterCreateListenerList as $listener){
	        $functionToExecute=$listener->afterCreatePerformed;
	        $functionToExecute($this,$listener->getListeningObject());
	    }
	}
	/**
	* Adds a ModelData for chain create
	* All the ModelData will be created after the creation of this DataModel
	* @param DataModel $relation_model The ModelData for chain create
	*/
	public function addModelDataForChainCreate($relation_model){
		if (!in_array($relation_model,$this->modelsToChainCreate)){
		    $this->modelsToChainCreate[]=$relation_model;
		}
	}
	/**
	* Triggers the chain creation
	*/
	public function chainCreate(){
	    foreach($this->modelsToChainCreate as $relation_model){
	        $relation_model->create();
	    }
	}
	/**
	* Adds a ModelData for chain Update
	* All the ModelData will be updated after the update of this DataModel
	* @param DataModel $relation_model The ModelData for chain create
	*/
	public function addModelDataForChainUpdate($relation_model){
		if (!in_array($relation_model,$this->modelsToChainUpdate)){
		    $this->modelsToChainUpdate[]=$relation_model;
		}
	}
	/**
	* Triggers the chain update
	*/
	public function chainUpdate(){
	    foreach($this->modelsToChainUpdate as $relation_model){
	        $relation_model->update();
	    }
	}
	/**
	* Adds a ModelData for chain Save
	* All the ModelData will be saved after the save of this DataModel
	* @param DataModel $relation_model The ModelData for chain save
	*/
	public function addModelDataForChainSave($relation_model){
		if (!in_array($relation_model,$this->modelsToChainSave)){
		    $this->modelsToChainSave[]=$relation_model;
		}
	}
	/**
	* Triggers the chain Save
	*/
	public function chainSave(){
	    foreach($this->modelsToChainSave as $relation_model){
	        $relation_model->save();
	    }
	}
	
	
}

