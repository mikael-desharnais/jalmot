<?php

class JSCSSPreLoader extends Module{
	
	protected $js=array();
	protected $css=array();
	
	public function init(){
		parent::init();
        $this->addToGlobalExecuteStack();
	    $file=Template::getCurrentTemplate()->getFile(new File('xml/module/JSCSSPreLoader',Ressource::getCurrentPage()->getName().'.xml',false));
	    $xml = XMLDocument::parseFromFile($file);
	    foreach($xml->js->children() as $xmlJSFile){
	    	$this->js[]=Ressource::getCurrentTemplate()->getFile(new File($xmlJSFile->directory,$xmlJSFile->name,false));
	    }
	    foreach($xml->css->children() as $xmlCSSFile){
	    	$this->css[]=Ressource::getCurrentTemplate()->getFile(new File($xmlCSSFile->directory,$xmlCSSFile->name,false));
	    }
	}
	public function execute(){
	    parent::execute();
	    foreach($this->js as $jsFile){
	        Ressource::getCurrentPage()->addJS($jsFile, 13);
	    }
	    foreach($this->css as $cssFile){
	        Ressource::getCurrentPage()->addCSS($cssFile, 13);
	    }
	}
	
}


