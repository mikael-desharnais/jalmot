<?php
/**
* This module provides a basic and simple CSS and JS minifier
* Simple but not perfect
*/
class JSCSSMinifier extends Module{
	/**
	* Replaces the JS and CSS minifier by its own objects
	*/
	public function init(){
	    parent::init();
	    $this->importClasses();
	    $startPageListener=new EventListener($this);
	    $startPageListener->actionPerformed=function($sourcePage){
	        $sourcePage->setCSSFilterFlow(Module::getInstalledModule('JSCSSMinifier')->getCSSFlowFilter());
	        $sourcePage->setJSFilterFlow(Module::getInstalledModule('JSCSSMinifier')->getJSFlowFilter());
	    };
	    Resource::getCurrentPage()->addStartPageEventListener($startPageListener);
	}
	/**
	* Returns an instance of BasicCSSFlowFilter
	* @return BasicCSSFlowFilter an instance of BasicCSSFlowFilter
	*/
	public function getCSSFlowFilter(){
	    return new BasicCSSFlowFilter();
	}
	/**
	* Returns an instance of BasicJSFlowFilter
	* @return BasicJSFlowFilter an instance of BasicJSFlowFilter
	*/
	public function getJSFlowFilter(){
	    return new BasicJSFlowFilter();
	}
}




?>