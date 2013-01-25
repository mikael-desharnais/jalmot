<?php
class CoreEAVDataQuery extends ModelDataQuery{
    
    public $foundRows;
    
	public static function getConditionContainerInstance($type){
		return new EAVConditionContainer($type);
	}
	/**
	* Defines the Root conditionContainer and defines its parent DataQuery
	*/
	public function setRootConditionContainer(){
		$this->conditionContainer=self::getConditionContainerInstance(ModelDataQueryConditionContainer::$MODEL_DATA_QUERY_CONDITION_CONTAINER_AND);
		$this->conditionContainer->setDataQuery($this);
	}
	public function getFoundRows(){
	    if ($this->foundRows==null){
	        $this->foundRows=$this->getModel()->getBaseModel()->getDataSource()->getFoundRows();
	    }
	    return $this->foundRows;
	}
	public function getSQL(){
         if ($this->getType()==ModelDataQuery::$SELECT_QUERY){
         	$query="SELECT SQL_CALC_FOUND_ROWS * FROM ".$this->getModel()->getName();
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



