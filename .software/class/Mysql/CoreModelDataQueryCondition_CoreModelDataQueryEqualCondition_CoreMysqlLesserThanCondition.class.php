<?php
/**
* The lesser than condition object adapted to Mysql
* @see ModelDataQueryCondition
*/
class CoreMysqlLesserThanCondition extends CoreModelDataQueryEqualCondition{
	/**
	* Returns the SQL query for this lesser than condition
	* @return string the SQL query for this lesser than condition
	*/
	public function getSQL(){
        $toReturn="";
        $DataQuery=$this->parentConditionContainer->getDataQuery();
        if ($this->val1 instanceof ModelField){
            $toReturn.=" ". $DataQuery->getModel()->getDataSource()->getDbFieldName($DataQuery->getModel()->getName(),$this->val1->getName())." ";
        }elseif (is_numeric($this->val1)) {
            $toReturn.=" ".$this->val1." ";
        }elseif ($this->val1 instanceof Date) {
            $toReturn.=" ".DateMysqlModelType::toSQL($this->val1)." ";
        }else {
            $toReturn.=" '".$this->val1."' ";
        }
        $toReturn.=" < ";
        if ($this->val2 instanceof ModelField){
            $toReturn.=" ".$this->val2->getName()." ";
        }elseif ($this->val2 instanceof Date) {
            $toReturn.=" ".DateMysqlModelType::toSQL($this->val2)." ";
        }elseif (is_numeric($this->val2)) {
            $toReturn.=" ".$this->val2." ";
        }else {
            $toReturn.=" '".$this->val2."' ";
        }
        return $toReturn;
    }
}







