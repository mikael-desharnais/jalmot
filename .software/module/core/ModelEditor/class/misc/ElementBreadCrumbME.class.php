<?php

class ElementBreadCrumbME {
    protected $modelName;
	protected $title;
	protected $modelData;
	
	public function __construct($modelName,$title,$modelData){
		$this->modelName=$modelName;
		$this->title=$title;
		$this->modelData = $modelData;
	}
	public function getModelName(){
		return $this->modelName;
	}
	public function getTitle(){
		return $this->title;
	}
	public function getModelData(){
		return $this->modelData;
	}
	public function toHTML(){
		ob_start();
		include(Resource::getCurrentTemplate()->getURL("html/module/ModelEditor/ElementBreadCrumbTitleME.phtml"));
		return ob_get_clean();
	}
}
