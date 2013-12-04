<?php

class TabModelEditorDescriptor extends ModelEditorDescriptor {
	public static function readFromXML($name,$xml){
	    $classname=$xml->class."";
		$modelEditor=new $classname();
		$modelEditor->setName($name);
		$modelEditor->setType($xml->type."");
		$modelEditor->setModel($xml->model."");
		$modelEditor->setReloadOnSave((bool)$xml->reloadOnSave);

		$title_class=$xml->title->class."";
		$modelEditor->setTitle(call_user_func(array($title_class,"readFromXML"),$modelEditor,$xml->title));


		foreach($xml->changeTypes->children() as $changeTypeXML){
			$modelEditor->addChangeType($changeTypeXML."");
		}
		foreach($xml->tabs->children() as $tab){
			$tab_class=$tab->class."";
			$tabElement=call_user_func(array($tab_class,"readFromXML"),$modelEditor,$tab);
			$modelEditor->addTab($tabElement);
			foreach($tab->inputs->children() as $input){
				$input_class=$input->class."";
				$element=call_user_func(array($input_class,"readFromXML"),$modelEditor,$input);
				$tabElement->addInput($element);
				$modelEditor->addInput($element);
			}
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

	protected $tabs;
	
	public function addTab($tab){
		$this->tabs[]=$tab;
	}
	
	public function toHTML(){
		ob_start();
		include(Ressource::getCurrentTemplate()->getFile(new File("html/module/ModelEditor","TabModelEditorDescriptor_".$this->type.".phtml",false))->toURL());
		return ob_get_clean();
	}
}
