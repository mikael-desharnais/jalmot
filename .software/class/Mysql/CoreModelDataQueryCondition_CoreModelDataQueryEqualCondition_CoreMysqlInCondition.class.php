<?php

class CoreMysqlInCondition extends ModelDataQueryEqualCondition{
	
	public function getSQL(){
        $toReturn="";
        $dataQuery=$this->parentConditionContainer->getDataQuery();
        
        if (!is_array($this->val2)||count($this->val2)==0){
        	return "1=2";
        }

        $DataQuery=$this->parentConditionContainer->getDataQuery();
        
        if ($this->val1 instanceof ModelField){
        	$toReturn.=" ". $DataQuery->getModel()->getDataSource()->getDbFieldName($DataQuery->getModel()->getName(),$this->val1->getName())." ";
        }elseif (is_numeric($this->val1)) {
        	$toReturn.=" ".$this->val1." ";
        }else {
        	$toReturn.=" ".StringMysqlModelType::toSQL($this->val1)." ";
        }
        
        $toReturn.=" IN ('".implode("','",$this->val2)."')";
        
        
        return $toReturn;
    }
}







