<?php

class ModelEditorDescriptor {
	public static function readFromXML($name,$xml){
	    $classname=$xml->class."";
		$modelEditor=new $classname();
		$modelEditor->setName($name);
		$modelEditor->setType($xml->type."");
		$modelEditor->setModel($xml->model."");
		$modelEditor->setReloadOnSave((bool)$xml->reloadOnSave);

		$title_class=$xml->title->class."";
		$modelEditor->setTitle(call_user_func(array($title_class,"readFromXML"),$modelEditor,$xml->title));

		foreach($xml->inputs->children() as $input){
			$input_class=$input->class."";
			$element=call_user_func(array($input_class,"readFromXML"),$modelEditor,$input);
			if (empty($element)){
				throw new Exception('Could not create element '.$input_class);
			}
			$modelEditor->addInput($element);
		}
		
		if (isset($xml->buttons)){
			foreach($xml->buttons->children() as $button){
				$method = "setDisplay".strtoupper((string)$button)."Button";
				$modelEditor->$method(true);
			}
		}else {
			$modelEditor->setDisplaySaveButton(true);
			$modelEditor->setDisplayCancelButton(true);
			$modelEditor->setDisplayDeleteButton(true);
		}
		
		foreach($xml->changeTypes->children() as $changeTypeXML){
		    $modelEditor->addChangeType($changeTypeXML."");
		}
		foreach($xml->hooks->children() as $hook){
		    Hook::initHookFromXML($hook->name."",$hook);
		}
		
		foreach($xml->data_fetchers->children() as $dataFetcher){
		    $classname=$dataFetcher->class."";
			$element=call_user_func(array($classname,"readFromXML"),$modelEditor,$dataFetcher);
		    $modelEditor->addDataFetcher($element);
		}
		return $modelEditor;
	}

	protected $type;
	protected $inputs=array();
	protected $model;
	protected $title;
	protected $id;
	protected $source;
	protected $name;
	protected $reloadOnSave;
	protected $dataFetchers=array();
	protected $fetchedData=array();
	protected $changeTypes = array();

	protected $displaySaveButton = false;
	protected $displayCancelButton = false;
	protected $displayDeleteButton = false;

	public function __construct(){
	}
	public function setDisplaySaveButton($displaySaveButton){
		$this->displaySaveButton=$displaySaveButton;
	}
	public function setDisplayCancelButton($displayCancelButton){
		$this->displayCancelButton=$displayCancelButton;
	}
	public function setDisplayDeleteButton($displayDeleteButton){
		$this->displayDeleteButton=$displayDeleteButton;
	}
	public function setSource($source){
	    $this->source=$source;
	}
	public function getSource(){
	    return $this->source;
	}
	public function setName($name){
	    $this->name=$name;
	}
	public function getName(){
	    return $this->name;
	}
	public function getId(){
	    return $this->id;
	}
	public function addDataFetcher($dataFetcher){
	    $this->dataFetchers[]=$dataFetcher;
	}
	public function getFetchedData(){
	    return $this->fetchedData;
	}
	public function getSerializedId(){
	    if ($this->source==ModelData::$SOURCE_NEW){
	        $concat="create";
	    }else {
			$concat="";
			foreach($this->id as $key_element=>$value_element){
				$concat.=$key_element."=".$value_element."&";
			}
	    }
		return "ModelEditorDescriptor-".md5($this->type.$this->model.$concat);
	}
	public function getURLParams(){
	    if ($this->source==ModelData::$SOURCE_NEW){
	        return array();
	    }else {
			return $this->id;
	    }
	}

	public function getFiltersURLParams(){
		return array('id'=>$this->id);
	}
	public function setId($id){
		$this->id=is_array($id)?$id:array();
		if (count($id)==0&&$this->getSource()==ModelData::$SOURCE_FROM_DATASOURCE){
			Log::Error('No precise Id for Editor on '.$this->model);
		}
	}

	public function setType($type){
		$this->type=$type;
	}
	public function setTitle($title){
		$this->title=$title;
	}
	public function getTitle(){
		return $this->title;
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
	public function addInput($input){
		$this->inputs[]=$input;
	}
	public function toHTML(){
		ob_start();
		include(Resource::getCurrentTemplate()->getFile(new File("html/module/ModelEditor","ModelEditorDescriptor_".$this->type.".phtml",false))->toURL());
		return ob_get_clean();
	}
	public function fetchData(){
	    foreach($this->dataFetchers as $dataFetcher){
	        $this->fetchedData=array_merge($this->fetchedData,$dataFetcher->fetchData($this));
	    }
	    $this->fetchDataToSave();
	}
	
	public function fetchDataToSave(){
		foreach($this->inputs as $input){
		    $input->fetchElementsToSave($this->fetchedData);
		}
	}
	public function save(){
	    $this->fetchedData['simple']->save();
	}
	
	public function delete(){
	    $this->fetchedData['simple']->delete();
	}
	public function wrapFieldName($name){
		return 'ModelEditor['.$name.']';
	}
	public function getParameterContainer(){
		return is_array(Resource::getParameters()->getValue('ModelEditor'))?Resource::getParameters()->getValue('ModelEditor'):array();
	}
	public function reloadOnSave(){
		return $this->reloadOnSave;
	}
	public function setReloadOnSave($reloadOnSave){
		$this->reloadOnSave = $reloadOnSave;
	}
	protected function getChangeTypes(){
		return $this->changeTypes;
	}
	public function addChangeType($changeType){
		$this->changeTypes[]=$changeType;
	}
}
