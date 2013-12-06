<?php

class JSCSSPreLoader extends Module{
	
	protected $js=array();
	protected $css=array();
	
	public function init(){
		parent::init();
        $this->addToGlobalExecuteStack();
	    $file=Template::getCurrentTemplate()->getFile(new File('xml/module/JSCSSPreLoader',Resource::getCurrentPage()->getName().'.xml',false));
	    $xml = XMLDocument::parseFromFile($file);
	    foreach($xml->js->children() as $xmlJSFile){
	    	$this->js[]=array('file'=>Resource::getCurrentTemplate()->getFile(new File($xmlJSFile->directory,$xmlJSFile->name,false)),'order'=>(int)$xmlJSFile->order);
	    }
	    foreach($xml->css->children() as $xmlCSSFile){
	    	$this->css[]=array('file'=>Resource::getCurrentTemplate()->getFile(new File($xmlCSSFile->directory,$xmlCSSFile->name,false)),'order'=>(int)$xmlCSSFile->order);
	    }
	}
	public function execute(){
	    parent::execute();
	    foreach($this->js as $jsFile){
	        Resource::getCurrentPage()->addJS($jsFile['file'], $jsFile['order']);
	    }
	    foreach($this->css as $cssFile){
	        Resource::getCurrentPage()->addCSS($cssFile['file'], $cssFile['order']);
	    }
	}
	
}


