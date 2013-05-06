<?php

class ModelEditor extends Module{
	
	protected $descriptor;
	public static $MODE_GET_MODEL_EDITOR_FILE_FROM_PARAMETERS=1;
	protected $beforeSaveListenerList=array();
	protected $afterSaveListenerList=array();
	
	public function init(){
		parent::init();
		$this->importClasses();
        $this->addToGlobalExecuteStack();
	}
	public function execute(){
		parent::execute();
		if ($this->getConfParam('mode')==self::$MODE_GET_MODEL_EDITOR_FILE_FROM_PARAMETERS){
		    $xml=XMLDocument::parseFromFile(Ressource::getCurrentTemplate()->getFile(new File("xml/module/ModelEditor/descriptor",Ressource::getParameters()->getValue("descriptor").".xml",false)));
		    $classname=$xml->class."";
			$this->setDescriptor(call_user_func(array($classname,"readFromXML"),Ressource::getParameters()->getValue("descriptor"),$xml));
		}
		if ((Ressource::getParameters()->valueExists("source")&&Ressource::getParameters()->getValue("source")=='create')){
		    $this->descriptor->setSource(ModelData::$SOURCE_NEW);
		}else {
			$this->descriptor->setSource(ModelData::$SOURCE_FROM_DATASOURCE);
		}
		$this->descriptor->setId(Ressource::getParameters()->getValue('id'));
		$this->descriptor->fetchData();
		Ressource::getCurrentLanguage()->init('module/alertWindow');
		if (Ressource::getParameters()->valueExists("action")&&Ressource::getParameters()->getValue("action")=="save"){
		    if (!$this->propagateBeforeSave()){
				return;		    
		    }
			$this->descriptor->save();
			Ressource::getCurrentPage()->stopExecution();
			if ($this->descriptor->reloadOnSave()){
				$fetchedData = $this->descriptor->getFetchedData();
				
				print(json_encode(array('status'=>313,'html'=>Ressource::getCurrentLanguage()->getTranslation('Save operation Success'),'params'=>array('id'=>$fetchedData['simple']->getPrimaryKeys(),'source'=>'db'),'css'=>array(),'js'=>array())));
			}else {
				print(json_encode(array('status'=>1,'html'=>'','css'=>array(),'js'=>array())));
			}
			$this->propagateAfterSave();
		}else if (Ressource::getParameters()->valueExists("action")&&Ressource::getParameters()->getValue("action")=="delete"){
		    $this->descriptor->delete();
			Ressource::getCurrentPage()->stopExecution();
			print(json_encode(array('status'=>311,'html'=>Ressource::getCurrentLanguage()->getTranslation('Delete operation Success'),'css'=>array(),'js'=>array())));
		}
	}
	public function setDescriptor($descriptor){
		$this->descriptor=$descriptor;
	}
	public function getDescriptor(){
		return $this->descriptor;
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
