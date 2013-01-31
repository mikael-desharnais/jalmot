<?php
class CoreEAVModelData extends CoreModelData{
	
	protected $baseModelData;
	protected $eavContent = array();


	public function __construct($pm){
		parent::__construct($pm);
		$this->setBaseModelData($pm->getBaseModel()->getInstance());
		$eavFields = $pm->getFields();
		$attributeFieldName = $pm->getEAVAttributeField()->getName();
		foreach($eavFields as $field){
			$instance = $pm->getEAVAttributeField()->getModel()->getInstance();
			$destinationField = $pm->getBaseModel()->getRelation($pm->getEAVRelationName())->getDestination();
			$sourceField = $pm->getBaseModel()->getRelation($pm->getEAVRelationName())->getSource();
			$setterMainId = "set".ucFirst($destinationField->getName());
			$getterMainId = "get".ucFirst($sourceField->getName());
			$attributeFieldSetter = "set".ucFirst($attributeFieldName);
			$instance->$attributeFieldSetter($field->getName());
			$this->addEAVContent($field->getName(),$instance);
			$instance->source=ModelData::$SOURCE_NEW;
		}
	}
	
	public function setBaseModelData($baseModelData){
		$this->baseModelData = $baseModelData;
	}
	public function getBaseModelData(){
		return $this->baseModelData;
	}
	public function addEAVContent($name,$content){
		$this->eavContent[strtolower($name)]=$content;
	}
	public function getEAVElements(){
		return $this->eavContent;
	}
	/**
	 * Magic method that catches calls to :
	 *   set[FIELDNAME] : allows to set a value of a field of this DataModel
	 *   get[FIELDNAME] :  returns a value  of a field of this DataModel
	 *   lst[RELATIONNAME] : returns a ModelDataQuery to fetch the elements linked to this DataModel through the Relation
	 * @return Mixed If set : return nothing, If get : returns the value of the field of the this DataModel, if lst : the ModelDataQuery to fetch the elements linked to this DataModel through the Relation
	 * @param string $name name of the method called
	 * @param array $arguments the arguments used to call this method
	 */
	public function __call($name,$arguments){
		$action=substr($name,0,3);
		$fieldName=strtolower(substr($name,3));
		if ($action=="get"){
			if ($this->baseModelData->getParentModel()->fieldExists($fieldName)){
				return call_user_func_array(array($this->baseModelData,$name),$arguments);
			}else if ($this->getParentModel()->fieldExists($fieldName)){
				if (array_key_exists($fieldName, $this->eavContent)) {
					$getter = "get".ucfirst($this->getParentModel()->getEAVContentFieldName());
					return call_user_func_array(array($this->eavContent[$fieldName],$getter),$arguments);
				}
			}
		}else if ($action=="set"){
			if ($this->baseModelData->getParentModel()->fieldExists($fieldName)){
				return call_user_func_array(array($this->baseModelData,$name),$arguments);
			}else if ($this->getParentModel()->fieldExists($fieldName)){
				if (array_key_exists($fieldName, $this->eavContent)) {
					$getter = "set".ucfirst($this->getParentModel()->getEAVContentFieldName());
					return call_user_func_array(array($this->eavContent[$fieldName],$getter),$arguments);
				}
			}
		}else if ($action=="lst"){
			return call_user_func_array(array($this->baseModelData,$name),$arguments);
		}
		 
	}
	public function getPrimaryKeys(){
		return $this->baseModelData->getPrimaryKeys();
	}
	public function __toString(){
		$toReturn=$this->baseModelData->__toString();
		foreach($this->eavContent as $name=>$field){
			$getter = "get".ucFirst($name);
			$toReturn.="Field : ".$name." ".$this->$getter()." |\n";
		}
		return $toReturn;
	}
}

