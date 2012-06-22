<?php
/**
* Query Object to fetch DataModels
* TODO : Should perhaps be renamed ModelDataQuery
*/
abstract class CoreModelDataRequest{
    /**
    * Class Constant to use as type for a ModelDataRequest
    * Query Type : Select
    */
    public static $SELECT_REQUEST=1;
    /**
    * Class Constant to use as type for a ModelDataRequest
    * Query Type : Update
    */
    public static $UPDATE_REQUEST=2;
    /**
    * Class Constant to use as type for a ModelDataRequest
    * Query Type : Insert
    */
    public static $INSERT_REQUEST=3;
    /**
    * Base condition container of this Query
    * By default, it should be a AND conditionContainer
    */
    protected $conditionContainer;
    /**
    * The model which is the target of this query
    */
    private $model;
    /**
    * The data source to use for this query
    */
    private $dataSource;
    /**
    * The type of this query
    */
    private $type;
    /**
    * Initialises the type, model and datasource of this Query
    * Defines the root condition container of this ModelDataRequest using setRootConditionContainer
    * @see ModelDataRequest::setRootConditionContainer()
    * @param integer $type Use a value from class contans : either for select, update, create
    * @param Model $model the model targeted by this query
    * @param DataSource $data_source The datasource to use for this query
    */
    public function __construct($type,$model,$data_source){
        $this->type=$type;
        $this->model=$model;
        $this->dataSource=$data_source;
		$this->setRootConditionContainer();
    }
	/**
	* Sets the default Condition container with a AND condition container
	*/
	public function setRootConditionContainer(){
		$this->conditionContainer=self::getConditionContainerInstance(ModelDataRequestConditionContainer::$MODEL_DATA_REQUEST_CONDITION_CONTAINER_AND);
	}
	/**
	* returns the UUID of this query
	* @return string the UUID of this query
	*/
	public function getUUID(){
		return md5($this->getSQL());
	}
    /**
    * Adds a condition to this query (In fact to the root condition container)
    * @return ModelDataRequest this
    * @param ModelDataRequestCondition $condition condition to add to this query
    */
    public function addCondition($condition){
        $this->conditionContainer->addCondition($condition);
        return $this;
    }
	/**
	* Adds a condition to this query (The condition to add is defined by its symbol and the given parameters (See the different conditions and their symbols))
	* @return ModelDataRequest this
	* @param string $symbol Symbol of the condition to create and Add
	*/
	public function addConditionBySymbol($symbol){
		$this->addCondition(call_user_func(array($this->dataSource,"getConditionBySymbol"),func_get_args()));
		return $this;
	}
    /**
    * Adds a condition container to this query
    * @return ModelDataRequest this
    * @param ModelDataRequestConditionContainer $conditionContainer the Condition container to add to this query
    */
    public function addConditionContainer($conditionContainer){
        $this->conditionContainer->addConditionContainer($conditionContainer);
        return $this;
    }
    /**
    * Executes the query and returns a Model Data Array containing the result
    * If two identical queries are executed with usecache=true, the second one will not be executed and the resulted will be taken from cache
    * @return array the Model Data Array containing the result
    * @param boolean $useCache=false true to use cache for this query / false otherwise
    */
    public function getModelData($useCache=false){
		if ($useCache&&$this->dataSource->isDataModelCached($this->getUUID())){
			return $this->dataSource->getCachedDataModel($this->getUUID());
		}else {
    		$array= $this->dataSource->execute($this);
			if ($useCache){
				$this->dataSource->registerDataModelForCache($this->getUUID(),$array);
			}
    		return $array;
		}
    }
    /**
    * Executes the query and returns  the first Model Data of the result
    * This method uses cache, the same way as getModelData
    * @see ModelDataRequest::getModelData
    * @return DataModel The first DataModel of the result of the query
    * @param boolean $useCache=false true to use cache for this query / false otherwise
    */
    public function getModelDataElement($useCache=false){
        $array=$this->getModelData($useCache);
        return $array[0];
    }
    /**
    * Returns the Model used for this query
    * @return Model The model used for this query
    */
    public function getModel(){
    	return $this->model;
    }
    /**
    * Returns the type of this query
    * @return integer The type of this query
    */
    public function getType(){
        return $this->type;
    }
    /**
    * Returns the root condition container of this query
    * @return ModelDataRequestConditionContainer the root condition container of this query
    */
    public function getConditionContainer(){
        return $this->conditionContainer;
    }
    /**
    * Returns the Datasource to use for this query
    * @return DataSource the Datasource to use for this query
    */
    public function getDataSource(){
        return $this->dataSource;
    }
}





