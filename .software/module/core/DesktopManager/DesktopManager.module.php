<?php
/**
* Module that displays a Desktop system
*/
class DesktopManager extends Module{
	/**
	* Icons contained in this Desktop
	*/
	protected $icons;
	/**
	* Initialises the module
	* Adds the module in the global execution stack
	* Imports the classes
	*/
	public function init(){
		parent::init();
        $this->addToGlobalExecuteStack();
		$this->importClasses();
	}
	/**
	* Loads the XML file [template]/xml/page/[current_page]/desktop.xml
	*/
	public function execute(){
		parent::execute();
		$xml_url=Resource::getCurrentTemplate()->getFile(new File('xml/page/'.Resource::getCurrentPage()->getName(),"desktop.xml",false));
		$xml = XMLDocument::parseFromFile($xml_url);
		foreach($xml->icons->children() as $icon){
			$this->addIcon(call_user_func(array($icon->class."","readFromXML"),$icon));
		}
	}
	/**
	* Adds an icon
	* @param DesktopIcon $icon The Icon to add
	*/
	public function addIcon($icon){
		$this->icons[]=$icon;
	}
	
}


