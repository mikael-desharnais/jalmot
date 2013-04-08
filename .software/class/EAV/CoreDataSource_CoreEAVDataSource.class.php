<?php
/**
* EAV Implementation of the DataSource Class
*/
 class CoreEAVDataSource extends DataSource{
 	
 	protected $lastQuery;
 	
     public function getModelDataQuery($type,$model){
         return new EAVDataQuery($type,$model,$this);
     }
	public function execute($query){
		$baseQuery = $query->getModel()->getBaseModel()->getDataSource()->getModelDataQuery($query->getType(),$query->getModel()->getBaseModel());
		$baseQuery->addConditionContainer($query->getConditionContainer()->convertForQuery($baseQuery));
		$result = $query->getModel()->getBaseModel()->getDataSource()->execute($baseQuery);
		$toReturn = new ModelDataCollection($query->getModel());
		foreach($result as $element){
			$eAVModelData = $query->getModel()->getInstance();
			$eAVModelData->source=$element->source;
			$eAVModelData->setBaseModelData($element);
			$attributeFieldName = $query->getModel()->getEAVAttributeField()->getName();
			$attributeFieldGetter = "get".ucFirst($attributeFieldName);
			$relationName = "lst".$query->getModel()->getEAVRelationName();
			$eavContent = $element->$relationName()->getModelData();
			foreach($eavContent as $eavElement){
				$eAVModelData->addEAVContent($eavElement->$attributeFieldGetter(),$eavElement);
			}
			$toReturn->addModelData($eAVModelData);
		}
		$this->lastQuery = $baseQuery;
		return $toReturn;
	}
	public function update($element){
		$element->getBaseModelData()->save();
		$eavElements = $element->getEAVElements();
		foreach($eavElements as $eavElement){
			$destinationField = $element->getBaseModelData()->getParentModel()->getRelation($element->getParentModel()->getEAVRelationName())->getDestination();
			$sourceField = $element->getBaseModelData()->getParentModel()->getRelation($element->getParentModel()->getEAVRelationName())->getSource();
			$setterMainId = "set".ucfirst($destinationField->getName());
			$getterMainId = "get".ucfirst($sourceField->getName());
			$eavElement->$setterMainId($element->$getterMainId());
			$eavElement->save();
		}
	}
	public function delete($element){
		$eavElements = $element->getEAVElements();
		foreach($eavElements as $eavElement){
			$eavElement->delete();
		}
		$element->getBaseModelData()->delete();
	}
	public function create($element){
		$element->getBaseModelData()->save();
		$eavElements = $element->getEAVElements();
		foreach($eavElements as $eavElement){
			$destinationField = $element->getBaseModelData()->getParentModel()->getRelation($element->getParentModel()->getEAVRelationName())->getDestination();
			$sourceField = $element->getBaseModelData()->getParentModel()->getRelation($element->getParentModel()->getEAVRelationName())->getSource();
			$setterMainId = "set".ucfirst($destinationField->getName());
			$getterMainId = "get".ucfirst($sourceField->getName());
			$eavElement->$setterMainId($element->$getterMainId());
			$eavElement->save();
		}
	}
	public function getConditionBySymbol($args){
		return new EAVCondition($args);
	}

	public function getFoundRows(){
		return $this->lastQuery->getFoundRows();
	}
	public function getOrderBy($field,$type){
	}
}


?>
