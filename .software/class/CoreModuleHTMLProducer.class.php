<?php
/**
 * Wrapper for HTML generation of modules.  Creates the notion of instance HTML code from modules in HTML generation. The code given is specific to an instance. Thus a module could be registered twice 
 * in a single hook and display different things
 *
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */
 class CoreModuleHTMLProducer{
	    /**
	     * module wrapped
	     * @access private
	     * @var Module
	     */
	 private $module;
	    /**
	     * instance of the module
	     * @access private
	     * @var string
	     */
	 private $instance;
	 
	 private $confParams;
	 
	 /**
	  * Builds a ModuleHTMLProducer given a module and an instance
	  * @param $module		module which produces HTML
	  * @param $instance	Instance for HTML generation
	  */
	 public function __construct($module,$instance){
		$this->module=$module;
		$this->instance=$instance;
	}
	
	public function setConfParams($params){
	    $this->confParams=$params;
	}
	public function getConfParams(){
	    return $this->confParams;
	}
	public function getConfParam($key){
	    return $this->confParams[$key];
	}
	 /**
	  * Returns the HTML code for the given Hook
	  * @param $currentHook		hook requiring HTML
	  * @return  the HTML code for the given Hook
	  */
	public function toHTML($currentHook){
	    $this->module->setCurrentHTMLProducer($this);
		$html= $this->module->toHTML($currentHook,$this->instance);
		$this->module->setCurrentHTMLProducer(null);
		return $html;
	}

}
?>