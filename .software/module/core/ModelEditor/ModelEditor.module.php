<?php

class ModelEditor extends Module{
	
	protected $descriptor;
	public static $MODE_GET_MODEL_EDITOR_FILE_FROM_PARAMETERS=1;
	protected $beforeSaveListenerList=array();
	protected $afterSaveListenerList=array();
	
	public function init(){
		parent::init();
        $this->addToGlobalExecuteStack();
		$this->importClasses();
	}
	public function execute(){
		parent::execute();
		if ($this->getConfParam('mode')==self::$MODE_GET_MODEL_EDITOR_FILE_FROM_PARAMETERS){
		    $xml=XMLDocument::parseFromFile(Ressource::getCurrentTemplate()->getFile(new File("xml/module/ModelEditor/descriptor",Ressource::getParameters()->getValue("descriptor").".xml",false)));
		    $classname=$xml->class."";
			$this->setDescriptor(call_user_func(array($classname,"readFromXML"),Ressource::getParameters()->getValue("descriptor"),$xml));
		}
		if (!(Ressource::getParameters()->valueExists("source"))){
			$this->descriptor->setSource(ModelData::$SOURCE_FROM_DATASOURCE);
		}else {
		    $this->descriptor->setSource(ModelData::$SOURCE_NEW);
		}
		if ($this->descriptor->getSource()==ModelData::$SOURCE_FROM_DATASOURCE){
			$this->descriptor->setId(Ressource::getParameters()->getValue('id'));
		}
		$this->descriptor->fetchData();
		if (Ressource::getParameters()->valueExists("action")&&Ressource::getParameters()->getValue("action")=="save"){
		    if (!$this->propagateBeforeSave()){
				return;		    
		    }
			$this->descriptor->save();
			Ressource::getCurrentPage()->stopExecution();
			$this->propagateAfterSave();
		}else if (Ressource::getParameters()->valueExists("action")&&Ressource::getParameters()->getValue("action")=="delete"){
		    $this->descriptor->delete();
			Ressource::getCurrentPage()->stopExecution();
		}
	}
	public function setDescriptor($descriptor){
		$this->descriptor=$descriptor;
	}
	public function addBeforeSaveListener($listener){
	    if (!in_array($listener,$this->beforeSaveListenerList)){
	        $this->beforeSaveListenerList[]=$listener;
	    }
	}
	public function addAfterSaveListener($listener){
	    if (!in_array($listener,$this->afterSaveListenerList)){
	        $this->afterSaveListenerList[]=$listener;
	    }
	}
	public function propagateBeforeSave(){
	    $toReturn=true;
	    foreach($this->beforeSaveListenerList as $listener){
	        $functionToExecute=$listener->beforeSavePerformed;
	        $result=$functionToExecute($this->descriptor,$listener->getListeningObject());
	        $toReturn=$toReturn&&$result;
	    }
	    return $toReturn;
	}
	public function propagateAfterSave(){
	    $toReturn=true;
	    foreach($this->afterSaveListenerList as $listener){
	        $functionToExecute=$listener->afterSavePerformed;
	        $result=$functionToExecute($this->descriptor,$listener->getListeningObject());
	        $toReturn=$toReturn&&$result;
	    }
	    return $toReturn;
	}
	
}
