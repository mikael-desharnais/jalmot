<?php
class JSCSSMinifier extends Module{
	public function init(){
	    parent::init();
	    $this->importClasses();
	    $startPageListener=new StartPageListener();
	    $startPageListener->actionPerformed=function($sourcePage){
	        $sourcePage->setCSSFilterFlow(Module::getInstalledModule('JSCSSMinifier')->getCSSFlowFilter());
	        $sourcePage->setJSFilterFlow(Module::getInstalledModule('JSCSSMinifier')->getJSFlowFilter());
	    };
	    Ressource::getCurrentPage()->addStartPageEventListener($startPageListener);
	}
	public function getCSSFlowFilter(){
	    return new BasicCSSFlowFilter();
	}
	public function getJSFlowFilter(){
	    return new BasicJSFlowFilter();
	}
}
?>