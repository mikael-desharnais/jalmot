<?php

class StaticTitleME {
	protected $model_editor;
	protected $title;
	
    public function __construct($model_editor,$title){
        $this->model_editor=$model_editor;
		$this->title=$title;
    }
	public function toHTML($dataFetched){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelEditor/StaticTitleME.phtml"));
		return ob_get_clean();
	}
	public static function readFromXML($model_editor,$xml){
		return new self($model_editor,$xml->title."");
	}
	
}
