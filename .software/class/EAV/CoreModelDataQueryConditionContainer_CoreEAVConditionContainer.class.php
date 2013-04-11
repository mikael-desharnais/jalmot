<?php

class CoreEAVConditionContainer extends CoreModelDataQueryConditionContainer{

	public function getSQL(){
		$toReturn="";
		$conditions=$this->getConditions();
		$containers=$this->getContainers();
		foreach($containers as $containerElement){
			$toReturn.=($toReturn==""?"":" ".$this->getSeparator()." ").$containerElement->getSQL();
		}
		foreach($conditions as $condition){
        	$toReturn.=($toReturn==""?"":" ".$this->getSeparator()." ").$condition->getSQL();
		}
		if (count($containers)+count($conditions)==0){
			$toReturn = "(1=1)";
		}
		return "(".$toReturn.")";
	}

	public function getSeparator(){
	}
	public function convertForQuery(ModelDataQuery $query){
		$queryClass = get_class($query);
		$convertedContainer = call_user_func(array($queryClass,"getConditionContainerInstance"),$this->getType());
		$conditions = $this->getConditions();
		$containers = $this->getContainers();
		$convertedContainer->setDataQuery($query);
		foreach($conditions as $condition){
			call_user_func_array(array($convertedContainer,"addConditionBySymbol"),$condition->getArguments());
		}
		foreach($containers as $container){
			$convertedContainer->addConditionContainer($container->convertForQuery($query));
		}
		return $convertedContainer;
		
	}
}


