<?php 
class CoreMysqlDataRequest extends ModelDataRequest{
	public static function getConditionContainerInstance($type){
		return new MysqlConditionContainer($type);
	}
	public function setRootConditionContainer(){
		$this->conditionContainer=self::getConditionContainerInstance(ModelDataRequestConditionContainer::$MODEL_DATA_REQUEST_CONDITION_CONTAINER_AND);
		$this->conditionContainer->setDataRequest($this);
	}
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

