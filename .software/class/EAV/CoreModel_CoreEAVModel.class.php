<?php
class CoreEAVModel extends Model{
    
    public static function readFromXML($class,$name,$xml){
    	$toReturn = parent::readFromXML($class,$name,$xml);
	    $baseModel = Model::getModel($xml->baseModel."");
	    $eavRelation = $baseModel->getRelation($xml->eavRelation."");
		$toReturn->setBaseModel($baseModel);
		$toReturn->setEAVRelationName($eavRelation->getName());
		$toReturn->setEAVAttributeField($eavRelation->getDestination()->getModel()->getField($xml->eavAttributeField.""));
		$toReturn->setEAVContentFieldName($xml->eavContentField."");
		return $toReturn;
    }
	protected $eAVRelationName;
	protected $baseModel;
	protected $eAVAttributeField;
	protected $eAVContentFieldName;
	
	protected $fieldList = array();

    public function __construct($name){
        parent::__construct($name);
        $this->modelDataClass="EAVModelData";
    }
    public function getField($name){
    	return $this->fieldList[strtolower($name)];
    }
    public function addField($field){
    	parent::addField($field);
    	$fieldClone = clone $field;
    	$fieldClone->setModel($this);
    	$this->fieldList[strtolower($fieldClone->getName())]=$fieldClone;
    }
	public function setEAVRelationName($eAVRelationName){
		$this->eAVRelationName = $eAVRelationName;
	}
	public function getEAVRelationName(){
		return $this->eAVRelationName;
	}
	public function setBaseModel($baseModel){
		$this->baseModel = $baseModel;
		foreach($this->baseModel->getFields() as $field){
	    	$fieldClone = clone $field;
	    	$fieldClone->setModel($this);
	    	$this->fieldList[strtolower($fieldClone->getName())]=$fieldClone;
		}
	}
	public function getBaseModel(){
		return $this->baseModel;
	}
	public function setEAVAttributeField($eAVAttributeField){
		$this->eAVAttributeField = $eAVAttributeField;
	}
	public function getEAVAttributeField(){
		return $this->eAVAttributeField;
	}
	public function setEAVContentFieldName($eAVContentFieldName){
		$this->eAVContentFieldName = $eAVContentFieldName;
	}
	public function getEAVContentFieldName(){
		return $this->eAVContentFieldName;
	}
	public function getRelation($name){
		return $this->baseModel->getRelation($name);
	}
}

