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
		    $xml=XMLDocument::parseFromFile(Resource::getCurrentTemplate()->getFile(new File("xml/module/ModelEditor/descriptor",Resource::getParameters()->getValue("descriptor").".xml",false)));
		    $classname=$xml->class."";
			$this->setDescriptor(call_user_func(array($classname,"readFromXML"),Resource::getParameters()->getValue("descriptor"),$xml));
		}
		if ((Resource::getParameters()->valueExists("source")&&Resource::getParameters()->getValue("source")=='create')){
		    $this->descriptor->setSource(ModelData::$SOURCE_NEW);
		}else {
			$this->descriptor->setSource(ModelData::$SOURCE_FROM_DATASOURCE);
		}
		$this->descriptor->setId(Resource::getParameters()->getValue('id'));
		$this->descriptor->fetchData();
		Resource::getCurrentLanguage()->init('module/alertWindow');
		if (Resource::getParameters()->valueExists("action")&&Resource::getParameters()->getValue("action")=="save"){
		    if (!$this->propagateBeforeSave()){
				return;		    
		    }
			$this->descriptor->save();
			Resource::getCurrentPage()->stopExecution();
			if ($this->descriptor->reloadOnSave()){
				$fetchedData = $this->descriptor->getFetchedData();
				
				print(json_encode(array('status'=>313,'html'=>Resource::getCurrentLanguage()->getTranslation('Save operation Success'),'params'=>array('id'=>$fetchedData['simple']->getPrimaryKeys(),'source'=>'db'),'css'=>array(),'js'=>array())));
			}else {
				print(json_encode(array('status'=>1,'html'=>'','css'=>array(),'js'=>array())));
			}
			$this->propagateAfterSave();
			die();
		}else if (Resource::getParameters()->valueExists("action")&&Resource::getParameters()->getValue("action")=="delete"){
		    $this->descriptor->delete();
			Resource::getCurrentPage()->stopExecution();
			print(json_encode(array('status'=>311,'html'=>Resource::getCurrentLanguage()->getTranslation('Delete operation Success'),'css'=>array(),'js'=>array())));
			die();
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
