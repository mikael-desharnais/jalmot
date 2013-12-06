<?php

class ModelEditorME extends SimpleFieldME {
	
	public $innerModelEditor = null;
	
	public function getUsefullData($dataFetched){
		$this->createInnerModelEditor($dataFetched);
	    return $dataFetched;
	}
	public function getInnerModelEditor(){
		return $this->innerModelEditor;
	}
	public function fetchElementsToSave($dataFetched){
		$this->createInnerModelEditor($dataFetched);
		$this->innerModelEditor->fetchDataToSave();

		if ($this->getConfParam('saveBefore')=='BEFORE'){
			$listener=new EventListener($this);		
			$listener->beforeSavePerformed=function ($listenedTo,$listeningObject){
				$listeningObject->saveBefore($listenedTo);
			};
			$dataFetched['simple']->addBeforeSaveListener($listener);
		}else {
			$listener=new EventListener($this);		
			$listener->afterSavePerformed=function ($listenedTo,$listeningObject){
				$listeningObject->saveAfter($listenedTo);
			};
			$dataFetched['simple']->addAfterSaveListener($listener);
		}
	}
	public function saveBefore($saved){
		$this->getInnerModelEditor()->save();
		$fetched = $this->innerModelEditor->getFetchedData();
		$sourceFieldSetter = "set".$this->getConfParam('sourceField');
		$destinationFieldGetter = "get".$this->getConfParam('destinationField');
		$saved->$sourceFieldSetter($fetched['simple']->$destinationFieldGetter());
	}
	public function saveAfter($saved){
		$this->getInnerModelEditor()->save();
		$fetched = $this->innerModelEditor->getFetchedData();
		$sourceFieldSetter = "set".$this->getConfParam('sourceField');
		$destinationFieldGetter = "get".$this->getConfParam('destinationField');
		$fetched['simple']->$sourceFieldSetter($saved->$destinationFieldGetter());
	}
	public function createInnerModelEditor($dataFetched){
		if (empty($this->innerModelEditor)){
		    $xml=XMLDocument::parseFromFile(Resource::getCurrentTemplate()->getFile(new File("xml/module/ModelEditor/descriptor",$this->getConfParam('modelEditor').".xml",false)));
		    $this->innerModelEditor = call_user_func(array($xml->class."","readFromXML"),$this->getConfParam('modelEditor'),$xml);
		    $parent = $dataFetched['simple']; 
		    $list = $dataFetched[$this->getConfParam('listReference')];
		    $found = false;
		    $sourceFieldGetter = "get".$this->getConfParam('sourceField');
		    foreach($list as $listElement){
		    	if ($listElement->$sourceFieldGetter()==$parent->$sourceFieldGetter()){
		    		$element = $listElement;
		    		$found=true;
		    	}
		    }
		    if (!$found){
		    	$this->innerModelEditor->setSource(ModelData::$SOURCE_NEW);
		    }else {
		    	$this->innerModelEditor->setSource(ModelData::$SOURCE_FROM_DATASOURCE);
		    	$this->innerModelEditor->setId(array($this->getConfParam('destinationField')=>$element->$sourceFieldGetter()));
		    }
		    $this->innerModelEditor->setParentField($this);
		    $this->innerModelEditor->fetchData();
		}
	}
}
