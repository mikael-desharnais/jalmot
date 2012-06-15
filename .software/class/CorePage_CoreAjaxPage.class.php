<?php
 class CoreAjaxPage extends Page{
	protected $ajaxTarget;
	public function __construct($ajaxTarget){
		parent::__construct('ajaxDispatcher');
		$this->ajaxTarget=$ajaxTarget;
	}

	public function toHTML($templateToLoad="html/ajaxStandardPage.phtml"){
		return parent::toHTML($templateToLoad);
	}
	public function getPointerName(){
		return $this->ajaxTarget;
	}
	public function getXMLModuleFileConfiguration(){
		return Ressource::getCurrentTemplate()->getFile(new File('xml/page/ajax/'.$this->ajaxTarget,'modules.xml',false));
	}

	public function getConfigurationFile(){
		return Ressource::getCurrentTemplate()->getFile(new File("xml/page/ajax/".$this->ajaxTarget,"configuration.xml",false));
	}
	public function getHookDescriptionFile($name,$silent=false){
		return Ressource::getCurrentTemplate()->getFile(new File("xml/page/ajax/".$this->ajaxTarget."/hook",$name.".xml",false),$silent);
	}
}
?>
