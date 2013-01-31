<?php
/**
* Override of Page used for AjaxPage
*/
 class CoreAjaxPage extends Page{
	/**
	* The unique name of this page
	*/
	protected $ajaxTarget;
	/**
	* Calls the parent constructor with name
	* Defines the unique name of the page
	* @param string $ajaxTarget the unique name of the page
	*/
	public function __construct($ajaxTarget){
		parent::__construct($ajaxTarget);
		$this->ajaxTarget=$ajaxTarget;
	}
	/**
	* Generate the output for this page
	* @return string the output for this page
	*/
	public function toHTML($templateToLoad="html/ajaxStandardPage.phtml"){
		return parent::toHTML($templateToLoad);
	}
	/**
	* Returns the unique name of this page
	* @return string the unique name of this page
	*/
	public function getPointerName(){
		return $this->ajaxTarget;
	}
	/**
	* Returns the XML File used to described installed modules for this page
	* @return File the XML File used to described installed modules for this page
	*/
	public function getXMLModuleFileConfiguration(){
		return Ressource::getCurrentTemplate()->getFile(new File('xml/page/ajax/'.$this->ajaxTarget,'modules.xml',false));
	}
	/**
	* Returns the File used for Configuration
	* @return File the File used for Configuration
	*/
	public function getConfigurationFile(){
		return Ressource::getCurrentTemplate()->getFile(new File("xml/page/ajax/".$this->ajaxTarget,"configuration.xml",false));
	}
	/**
	* Returns the File used for Hook description
	* @return File the File used for Hook description
	* @param string $name the name of the hook
	* @param boolean $silent=false false if no error should be triggered on file not found
	*/
	public function getHookDescriptionFile($name,$silent=false){
		return Ressource::getCurrentTemplate()->getFile(new File("xml/page/ajax/".$this->ajaxTarget."/hook",$name.".xml",false),$silent);
	}
	/**
	* Defines a value for the JSON ajax content
	* @param string $key the key to the JSON ajax content
	* @param mixed $value the value for the JSON ajax content
	*/
	public function setAjaxContent($key,$value){
		$this->ajaxContent[$key]=$value;
	}
	/**
	* Returns the JSON ajax Content
	* @return array the JSON ajax Content
	*/
	public function getAjaxContent(){
		return $this->ajaxContent;
	}
}


?>
