<?php
/**
* Mysql Implementation of ModelDataQuery
*/
class CoreMysqlDataQuery extends ModelDataQuery{
	/**
	* Returns an instance of MysqlConditionContainer of the type given
	* @return MysqlConditionContainer an instance of MysqlConditionContainer of the type given
	* @param integer $type Type of the ConditionContainer required
	*/
	public static function getConditionContainerInstance($type){
		return new MysqlConditionContainer($type);
	}
	/**
	* Defines the Root conditionContainer and defines its parent DataQuery
	*/
	public function setRootConditionContainer(){
		$this->conditionContainer=self::getConditionContainerInstance(ModelDataQueryConditionContainer::$MODEL_DATA_QUERY_CONDITION_CONTAINER_AND);
		$this->conditionContainer->setDataQuery($this);
	}
	/**
	* Returns the SQL source code for this Query
	* @return string  the SQL source code for this Query
	*/
	public function getSQL(){
         if ($this->getType()==ModelDataQuery::$SELECT_QUERY){
         	$query="SELECT * FROM ".$this->getModel()->getDataSource()->getTableName($this->getModel()->getName());
         	$hasWhere=false;
			$where=$this->getConditionContainer()->getSQL();
			if (!empty($where)){
				$query.=" WHERE ".$where;
			}
			if (count($this->orderBy)>0){
			    $query.=" ORDER BY ";
			    $first = true;
			    foreach($this->orderBy as $orderBy){
			        $query.=$orderBy->toSQL($this);
			        if (!$first){
			            $query.=',';
			        }
			        $first = false;
			    }
			}
			if ( $this->sizeLimit!=-1){
			    $query.=" LIMIT ".$this->sizeLimit." ";
			}
			if ($this->startPoint!=0){
			    $query.=" OFFSET ".(int)$this->startPoint." ";
			}
			return $query;
		}
         if ($query->getType()==ModelDataQuery::$UPDATE_QUERY){
		}
         if ($query->getType()==ModelDataQuery::$INSERT_QUERY){
		}
	}
}



