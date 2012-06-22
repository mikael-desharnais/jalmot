<?php
/**
* This class works as a container for conditions in a DataRequest.
* It can be seen as brackets in a text query.
* A condition container contains other conditions and other condition containers.
* A condition container has a type : either OR or AND.
* Example : 
* If the type is AND, the serialisation of this condition container will be [conditionContainer[1] AND ... AND conditionContainer[n] AND condition[1] AND ... AND condition[n]]
*/
abstract class CoreModelDataRequestConditionContainer{
	/**
	* Type type of this Condition Container
	* Value taken from the Class Constants : $MODEL_DATA_REQUEST_CONDITION_CONTAINER_AND or $MODEL_DATA_REQUEST_CONDITION_CONTAINER_OR
	*/
	private $type;
	/**
	* Class constant for a type AND condition container
	*/
	public static $MODEL_DATA_REQUEST_CONDITION_CONTAINER_AND = 1;
	/**
	* Class constant for a type OR condition container
	*/
	public static $MODEL_DATA_REQUEST_CONDITION_CONTAINER_OR = 0;
	/**
	* List of all conditions contained by this ConditionContainer
	*/
	private $conditions = array();
	/**
	* The parent DataRequest of this Condition container
	*/
	private $dataRequest;
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
	* Defines the ModelDataRequest that contains this conditionContainer
	* @param ModelDataRequest  $dataRequest  the ModelDataRequest that contains this conditionContainer
	*/
	public function setDataRequest($dataRequest){
	    $this->dataRequest=$dataRequest;
	}
	/**
	* Returns  the DataRequest that contains this conditionContainer
	* @return ModelDataRequest  the ModelDataRequest that contains this conditionContainer
	*/
	public function getDataRequest(){
	    return $this->dataRequest;
	}
	/**
	* Adds a condition to this ConditionContainer
	* TODO : there should be a class for CoreModelDataRequestCondition
	* @param ModelDataRequestCondition $condition The condition to add to this ConditionContainer
	*/
	public function addCondition($condition){
	    $condition->setParentConditionContainer($this);
		$this->conditions[]=$condition;
	}
	/**
	* Adds a ConditionContainer to this ConditionContainer
	* @param ModelDataRequestConditionContainer $conditionContainer The ConditionContainer to add to this ConditionContainer
	*/
	public function addConditionContainer($conditionContainer){
	    $conditionContainer->setDataRequest($this->dataRequest);
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
}


