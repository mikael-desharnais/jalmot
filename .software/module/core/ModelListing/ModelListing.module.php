<?php
class ModelListing extends Module{
    
	protected $descriptor;
	public static $MODE_GET_MODEL_LISTING_FILE_FROM_PARAMETERS=1;
	
	public function init(){
		parent::init();
        $this->addToGlobalExecuteStack();
		$this->importClasses();
	}
	public function execute(){
		parent::execute();
		if ($this->getConfParam('mode')==self::$MODE_GET_MODEL_LISTING_FILE_FROM_PARAMETERS){
		    $xml=XMLDocument::parseFromFile(Resource::getCurrentTemplate()->getFile(new File("xml/module/ModelListing/descriptor",Resource::getParameters()->getValue("descriptor").".xml",false)));
		    $descriptor = call_user_func(array($xml->class."","readFromXML"),Resource::getParameters()->getValue("descriptor"),$xml);
		    $descriptor->setPage((int)Resource::getParameters()->getValue("page_number"));
			$this->setDescriptor($descriptor);
		}
		if ($this->descriptor!=null){
			$this->descriptor->fetchData();
		}
	}
	public function setDescriptor($descriptor){
		$this->descriptor=$descriptor;
	}
}
