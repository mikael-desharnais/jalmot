<?php
/**
* Mysql Implementation of ModelDataRequest
*/
class CoreMysqlDataRequest extends ModelDataRequest{
	/**
	* Returns an instance of MysqlConditionContainer of the type given
	* @return MysqlConditionContainer an instance of MysqlConditionContainer of the type given
	* @param integer $type Type of the ConditionContainer required
	*/
	public static function getConditionContainerInstance($type){
		return new MysqlConditionContainer($type);
	}
	/**
	* Defines the Root conditionContainer and defines its parent dataRequest
	*/
	public function setRootConditionContainer(){
		$this->conditionContainer=self::getConditionContainerInstance(ModelDataRequestConditionContainer::$MODEL_DATA_REQUEST_CONDITION_CONTAINER_AND);
		$this->conditionContainer->setDataRequest($this);
	}
	/**
	* Returns the SQL source code for this Query
	* @return string  the SQL source code for this Query
	*/
	public function getSQL(){
         if ($this->getType()==ModelDataRequest::$SELECT_REQUEST){
         	$query="SELECT * FROM ".$this->getDataSource()->getTableName($this->getModel()->getName());
         	$hasWhere=false;
			$where=$this->getConditionContainer()->getSQL();
			if (!empty($where)){
				$query.=" WHERE ".$where;
			}
			return $query;
		}
         if ($request->getType()==ModelDataRequest::$UPDATE_REQUEST){
		}
         if ($request->getType()==ModelDataRequest::$INSERT_REQUEST){
		}
	}
}



