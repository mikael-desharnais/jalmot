<?php

class QueryOrderByML extends OrderByML {
    public static function readFromXML($class,$xml){
    	return new $class((string)$xml->field,(string)$xml->direction);
    }
    
    private $fieldName;
    private $direction;
    
    public function __construct($fieldName,$direction){
    	$this->fieldName=$fieldName;
    	$this->direction = $direction;
    }
    public function getType(){
    	return self::$ModelOrderBy;
    }
    public function apply($query){
    	$query->addOrderBy($query->getModel()->getDataSource()->getOrderBy($query->getModel()->getField($this->fieldName),$this->direction));
    }
	
}
