<?php

class FieldValidator extends Module{
    
	protected $descriptors=array();
	
	public function init(){
		parent::init();
        $this->addToGlobalExecuteStack();
		$this->importClasses();
		
		// TODO
		$listener=new EventListener($this);
		$listener->beforeSavePerformed=function ($listenedTo,$listeningObject){
		    return $listeningObject->getDescriptor($listenedTo->getName())->isValid();
		};
		//Module::getInstalledModule('modelEditor')->addBeforeSaveListener($listener);
	}
	
	public function getDescriptor($name){
	    if (!array_key_exists($name,$this->descriptors)){
	    	$xml_url=Ressource::getCurrentTemplate()->getFile(new File('xml/module/FieldValidator/descriptor/',$name.".xml",false));
	    	$xml = XMLDocument::parseFromFile($xml_url);
	    	$this->addDescriptor(call_user_func(array($xml->class."","readFromXML"),$xml));
	    }
	    return $this->descriptors[$name];
	}
	public function addDescriptor($descriptor){
		$this->descriptors[$descriptor->getName()]=$descriptor;
	}
}
