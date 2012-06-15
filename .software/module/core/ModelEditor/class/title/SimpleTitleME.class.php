<?php

class SimpleTitleME {
	protected $model_editor;
	protected $model_lang;
	protected $title_field;
	
    public function __construct($model_editor,$model_lang,$title_field){
        $this->model_editor=$model_editor;
		$this->model_lang=$model_lang;
		$this->title_field=$title_field;
    }
	public function toHTML($dataFetched){
	    $hasTitle=false;
	    if ($this->model_editor->getSource()!=ModelData::$SOURCE_NEW){
	        $element=$dataFetched['simple'];
	        $hasTitle=true;
	    }
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelEditor/SimpleTitleME.phtml"));
		return ob_get_clean();
	}
	public static function readFromXML($model_editor,$xml){
		return new self($model_editor,$xml->model."",$xml->field."");
	}
	
}
