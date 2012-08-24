<?php
/**
* The equal condition object adapted to Mysql
* @see ModelDataQueryEqualCondition
*/
class CoreMysqlBetweenCondition extends CoreModelDataQueryBetweenCondition{
	/**
	* Returns the SQL query for this equal condition
	* @return string the SQL query for this equal condition
	*/
	public function getSQL(){
        $toReturn="";
        $DataQuery=$this->parentConditionContainer->getDataQuery();
        if ($this->compared instanceof ModelField){
            $toReturn.=" ". $DataQuery->getDataSource()->getDbFieldName($DataQuery->getModel()->getName(),$this->compared->getName())." ";
        }elseif ($this->compared instanceof Date) {
            $toReturn.=" ".DateMysqlModelType::toSQL($this->compared)." ";
        }else {
            $toReturn.=" '".$this->compared."' ";
        }
        $toReturn.=" BETWEEN ";
        if ($this->val1 instanceof ModelField){
            $toReturn.=" ". $DataQuery->getDataSource()->getDbFieldName($DataQuery->getModel()->getName(),$this->val1->getName())." ";
        }elseif ($this->val1 instanceof Date) {
            $toReturn.=" ".DateMysqlModelType::toSQL($this->val1)." ";
        }else {
            $toReturn.=" '".$this->val1."' ";
        }
        $toReturn.=" AND ";
        if ($this->val2 instanceof ModelField){
            $toReturn.=" ". $DataQuery->getDataSource()->getDbFieldName($DataQuery->getModel()->getName(),$this->val2->getName())." ";
        }elseif ($this->val2 instanceof Date) {
            $toReturn.=" ".DateMysqlModelType::toSQL($this->val2)." ";
        }else {
            $toReturn.=" '".$this->val2."' ";
        }
        return $toReturn;
    }
}







