<?php

 class CoreMailPage extends Page{
	/**
	* The unique name of this page
	*/
	protected $mailTarget;
	/**
	* Calls the parent constructor with name
	* Defines the unique name of the page
	* @param string $ajaxTarget the unique name of the page
	*/
	public function __construct($mailTarget){
		$this->mailTarget=$mailTarget;
		parent::__construct($mailTarget);
	}
	/**
	* Generate the output for this page
	* @return string the output for this page
	*/
	public function toHTML($templateToLoad="html/mail/mailStandardPage.phtml"){
		ob_start();
		parent::toHTML($templateToLoad);
		return ob_get_clean();
	}
	/**
	* Returns the unique name of this page
	* @return string the unique name of this page
	*/
	public function getPointerName(){
		return $this->mailTarget;
	}
	/**
	* Returns the XML File used to described installed modules for this page
	* @return File the XML File used to described installed modules for this page
	*/
	public function getXMLModuleFileConfiguration(){
		return Resource::getCurrentTemplate()->getFile(new File('xml/page/mail/'.$this->mailTarget,'modules.xml',false));
	}
	/**
	* Returns the File used for Configuration
	* @return File the File used for Configuration
	*/
	public function getConfigurationFile(){
		return Resource::getCurrentTemplate()->getFile(new File("xml/page/mail/".$this->mailTarget,"configuration.xml",false));
	}
	/**
	* Returns the File used for Hook description
	* @return File the File used for Hook description
	* @param string $name the name of the hook
	* @param boolean $silent=false false if no error should be triggered on file not found
	*/
	public function getHookDescriptionFile($name,$silent=false){
		return Resource::getCurrentTemplate()->getFile(new File("xml/page/mail/".$this->mailTarget."/hook",$name.".xml",false),$silent);
	}
}


?>
