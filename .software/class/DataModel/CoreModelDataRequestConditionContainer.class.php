<?php 
abstract class CoreModelDataRequestConditionContainer{
	private $type;
	public static $MODEL_DATA_REQUEST_CONDITION_CONTAINER_AND = 1;
	public static $MODEL_DATA_REQUEST_CONDITION_CONTAINER_OR = 0;

	private $conditions = array();
	private $dataRequest;
	private $conditionContainers = array();	
	
	
	public function __construct($type){
		$this->type=$type;
	}
	public function setDataRequest($dataRequest){
	    $this->dataRequest=$dataRequest;
	}
	public function getDataRequest(){
	    return $this->dataRequest;
	}
	public function addCondition($condition){
	    $condition->setParentConditionContainer($this);
		$this->conditions[]=$condition;
	}
	public function addConditionContainer($conditionContainer){
	    $conditionContainer->setDataRequest($this->dataRequest);
		$this->conditionContainers[]=$conditionContainer;
	}
	public function getConditions(){
		return $this->conditions;
	}
	public function getContainers(){
		return $this->conditionContainers;
	}
	public function getType(){
		return $this->type;
	}
	protected function getDataSource(){
	    return $this->dataSource;
	}
}
