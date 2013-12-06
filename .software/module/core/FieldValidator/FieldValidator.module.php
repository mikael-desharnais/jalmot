<?php
/**
* Module to manage Field Validation
* Field Validation using this module is done both in PHP and Javascript.
* Each class used should exist in Both
*/
class FieldValidator extends Module{
	/**
	* List of existing descriptors
	*/
	protected $descriptors=array();
	/**
	* Initilises the Module :
	* Registers in global execution stack
	* TODO : correct this
	*/
	public function init(){
		parent::init();
        $this->addToGlobalExecuteStack();
		$this->importClasses();
		
		$listener=new EventListener($this);
		$listener->beforeSavePerformed=function ($listenedTo,$listeningObject){
		    return $listeningObject->getDescriptor($listenedTo->getName())->isValid();
		};
		//Module::getInstalledModule('modelEditor')->addBeforeSaveListener($listener);
	}
	/**
	* Returns the descriptor correspoding to the given name
	* @return FieldValidatorDescriptor the descriptor correspoding to the given name
	* @param string $name the name of the descriptor
	*/
	public function getDescriptor($name){
	    if (!array_key_exists($name,$this->descriptors)){
	    	$xml_url=Resource::getCurrentTemplate()->getFile(new File('xml/module/FieldValidator/descriptor/',$name.".xml",false));
	    	$xml = XMLDocument::parseFromFile($xml_url);
	    	$this->addDescriptor(call_user_func(array($xml->class."","readFromXML"),$xml));
	    }
	    return $this->descriptors[$name];
	}
	/**
	* Adds a FieldValidatorDescriptor to the module
	* @param FieldValidatorDescriptor $descriptor The FieldValidatorDescriptor to add to the module
	*/
	public function addDescriptor($descriptor){
		$this->descriptors[$descriptor->getName()]=$descriptor;
	}
}


