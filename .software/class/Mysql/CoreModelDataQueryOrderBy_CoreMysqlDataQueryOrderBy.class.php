<?php
/**
* Mysql Implementation of ModelDataQueryOrderBy
*/
class CoreMysqlDataQueryOrderBy extends ModelDataQueryOrderBy{
	public function toSQL($DataQuery){
	    $toReturn="";
	    if ($this->field instanceof ModelField){
	        $toReturn.=" ". $DataQuery->getModel()->getDataSource()->getDbFieldName($DataQuery->getModel()->getName(),$this->field->getName())." ";
	    }elseif ($this->field instanceof Date) {
	        $toReturn.=" ".DateMysqlModelType::toSQL($this->field)." ";
	    }else {
	        $toReturn.=" '".$this->field."' ";
	    }
	    $toReturn.=" ".($this->order_type==ModelDataQueryOrderBy::$ORDER_ASC?"ASC":"DESC");
	    return $toReturn;
	}
}



