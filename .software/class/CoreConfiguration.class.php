<?php
/**
* Contains all configuration values : By default, uses a XML file : xml/configuration.xml
* 
* 
* 
*/
 class CoreConfiguration{
     /**
     * Array containing all the configuration data
     */
     private $configurationArray=array();
	/**
	* Loads the XML file containing configuration
	*/
	public function __construct(){
		$xml = XMLDocument::parseFromFile(new File("xml","configuration.xml",false));
		foreach($xml as $key=>$child){
		    $this->configurationArray[$key]=$child."";
		}
	}
	/**
	* Returns the value corresponding to the given key. If the value does not exists, an empty string is returned
	* @return string the value corresponding to the given key. If the value does not exists, an empty string is returned
	* @param string $key The key to the configuration value
	*/
	public function getValue($key){
	    if (array_key_exists($key, $this->configurationArray)){
	    	return $this->configurationArray[$key];
	    }else {
	        return "";
	    }
	}
	/**
	* Returns the current Configuration Manager
	* 
	* @return Configuration The current Configuration
	*/
	public static function getCurrentConfiguration(){
		return new Configuration();
	}
}
?>
