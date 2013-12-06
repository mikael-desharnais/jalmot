<?php

abstract class BreadCrumbTitleME {
	protected $model_editor;
	protected $title;
	protected $relations = array();
	protected $parentElements = array();
	
    public function __construct($model_editor){
        $this->model_editor=$model_editor;
    }
	public function toHTML($dataFetched){
		$this->loadData();
		ob_start();
		include(Resource::getCurrentTemplate()->getURL("html/module/ModelEditor/BreadCrumbTitleME.phtml"));
		return ob_get_clean();
	}
	public function addRelation($relation){
		$this->relations[]=$relation;
	}
	public static function readFromXML($model_editor,$xml){
		$classname = $xml->class."";
		$toReturn = new $classname($model_editor,$xml->title."");
		foreach($xml->parentRelations->children() as $relation){
			$toReturn->addRelation(call_user_func(array($relation->class."","readFromXML"),$relation));
		}
		return $toReturn;
	}
	public function loadRelations($formerElement){
		foreach($this->relations as $relation){
			$parentElement = $relation->getElement($formerElement);
			array_unshift($this->parentElements,$parentElement);
			$formerElement=$parentElement->getModelData();
			Log::GlobalLogData("test", 3);
		}
	}
	public abstract function loadData();
	
}
