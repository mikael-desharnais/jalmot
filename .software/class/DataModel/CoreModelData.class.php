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
    protected $__parent_model;
	/**
	* The source type of this DataModel
	* it should be either one of this Class constants (from database or new element)
	*/
	public $source;
	
	public $uuid;
	
	
	public $changeLog = array();
	
	/** Event listeners */
	
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
	* List of all listeners for the event beforeDelete
	*/
	protected $beforeDeleteListenerList=array();
	/**
	* List of all listeners for the event afterDelete
	*/
	protected $afterDeleteListenerList=array();

	
	/** Static Event Listeners */

	
	/**
	 * List of all listeners for the event beforeUpdate (static mode)
	 */
	protected static $beforeUpdateStaticListenerList=array();
	/**
	 * List of all listeners for the event afterUpdate (static mode)
	 */
	protected static $afterUpdateStaticListenerList=array();
	/**
	 * List of all listeners for the event beforeCreate (static mode)
	 */
	protected static $beforeCreateStaticListenerList=array();
	/**
	 * List of all listeners for the event afterCreate (static mode)
	 */
	protected static $afterCreateStaticListenerList=array();
	/**
	 * List of all listeners for the event beforeSave (static mode)
	 */
	protected static $beforeSaveStaticListenerList=array();
	/**
	 * List of all listeners for the event afterSave (static mode)
	 */
	protected static $afterSaveStaticListenerList=array();
	/**
	 * List of all listeners for the event beforeDelete (static mode)
	 */
	protected static $beforeDeleteStaticListenerList=array();
	/**
	 * List of all listeners for the event afterDelete (static mode)
	 */
	protected static $afterDeleteStaticListenerList=array();
	
	
	
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
	* List of all models to delete after having deleted this ModelData
	*/
	protected $modelsToChainDelete=array();
	
	
	
    /**
    * Initialises the parent model and sets the source to new
    * @param Model $pm The parent model of this DataModel
    */
    public function __construct($pm){
        $this->__parent_model=$pm;
        $this->source=ModelData::$SOURCE_NEW;
        $this->uuid = microtime(true);
    }
    
    public function __wakeup(){
    	$this->__parent_model=Model::getModel($this->__parent_model->getName());
    }
    /**
     * returns an associative array containing only the data of this ModelData
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
	    	if (!property_exists($this,$fieldName)){
	    		throw new Exception('Unknown property '.$fieldName.' in model '.$this->getParentModel()->getName());	
	    	}
	        return $this->$fieldName;
	    }else if ($action=="set"){
	        $value=$arguments[0];
	        ModelType::getType($this->__parent_model->getField($fieldName)->getType())->checkValue($value);
	        if (!array_key_exists($this->__parent_model->getField($fieldName)->getName(), $this->changeLog)&&$this->$fieldName!=$value){
	        	$this->changeLog[$this->__parent_model->getField($fieldName)->getName()]=$this->$fieldName;
	        }
	        $this->$fieldName=$value;
	    }else if ($action=="lst"){
	        $model=$this->__parent_model->getRelation($fieldName)->getDestination()->getModel();
	        $source_field=strtolower($this->__parent_model->getRelation($fieldName)->getSource()->getName());
	        $source_field_value=$this->$source_field;
	        return $model->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$model)->addConditionBySymbol('=',$this->__parent_model->getRelation($fieldName)->getDestination(),$source_field_value);
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
	    $this->propagateBeforeStaticUpdate($this);
	    $this->getParentModel()->getDataSource()->update($this);
	    $this->propagateAfterUpdate();
	    $this->propagateAfterStaticUpdate($this);
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
	    $this->propagateBeforeStaticCreate($this);
	    $this->getParentModel()->getDataSource()->create($this);
	    $this->source=ModelData::$SOURCE_FROM_DATASOURCE;
	    $this->propagateAfterCreate();
	    $this->propagateAfterStaticCreate($this);
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
	    $this->propagateBeforeStaticSave($this);
	    $this->source==ModelData::$SOURCE_NEW?$this->create():$this->update();
	    $this->propagateAfterSave();
	    $this->propagateAfterStaticSave($this);
	    $this->chainSave();
	}
	/**
	* Deletes this DataModel in DB using the DataSource
	* It also deletes the DataModel that are linked to this DataModel through relations with type CascadeOnDelete
	*/
	public function delete(){
	    $this->propagateBeforeDelete();
	    $this->propagateBeforeStaticDelete($this);
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
	    $this->getParentModel()->getDataSource()->delete($this);
	    $this->propagateAfterDelete();
	    $this->propagateAfterStaticDelete($this);
	    $this->chainDelete();
	}
	
	public function startChangeLogging(){
		$this->changeLog=array();
	}
	public function getChangeLog(){
		return $this->changeLog;
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
	* Adds a listener for the Before Delete Event
	* @param EventListener $listener the listener to the Before Delete Event
	*/
	public function addBeforeDeleteListener($listener){
	    if (!in_array($listener,$this->beforeDeleteListenerList)){
	        $this->beforeDeleteListenerList[]=$listener;
	    }
	}
	/**
	* Adds a listener for the After Delete Event
	* @param EventListener $listener the listener to the After Delete Event
	*/
	public function addAfterDeleteListener($listener){
	    if (!in_array($listener,$this->afterDeleteListenerList)){
	        $this->afterDeleteListenerList[]=$listener;
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
	* Triggers the Before Delete Event
	*/
	public function propagateBeforeDelete(){
	    foreach($this->beforeDeleteListenerList as $listener){
	        $functionToExecute=$listener->beforeDeletePerformed;
	        $functionToExecute($this,$listener->getListeningObject());
	    }
	}
	/**
	* Triggers the After Delete Event
	*/
	public function propagateAfterDelete(){
	    
	    foreach($this->afterDeleteListenerList as $listener){
	        $functionToExecute=$listener->afterDeletePerformed;
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
	/**
	* Adds a ModelData for chain delete
	* All the ModelData will be deleted after the deletion of this DataModel
	* @param DataModel $relation_model The ModelData for chain delete
	*/
	public function addModelDataForChainDelete($relation_model){
		if (!in_array($relation_model,$this->modelsToChainDelete)){
		    $this->modelsToChainDelete[]=$relation_model;
		}
	}
	/**
	* Triggers the chain deletion
	*/
	public function chainDelete(){
	    foreach($this->modelsToChainDelete as $relation_model){
	        $relation_model->delete();
	    }
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * Adds a listener for the Before Update Event
	 * @param EventListener $listener the listener to the Before Update Event
	 */
	public static function addBeforeUpdateStaticListener($listener){
	    if (!in_array($listener,self::$beforeUpdateStaticListenerList)){
	        self::$beforeUpdateStaticListenerList[]=$listener;
	    }
	}
	/**
	 * Adds a listener for the After Update Event
	 * @param EventListener $listener the listener to the After Update Event
	 */
	public static function addAfterUpdateStaticListener($listener){
	    if (!in_array($listener,self::$afterUpdateStaticListenerList)){
	        self::$afterUpdateStaticListenerList[]=$listener;
	    }
	}
	/**
	 * Adds a listener for the Before Save Event
	 * @param EventListener $listener the listener to the Before Save Event
	 */
	public static function addBeforeSaveStaticListener($listener){
	    if (!in_array($listener,self::$beforeSaveStaticListenerList)){
	        self::$beforeSaveStaticListenerList[]=$listener;
	    }
	}
	/**
	 * Adds a listener for the After Save Event
	 * @param EventListener $listener the listener to the After Save Event
	 */
	public static function addAfterSaveStaticListener($listener){
	    if (!in_array($listener,self::$afterSaveStaticListenerList)){
	        self::$afterSaveStaticListenerList[]=$listener;
	    }
	}
	/**
	 * Adds a listener for the Before Create Event
	 * @param EventListener $listener the listener to the Before Create Event
	 */
	public static function addBeforeCreateStaticListener($listener){
	    if (!in_array($listener,self::$beforeCreateStaticListenerList)){
	        self::$beforeCreateStaticListenerList[]=$listener;
	    }
	}
	/**
	 * Adds a listener for the After Create Event
	 * @param EventListener $listener the listener to the After Create Event
	 */
	public static function addAfterCreateStaticListener($listener){
	    if (!in_array($listener,self::$afterCreateStaticListenerList)){
	        self::$afterCreateStaticListenerList[]=$listener;
	    }
	}
	/**
	 * Adds a listener for the Before Delete Event
	 * @param EventListener $listener the listener to the Before Delete Event
	 */
	public static function addBeforeDeleteStaticListener($listener){
	    if (!in_array($listener,self::$beforeDeleteStaticListenerList)){
	        self::$beforeDeleteStaticListenerList[]=$listener;
	    }
	}
	/**
	 * Adds a listener for the After Delete Event
	 * @param EventListener $listener the listener to the After Delete Event
	 */
	public static function addAfterDeleteStaticListener($listener){
	    if (!in_array($listener,self::$afterDeleteStaticListenerList)){
	        self::$afterDeleteStaticListenerList[]=$listener;
	    }
	}
	/**
	 * Triggers the Before Update Event
	 */
	public static function propagateBeforeStaticUpdate($element){
	    foreach(self::$beforeUpdateStaticListenerList as $listener){
	        $functionToExecute=$listener->beforeUpdatePerformed;
	        $functionToExecute($element,$listener->getListeningObject());
	    }
	}
	/**
	 * Triggers the After Update Event
	 */
	public static function propagateAfterStaticUpdate($element){
	    foreach(self::$afterUpdateStaticListenerList as $listener){
	        $functionToExecute=$listener->afterUpdatePerformed;
	        $functionToExecute($element,$listener->getListeningObject());
	    }
	}
	/**
	 * Triggers the Before Save Event
	 */
	public static function propagateBeforeStaticSave($element){
	    foreach(self::$beforeSaveStaticListenerList as $listener){
	        $functionToExecute=$listener->beforeSavePerformed;
	        $functionToExecute($element,$listener->getListeningObject());
	    }
	}
	/**
	 * Triggers the After Save Event
	 */
	public static function propagateAfterStaticSave($element){
	    foreach(self::$afterSaveStaticListenerList as $listener){
	        $functionToExecute=$listener->afterSavePerformed;
	        $functionToExecute($element,$listener->getListeningObject());
	    }
	}
	/**
	 * Triggers the Before Create Event
	 */
	public static function propagateBeforeStaticCreate($element){
	    foreach(self::$beforeCreateStaticListenerList as $listener){
	        $functionToExecute=$listener->beforeCreatePerformed;
	        $functionToExecute($element,$listener->getListeningObject());
	    }
	}
	/**
	 * Triggers the After Create Event
	 */
	public static function propagateAfterStaticCreate($element){
	     
	    foreach(self::$afterCreateStaticListenerList as $listener){
	        $functionToExecute=$listener->afterCreatePerformed;
	        $functionToExecute($element,$listener->getListeningObject());
	    }
	}
	/**
	 * Triggers the Before Delete Event
	 */
	public static function propagateBeforeStaticDelete($element){
	    foreach(self::$beforeDeleteStaticListenerList as $listener){
	        $functionToExecute=$listener->beforeDeletePerformed;
	        $functionToExecute($element,$listener->getListeningObject());
	    }
	}
	/**
	 * Triggers the After Delete Event
	 */
	public static function propagateAfterStaticDelete($element){
	     
	    foreach(self::$afterDeleteStaticListenerList as $listener){
	        $functionToExecute=$listener->afterDeletePerformed;
	        $functionToExecute($element,$listener->getListeningObject());
	    }
	}
	public function __toString(){
		$toReturn="ModelData ".$this->uuid." :|\n".
				"	Model : ".$this->getParentModel()->getName()." |\n".
				"	Fields : |\n";
		$fields=$this->getParentModel()->getFields();
		foreach($fields as $field){
			$getter = "get".ucFirst($field->getName());
			$toReturn.="Field : ".$field->getName()." ".$this->$getter()." |\n";
		}
		return $toReturn;
	}
	
}

