<?php

 class CoreQueryBuilder{
 	public static function __callstatic($name,$args){
 		$action = substr($name,0,3);
 		if ($action=="get"){
 			if (strpos($name,'By')!==false){
	 			$modelName = substr($name,3,strpos($name,'By')-3);
	 			$fieldName = substr($name,strpos($name,'By')+2);
	 			$model = Model::getModel($modelName);
	 			return $model->getDatasource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY, $model)
	 											->addConditionBySymbol('=',$model->getField($fieldName),$args[0]);
 			}else {
 				$modelName = substr($name,3);
	 			$model = Model::getModel($modelName);
	 			return $model->getDatasource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY, $model);
 			}
 		}
 		if ($action=="lst"){
 			$modelName = substr($name,3);
 			$model = Model::getModel($modelName);
 			return $model->getDatasource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY, $model);
 		}
 	}
}
?>
