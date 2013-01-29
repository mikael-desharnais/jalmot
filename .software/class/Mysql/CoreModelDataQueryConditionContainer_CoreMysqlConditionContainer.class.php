<?php
/**
* Mysql Implementation of the ModelDataQueryConditionContainer
*/
class CoreMysqlConditionContainer extends CoreModelDataQueryConditionContainer{
	/**
	* Returns the string query corresponding to this ConditionContainer in Mysql Language
	* @return string the string query corresponding to this ConditionContainer in Mysql Language
	*/
	public function getSQL(){
		$toReturn="";
		$conditions=$this->getConditions();
		$containers=$this->getContainers();
		foreach($containers as $containerElement){
			$toReturn.=($toReturn==""?"":" ".$this->getSeparator()." ")."(".$containerElement->getSQL().")";
		}
		foreach($conditions as $condition){
        	$toReturn.=($toReturn==""?"":" ".$this->getSeparator()." ").$condition->getSQL();
		}
		return $toReturn;
	}
	/**
	* returns the proper Mysql separator for this Container (either AND or OR)
	* @return string the proper Mysql separator for this Container (either AND or OR)
	*/
	public function getSeparator(){
		if ($this->getType()==self::$MODEL_DATA_QUERY_CONDITION_CONTAINER_AND){
			return " AND ";
		}else {
			return " OR ";
		}
	}
}


