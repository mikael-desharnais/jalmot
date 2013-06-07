<?php

class ModelEditorSwitcherDescriptor {

	public static function readFromXML($name,$xml){
	    $classname=$xml->class."";
		$modelEditor=new $classname();
		$modelEditor->setName($name);
		$modelEditor->setType($xml->type."");
		$modelEditor->setModel($xml->model."");
		$modelEditor->setFieldName($xml->fieldName."");
		foreach($xml->selectors->children() as $selector){
			$modelEditor->addModelEditorChoice($selector->value."",$selector->modelEditor."");
		}
		return $modelEditor;
	}
	protected $fieldName;
	protected $modelEditorsChoices=array();
	protected $name;
	protected $source;
	protected $modelEditorSelected;
	protected $modelEditor;
	protected $id;
	
	
	public function setFieldName($fieldName){
		$this->fieldName=$fieldName;
	}
	public function getFieldName(){
		return $this->fieldName;
	}
	public function addModelEditorChoice($fieldValue,$modelEditorName){
		$this->modelEditorsChoices[$fieldValue]=$modelEditorName;
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
	public function setSource($source){
	    $this->source=$source;
	}
	public function getSource(){
	    return $this->source;
	}
	public function __construct(){
	}
	public function getId(){
	    return $this->id;
	}
	public function setId($id){
		$this->id=is_array($id)?$id:array();
		if (count($id)==0&&$this->getSource()==ModelData::$SOURCE_FROM_DATASOURCE){
			Log::Error('No precise Id for Editor on '.$this->model);
		}
	}
	public function selectModelEditor(){
		$modelToUse="";
		$model=Model::getModel($this->getModel());
		if ($this->getSource()==ModelData::$SOURCE_NEW){
			$modelToUse=$this->modelEditorsChoices['CREATE'];
		}else {
			$query=$model->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$model);
			foreach($this->getId() as $key_element=>$value_element){
				$query=$query->addConditionBySymbol('=',$model->getField($key_element), $value_element);
			}
			$element=	$query->getModelDataElement(true);
			$getterName = "get".ucFirst($this->getFieldName());
			if (array_key_exists($element->$getterName()."",$this->modelEditorsChoices)){
				$modelToUse=$this->modelEditorsChoices[$element->$getterName()];
			}else{
				Log::GlobalLogData("Could not find Model Editor ".$this->getModel()." for element ".$this->getFieldName()." with value ".$element->$getterName(), Log::$LOG_LEVEL_INFO);
				$modelToUse=$this->modelEditorsChoices['DEFAULT'];
			}
		}
		$xml=XMLDocument::parseFromFile(Ressource::getCurrentTemplate()->getFile(new File("xml/module/ModelEditor/descriptor",$modelToUse.".xml",false)));
		$this->modelEditor=call_user_func(array($xml->class."","readFromXML"),$modelToUse,$xml);
		$this->modelEditor->setSource($this->getSource());
		$this->modelEditor->setId($this->getId());
		$this->modelEditorSelected=true;
	}
	public function __call($method,$parameters){
		if (!$this->modelEditorSelected){
			$this->selectModelEditor();
		}
		return call_user_func_array(array($this->modelEditor,$method),$parameters);
	}
	public function reloadOnSave(){
		return $this->getSource()==ModelData::$SOURCE_NEW?true:$this->modelEditor->reloadOnSave();
	}
}
