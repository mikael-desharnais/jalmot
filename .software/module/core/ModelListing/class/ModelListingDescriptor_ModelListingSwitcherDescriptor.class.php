<?php

class ModelListingSwitcherDescriptor {
	protected $type;
	protected $name;
	protected $model;
	protected $modelListingsChoices;
	protected $modelListing;
	protected $modelListingSelected;

	public function __construct(){
	}
	
	public static function readFromXML($name,$xml){
		$classname=$xml->class."";
		$modelListing=new $classname();
		$modelListing->setType($xml->type."");
		$modelListing->setName($name);
		$modelListing->setModel($xml->model."");
		
		foreach($xml->selectors->children() as $selector){
			$modelListing->addModelListingChoice($selector->value."",$selector->modelListing."");
		}
		return $modelListing;
	}
	
	public function addModelListingChoice($fieldValue,$modelListingName){
		$this->modelListingsChoices[$fieldValue]=$modelListingName;
	}
	
	public function setFieldName($fieldName){
		$this->fieldName=$fieldName;
	}
	public function getFieldName(){
		return $this->fieldName;
	}
	public function setName($name){
		$this->name=$name;
	}
	public function getName(){
		return $this->name;
	}
	public function setType($type){
		$this->type=$type;
	}
	public function getType(){
		return $this->type;
	}
	public function setModel($model){
		$this->model=$model;
	}
	public function getModel(){
		return $this->model;
	}

	public function selectModelListing(){

		//on initialise avec le model par défaut
		$modelToUse=$this->modelListingsChoices['DEFAULT'];

		foreach($this->modelListingsChoices as $right => $modelAssoc){
			if(Resource::getUserSpace()->getCurrentUserSpace()->hasRight($right)){
				$modelToUse=$modelAssoc;
			}
		}
		$xml=XMLDocument::parseFromFile(Resource::getCurrentTemplate()->getFile(new File("xml/module/ModelListing/descriptor",$modelToUse.".xml",false)));
		$this->modelListing=call_user_func(array($xml->class."","readFromXML"),$modelToUse,$xml);
		$this->modelListingSelected=true;
	}
	
	public function __call($method,$parameters){
		if (!$this->modelListingSelected){
			$this->selectModelListing();
		}
		return call_user_func_array(array($this->modelListing,$method),$parameters);
	}
	
}
