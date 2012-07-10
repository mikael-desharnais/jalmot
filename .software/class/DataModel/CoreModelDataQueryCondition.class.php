<?php
/**
* This class describes a condition for a ModelDataQuery
*/
abstract class CoreModelDataQueryCondition{
    /**
    * The conditionContainer that contains this COndition
    */
    protected $parentConditionContainer;
    /**
    * Defines the Condition Container that contains this condition
    * @param ModelDataQueryConditionContainer $parentConditionContainer the Condition Container that contains this condition
    */
    public function setParentConditionContainer($parentConditionContainer){
        $this->parentConditionContainer=$parentConditionContainer;
    }
    
    /**
     * Returns the SQL query for this equal condition
     * @return string the SQL query for this equal condition
     */
    public abstract function getSQL();
	
}



