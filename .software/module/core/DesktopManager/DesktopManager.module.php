<?php

class DesktopManager extends Module{
    
	protected $icons;
	
	public function init(){
		parent::init();
        $this->addToGlobalExecuteStack();
		$this->importClasses();
	}
	public function execute(){
		parent::execute();
		$xml_url=Ressource::getCurrentTemplate()->getFile(new File('xml/page/'.Ressource::getCurrentPage()->getName(),"desktop.xml",false));
		$xml = XMLDocument::parseFromFile($xml_url);
		foreach($xml->icons->children() as $icon){
			$this->addIcon(call_user_func(array($icon->class."","readFromXML"),$icon));
		}
	}
	public function addIcon($icon){
		$this->icons[]=$icon;
	}
	
}
