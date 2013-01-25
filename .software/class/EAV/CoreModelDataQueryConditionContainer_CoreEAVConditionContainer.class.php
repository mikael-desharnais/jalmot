<?php

class CoreEAVConditionContainer extends CoreModelDataQueryConditionContainer{

	public function getSQL(){
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


