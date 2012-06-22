<?php
/**
* Wrapper for HTML generation of modules.  Creates the notion of instance HTML code from modules in HTML generation. The code given is specific to an instance. Thus a module could be registered twice 
* in a single hook and display different things
* 
* 
*/
 class CoreModuleHTMLProducer{
	 /**
	 * module wrapped
	 * 
	 */
	 private $module;
	 /**
	 * instance of the module
	 * 
	 */
	 private $instance;
	 /**
	 * Configuration Parameters Array
	 */
	 private $confParams;
	 /**
	 * Builds a ModuleHTMLProducer given a module and an instance
	 * 
	 * @param Module $module The module to wrapp
	 * @param string $instance  the HTML instance name
	 */
	 public function __construct($module,$instance){
		$this->module=$module;
		$this->instance=$instance;
	}
	/**
	* Defines the Configuration Parameter Array
	* @param array $params  the Configuration Parameter Array
	*/
	public function setConfParams($params){
	    $this->confParams=$params;
	}
	/**
	* Returns  the Configuration Parameter Array
	* @return array  the Configuration Parameter Array
	*/
	public function getConfParams(){
	    return $this->confParams;
	}
	/**
	* Returns a Configuration parameter value given its key
	* @return string The Configuration parameter value
	* @param string $key the key to the Configuration parameter value
	*/
	public function getConfParam($key){
	    return $this->confParams[$key];
	}
	/**
	* Returns the HTML code for the given Hook
	* 
	* @return string the HTML code for the given Hook
	* @param string $currentHook the name of the string being executed
	*/
	public function toHTML($currentHook){
	    $this->module->setCurrentHTMLProducer($this);
		$html= $this->module->toHTMLCache($currentHook,$this->instance);
		$this->module->setCurrentHTMLProducer(null);
		return $html;
	}

}


?>