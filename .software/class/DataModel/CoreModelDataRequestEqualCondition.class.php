<?php 
abstract class CoreModelDataRequestEqualCondition{
    protected $val1,$val2;
    
    protected $parentConditionContainer;
    
    public function __construct($val1,$val2){
        $this->val1=$val1;
        $this->val2=$val2;
    }
    public function setParentConditionContainer($parentConditionContainer){
        $this->parentConditionContainer=$parentConditionContainer;
    }
	
}

