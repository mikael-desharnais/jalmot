<?php
/**
* This class describes an equal condition for a ModelDataQuery
* This condition uses two values that can be either ModelFields ,string or integer
*/
abstract class CoreModelDataQueryBetweenCondition{
    /**
    * The two values of this Condition
    */
    protected $val1,$val2,$compared;
    /**
    * The conditionContainer that contains this COndition
    */
    protected $parentConditionContainer;
    /**
    * Initialises the two values
    * the serialisation is val1=val2
    * @param mixed $val1 The first value 
    * @param mixed $val2 The second value
    */
    public function __construct($compared,$val1,$val2){
        $this->compared=$compared;
        $this->val1=$val1;
        $this->val2=$val2;
    }
    /**
    * Defines the Condition Container that contains this condition
    * @param ModelDataQueryConditionContainer $parentConditionContainer the Condition Container that contains this condition
    */
    public function setParentConditionContainer($parentConditionContainer){
        $this->parentConditionContainer=$parentConditionContainer;
    }
	
}



