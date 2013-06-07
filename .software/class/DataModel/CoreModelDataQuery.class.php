<?php
/**
* Query Object to fetch DataModels
*/
abstract class CoreModelDataQuery{
    /**
    * Class Constant to use as type for a ModelDataQuery
    * Query Type : Select
    */
    public static $SELECT_QUERY=1;
    /**
    * Class Constant to use as type for a ModelDataQuery
    * Query Type : Update
    */
    public static $UPDATE_QUERY=2;
    /**
    * Class Constant to use as type for a ModelDataQuery
    * Query Type : Insert
    */
    public static $INSERT_QUERY=3;
    /**
    * Base condition container of this Query
    * By default, it should be a AND conditionContainer
    */
    protected $conditionContainer;
    
    /**
     * List of all Order Bys
     */
    protected $orderBy = array();
    
    /**
    * The model which is the target of this query
    */
    private $model;

    /**
    * The type of this query
    */
    private $type;
    /**
    * The start point of the fetching
    */
    protected $startPoint=0;
    /**
    * The size of the data fetched
    */
    protected $sizeLimit=-1;
    /**
    * Initialises the type, model and datasource of this Query
    * Defines the root condition container of this ModelDataQuery using setRootConditionContainer
    * @see ModelDataQuery::setRootConditionContainer()
    * @param integer $type Use a value from class contans : either for select, update, create
    * @param Model $model the model targeted by this query
    */
    public function __construct($type,$model){
        $this->type=$type;
        $this->model=$model;
		$this->setRootConditionContainer();
    }
	/**
	* Sets the default Condition container with a AND condition container
	*/
	public function setRootConditionContainer(){
		$this->conditionContainer=self::getConditionContainerInstance(ModelDataQueryConditionContainer::$MODEL_DATA_QUERY_CONDITION_CONTAINER_AND);
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
    * @return ModelDataQuery this
    * @param ModelDataQueryCondition $condition condition to add to this query
    */
    public function addCondition($condition){
        $this->conditionContainer->addCondition($condition);
        return $this;
    }
	/**
	* Adds a condition to this query (The condition to add is defined by its symbol and the given parameters (See the different conditions and their symbols))
	* @return ModelDataQuery this
	* @param string $symbol Symbol of the condition to create and Add
	*/
	public function addConditionBySymbol($symbol){
		$this->addCondition(call_user_func(array($this->getModel()->getDataSource(),"getConditionBySymbol"),func_get_args()));
		return $this;
	}
	/**
	* Adds an order by
	* @return ModelDataQuery this
	* @param ModelDataQueryOrderBy $orderBy The order By to add
	*/
	public function addOrderBy($orderBy){
		$this->orderBy[]=$orderBy;
		return $this;
	}
	/**
	* Sets the fetching start point (0 by default)
	* @param int $startPoint The start point of the fetching
	* @return ModelDataQuery this
	*/
	public function setStartPoint($startPoint){
		$this->startPoint = $startPoint;
		return $this;
	}
	/**
	* Sets the size of the data fetched
	* @param int $sizeLimit the size of the data fetched
	* @return ModelDataQuery this
	*/
	public function setSizeLimit($sizeLimit){
		$this->sizeLimit = $sizeLimit;
		return $this;
	}
	
	
    /**
    * Adds a condition container to this query
    * @return ModelDataQuery this
    * @param ModelDataQueryConditionContainer $conditionContainer the Condition container to add to this query
    */
    public function addConditionContainer($conditionContainer){
        $this->conditionContainer->addConditionContainer($conditionContainer);
        return  $this->conditionContainer;
    }
    /**
    * Executes the query and returns a Model Data Array containing the result
    * If two identical queries are executed with usecache=true, the second one will not be executed and the resulted will be taken from cache
    * @return array the Model Data Array containing the result
    * @param boolean $useCache=false true to use cache for this query / false otherwise
    */
    public function getModelData($useCache=false){
		if ($useCache&&$this->getModel()->getDataSource()->isDataModelCached($this->getUUID())){
			$toReturn = $this->getModel()->getDataSource()->getCachedDataModel($this->getUUID());
			$toReturn->rewind();
			return $toReturn;
		}else {
    		$array= $this->getModel()->getDataSource()->execute($this);
			if ($useCache){
				$this->getModel()->getDataSource()->registerDataModelForCache($this->getUUID(),$array);
			}
    		return $array;
		}
    }
    /**
    * Executes the query and returns  the first Model Data of the result
    * This method uses cache, the same way as getModelData
    * @see ModelDataQuery::getModelData
    * @return DataModel The first DataModel of the result of the query
    * @param boolean $useCache=false true to use cache for this query / false otherwise
    */
    public function getModelDataElement($useCache=false){
        $result = $this->getModelData($useCache);
        return $result->valid()?$result->current():null;
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
    * @return ModelDataQueryConditionContainer the root condition container of this query
    */
    public function getConditionContainer(){
        return $this->conditionContainer;
    }

}





