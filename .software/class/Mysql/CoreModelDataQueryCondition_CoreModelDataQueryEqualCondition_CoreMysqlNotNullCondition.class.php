<?php
/**
* The lesser than condition object adapted to Mysql
* @see ModelDataQueryCondition
*/
class CoreMysqlNotNullCondition extends ModelDataQueryEqualCondition{

	protected $orEqual = false;
	
	public function __construct($val1, $val2,$orEqual=false){
		parent::__construct($val1, $val2);
		$this->orEqual=$orEqual;
	}
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
            $toReturn.=" ".StringMysqlModelType::toSQL($this->val1)." ";
        }
        $toReturn.=" IS NOT NULL";
        return $toReturn;
    }
}







