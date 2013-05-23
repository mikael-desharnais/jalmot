<?php
/**
* The equal condition object adapted to Mysql
* @see ModelDataQueryEqualCondition
*/
class CoreMysqlLikeCondition extends CoreModelDataQueryLikeCondition{
	/**
	* Returns the SQL query for this equal condition
	* @return string the SQL query for this equal condition
	*/
	public function getSQL(){
		if (is_object($this->val1)&&get_class($this->val1)=='Model'){
			throw new Exception();
		}
        $toReturn="";
        $DataQuery=$this->parentConditionContainer->getDataQuery();
        if ($this->val1 instanceof ModelField){
            $toReturn.=" ". $DataQuery->getModel()->getDataSource()->getDbFieldName($DataQuery->getModel()->getName(),$this->val1->getName())." ";
        }else {
            $toReturn.="  ".StringMysqlModelType::toSQL($this->val1)." ";
        }
        
        $toReturn.=" LIKE ";
        if ($this->val2 instanceof ModelField){
        	$toReturn.=" %".$DataQuery->getModel()->getDataSource()->getDbFieldName($DataQuery->getModel()->getName(),$this->val2->getName())."% ";
        }else {
        	$toReturn.=" ".StringMysqlModelType::toSQL("%".$this->val2."%")." ";
        }
        return $toReturn;
    }
}







