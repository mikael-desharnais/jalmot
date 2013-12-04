<?php

class TabME {
    
	protected $inputs=array();
	protected $title;
	protected $model_editor;
	protected $class;
	protected $displayButtons=true;
	
	public $displaySaveButton = false;
	public $displayCancelButton = false;
	public $displayDeleteButton = false;
	
	public function displayButtons(){
		return $this->displayButtons;
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
	
	public function __construct($class,$model_editor,$title,$displayButtons){
        $this->model_editor=$model_editor;
		$this->title=$title;
		$this->class=$class;
		$this->displayButtons = (bool)$displayButtons;
	}
	public function setInstance($instance){
		$this->instance=$instance;
	}
	public function toHTML($dataFetched){
	    $element=$this->getUsefullData($dataFetched);
		ob_start();
		include(Ressource::getCurrentTemplate()->getFile(new File("html/module/ModelEditor",$this->class.(empty($this->instance)?"":"_".$this->instance).".phtml",false))->toURL());
		return ob_get_clean();
	}
	public function getModelEditor(){
		return $this->model_editor;
	}
	public function addInput($input){
		$this->inputs[]=$input;
	}
	public function getTitle(){
		return $this->title;
	}
	
	public static function readFromXML($model_editor,$xml){
	    $classname=$xml->class."";
	    $toReturn=new $classname($classname,$model_editor,$xml->title."",$xml->displayButtons."");
	    $toReturn->setConfParams(XMLParamsReader::read($xml));
	    if (!empty($instance)){
	    	$toReturn->setInstance($xml->instance."");
	    }
		if (isset($xml->buttons)){
			foreach($xml->buttons->children() as $button){
				$method = "setDisplay".ucfirst((string)$button)."Button";
				$toReturn->$method(true);
			}
		}else {
			$toReturn->setDisplaySaveButton(true);
			$toReturn->setDisplayCancelButton(true);
			$toReturn->setDisplayDeleteButton(true);
		}
		return $toReturn;
	}
	public function getConfParams(){
	    return $this->confParams;
	}
	public function setConfParams($confParams){
	    $this->confParams=$confParams;
	}
	public function getConfParam($key){
	    if (array_key_exists($key,$this->confParams)){
	    	return $this->confParams[$key];
	    }else {
	        return "";
	    }
	}
	public function getInputs(){
		return $this->inputs;
	}
}
