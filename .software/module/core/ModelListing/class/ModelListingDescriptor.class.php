<?php

class ModelListingDescriptor {
	public static function readFromXML($xml){
	    $classname=$xml->class."";
		$modelListing=new $classname();
		$modelListing->setType($xml->type."");
		$modelListing->setModel($xml->model."");
		$modelListing->setTitle($xml->title."");
		if (!empty($xml->pageSize)){
		    $modelListing->setPageSize((int)$xml->pageSize);
		}
		foreach($xml->columns->children() as $column){
			$heading=$column->head->class."";
			$body=$column->body->class."";
			$headElement=call_user_func(array($heading,"readFromXML"),$column->head);
			$bodyElement=call_user_func(array($body,"readFromXML"),$column->body);
			$bodyElement->setListing($modelListing);
			$modelListing->addColumn($headElement,$bodyElement);
		}
		foreach($xml->hooks->children() as $hook){
		    Hook::initHookFromXML($hook->name."",$hook);
		}
	    $modelListing->setConfParams(XMLParamsReader::read($xml));
		return $modelListing;
	}

	protected $type;
	protected $columns=array();
	protected $model;
	protected $list;
	protected $title;
	protected $confParams=array();
	protected $filters = array();
	protected $page = 0;
	protected $pageSize = 10;
	protected $originalSize = 0;

	public function __construct(){
	}
	public function setPageSize($size){
	    $this->pageSize = $size;
	}
	public function setType($type){
	    $this->type=$type;
	}
	
	public function getType(){
	    return $this->type;
	}
	public function setTitle($title){
		$this->title=$title;
	}

	public function getTitle(){
		return $this->title;
	}

	public function setModel($model){
		$this->model=$model;
	}

	public function getModel(){
		return $this->model;
	}

	public function addColumn($head,$body){
		$this->columns[]=array('head'=>$head,'body'=>$body);
	}
	public function toHTML(){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/ModelListingDescriptor_".$this->type.".phtml"));
		return ob_get_clean();
	}
	public function addFilter($filter){
	    $this->filters[]=$filter;
	}
	public function fetchData(){
		$model=Model::getModel($this->model);
		$query = $model->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$model);
		foreach($this->filters as $filter){
		    $query->addCondition($filter->getModelDataQueryCondition($model));
		}
		$query->setStartPoint(0+$this->page*$this->pageSize);
		$query->setSizeLimit($this->pageSize);
		$this->list=$query->getModelData();
		$this->originalSize = $query->getFoundRows();
	}
	public function getConfParams(){
	    return $this->confParams;
	}
	public function setConfParams($confParams){
	    $this->confParams=$confParams;
	}
	public function getConfParam($key){
	    return $this->confParams[$key];
	}
	public function setPage($page){
	    $this->page = $page;
	}
}
