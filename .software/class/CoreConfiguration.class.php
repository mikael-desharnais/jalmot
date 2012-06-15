<?php
/**
 * Contains all configuration values : By default, is empty and must be overriden by applications
 *
 * 
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */
 class CoreConfiguration{
     private $configurationArray=array();
     
	/**
     * Does nothing, must be overriden
     */
	public function __construct(){
		$xml = XMLDocument::parseFromFile(new File("xml","configuration.xml",false));
		foreach($xml as $key=>$child){
		    $this->configurationArray[$key]=$child."";
		}
	}
	public function getValue($key){
	    if (array_key_exists($key, $this->configurationArray)){
	    	return $this->configurationArray[$key];
	    }else {
	        return "";
	    }
	}
	
	/**
	 * Returns the current Configuration
	 * @return The current Configuration
	 */
	public static function getCurrentConfiguration(){
		return new Configuration();
	}
}
?>
