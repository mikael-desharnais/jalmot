<?php
/**
* The Static Ressource object for all the things you may need
*/
class CoreRessource{
	/**
	* The current page Object
	*/
	protected static $page;
	/**
	* The current template Object
	*/
	protected static $template;
	/**
	* The current Language object
	*/
	protected static $language;
	/**
	* The current Configuration manager Object
	*/
	protected static $configuration;
	/**
	* The current GET/POST parameters Object
	*/
	protected static $parameters;
	/**
	* The current Datasource object
	*/
	protected static $dataSource;
	/**
	* The current UserSpace Object
	*/
	protected static $userSpace;
	/**
	* The current Session Manager Object
	*/
	protected static $sessionManager;
	/**
	* Returns the Current Page object
	* @return Page the Current Page object
	*/
	public static function getCurrentPage(){
		return self::$page;
	}
	/**
	* Defines the Current Page object
	* @param Page $cPage the Current Page object
	*/
	public static function setCurrentPage($cPage){
		self::$page=$cPage;
	}
	/**
	* Returns the current template object
	* @return Template the current template object
	*/
	public static function getCurrentTemplate(){
		return self::$template;
	}
	/**
	* Defines the current template object
	* @param Template $cTemplate the current template object
	*/
	public static function setCurrentTemplate($cTemplate){
		self::$template=$cTemplate;
	}
	/**
	* defines the current Language Object
	* @param Language $cLanguage  the current Language Object
	*/
	public static function setCurrentLanguage($cLanguage){
		self::$language=$cLanguage;
	}
	/**
	* Returns  the current Language Object
	* @return Language  the current Language Object
	*/
	public static function getCurrentLanguage(){
		return self::$language;
	}
	/**
	* Defines the current Configuration manager Object
	* @param Configuration $cConfiguration the current Configuration manager Object
	*/
	public static function setConfiguration($cConfiguration){
		self::$configuration=$cConfiguration;
	}
	/**
	* Returns the current Configuration manager Object
	* @return Configuration the current Configuration manager Object
	*/
	public static function getConfiguration(){
		return self::$configuration;
	}
	/**
	* Defines the current GET/POST parameters manager Object
	* @param Parameter $cParameters  the current GET/POST parameters manager Object
	*/
	public static function setParameters($cParameters){
		self::$parameters=$cParameters;
	}
	/**
	* Returns the current GET/POST parameters manager Object
	* @return Parameter  the current GET/POST parameters manager Object
	*/
	public static function getParameters(){
		return self::$parameters;
	}
	/**
	* Defines the current Datasource Object
	* @param DataSource $cDataSource the current Datasource Object
	*/
	public static function setDataSource($cDataSource){
		self::$dataSource=$cDataSource;
	}
	/**
	* Returns the current Datasource Object
	* @return DataSource the current Datasource Object
	*/
	public static function getDataSource(){
		return self::$dataSource;
	}
	/**
	* Defines the current UserSpace Object
	* @param UserSpace $cUserSpace the current UserSpace Object
	*/
	public static function setUserSpace($cUserSpace){
		self::$userSpace=$cUserSpace;
	}
	/**
	* Returns the current UserSpace Object
	* @return UserSpace the current UserSpace Object
	*/
	public static function getUserSpace(){
		return self::$userSpace;
	}
	/**
	* Defines the current SessionManager Object
	* @param SessionManager $cSessionManager the current SessionManager Object
	*/
	public static function setSessionManager($cSessionManager){
		self::$sessionManager=$cSessionManager;
	}
	/**
	* Returns the current SessionManager Object
	* @return SessionManager the current SessionManager Object
	*/
	public static function getSessionManager(){
		return self::$sessionManager;
	}
	
	
}


?>
