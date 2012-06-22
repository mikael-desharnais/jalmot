<?php
/**
* The equal condition object adapted to Mysql
* @see ModelDataRequestEqualCondition
*/
class CoreMysqlEqualCondition extends CoreModelDataRequestEqualCondition{
	/**
	* Returns the SQL query for this equal condition
	* @return string the SQL query for this equal condition
	*/
	public function getSQL(){
        $toReturn="";
        $dataRequest=$this->parentConditionContainer->getDataRequest();
        if ($this->val1 instanceof ModelField){
            $toReturn.=" ". $dataRequest->getDataSource()->getDbFieldName($dataRequest->getModel()->getName(),$this->val1->getName())." ";
        }elseif (is_numeric($this->val1)) {
            $toReturn.=" ".$this->val1." ";
        }else {
            $toReturn.=" '".$this->val1."' ";
        }
        $toReturn.=" = ";
        if ($this->val2 instanceof ModelField){
            $toReturn.=" ".$this->val2->getName()." ";
        }elseif (is_numeric($this->val2)) {
            $toReturn.=" ".$this->val2." ";
        }else {
            $toReturn.=" '".$this->val2."' ";
        }
        return $toReturn;
    }
}







