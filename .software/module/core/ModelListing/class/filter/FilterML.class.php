<?php

class FilterML {
    
	protected $symbol;
	protected $field;
	protected $value;
	
	
    public function __construct($symbol,$field,$value){
        $this->symbol=$symbol;
		$this->field=$field;
		$this->value=$value;
	}
	public function getModelDataQueryCondition($model){
		return  $model->getDataSource()->getConditionBySymbol(array($this->symbol,$model->getField($this->field),$this->value));
	}

	public function getURLParams(){
		return array("id[".$this->field."]"=>$this->value);
	}
	
}