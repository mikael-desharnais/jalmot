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

    public function __construct($name){
        parent::__construct($name);
        $this->modelDataClass="EAVModelData";
    }
    public function getField($name){
    	if ($this->fieldExists($name)){
    		return parent::getField($name);
    	}else {
    		return $this->baseModel->getField($name);
    	}
    }
	public function setEAVRelationName($eAVRelationName){
		$this->eAVRelationName = $eAVRelationName;
	}
	public function getEAVRelationName(){
		return $this->eAVRelationName;
	}
	public function setBaseModel($baseModel){
		$this->baseModel = $baseModel;
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

