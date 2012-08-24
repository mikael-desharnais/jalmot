<?php
/**
* This class works as a container for conditions in a DataQuery.
* It can be seen as brackets in a text query.
* A condition container contains other conditions and other condition containers.
* A condition container has a type : either OR or AND.
* Example : 
* If the type is AND, the serialisation of this condition container will be [conditionContainer[1] AND ... AND conditionContainer[n] AND condition[1] AND ... AND condition[n]]
*/
abstract class CoreModelDataQueryConditionContainer{
	/**
	* Type type of this Condition Container
	* Value taken from the Class Constants : $MODEL_DATA_QUERY_CONDITION_CONTAINER_AND or $MODEL_DATA_QUERY_CONDITION_CONTAINER_OR
	*/
	private $type;
	/**
	* Class constant for a type AND condition container
	*/
	public static $MODEL_DATA_QUERY_CONDITION_CONTAINER_AND = 1;
	/**
	* Class constant for a type OR condition container
	*/
	public static $MODEL_DATA_QUERY_CONDITION_CONTAINER_OR = 0;
	/**
	* List of all conditions contained by this ConditionContainer
	*/
	private $conditions = array();
	/**
	* The parent DataQuery of this Condition container
	*/
	private $DataQuery;
	/**
	* List of all ConditionContainers contained by this ConditionContainer
	*/
	private $conditionContainers = array();	
	/**
	* Initialises the type of the ConditionContainer
	* @param integer $type the type of the conditionContainer
	*/
	public function __construct($type){
		$this->type=$type;
	}
	/**
	* Defines the ModelDataQuery that contains this conditionContainer
	* @param ModelDataQuery  $DataQuery  the ModelDataQuery that contains this conditionContainer
	*/
	public function setDataQuery($DataQuery){
	    $this->DataQuery=$DataQuery;
	}
	/**
	* Returns  the DataQuery that contains this conditionContainer
	* @return ModelDataQuery  the ModelDataQuery that contains this conditionContainer
	*/
	public function getDataQuery(){
	    return $this->DataQuery;
	}
	/**
	* Adds a condition to this ConditionContainer
	* @param ModelDataQueryCondition $condition The condition to add to this ConditionContainer
	*/
	public function addCondition($condition){
	    $condition->setParentConditionContainer($this);
		$this->conditions[]=$condition;
	}
	/**
	* Adds a ConditionContainer to this ConditionContainer
	* @param ModelDataQueryConditionContainer $conditionContainer The ConditionContainer to add to this ConditionContainer
	*/
	public function addConditionContainer($conditionContainer){
	    $conditionContainer->setDataQuery($this->DataQuery);
		$this->conditionContainers[]=$conditionContainer;
	}
	/**
	* Returns the list of all conditions of this ConditionContainer
	* @return array the list of all conditions of this ConditionContainer
	*/
	public function getConditions(){
		return $this->conditions;
	}
	/**
	* Returns the list of all ConditionContainers of this ConditionContainer
	* @return array the list of all ConditionContainers of this ConditionContainer
	*/
	public function getContainers(){
		return $this->conditionContainers;
	}
	/**
	* Returns the type of this ConditionContainer
	* @return integer the type of this ConditionContainer
	*/
	public function getType(){
		return $this->type;
	}
	/**
	* Returns the DataSource that contains this conditionContainer
	* @return DataSource the DataSource that contains this conditionContainer
	*/
	protected function getDataSource(){
	    return $this->dataSource;
	}
	/**
	* Adds a condition to this query (The condition to add is defined by its symbol and the given parameters (See the different conditions and their symbols))
	* @return ModelDataQuery this
	* @param string $symbol Symbol of the condition to create and Add
	*/
	public function addConditionBySymbol($symbol){
		$this->addCondition(call_user_func(array($this->DataQuery->getDataSource(),"getConditionBySymbol"),func_get_args()));
		return $this;
	}
}


