<?php

class CoreRessource{
	protected static $page;
	protected static $template;
	protected static $language;
	protected static $configuration;
	protected static $parameters;
	protected static $dataSource;
	protected static $userSpace;
	protected static $sessionManager;
	
	public static function getCurrentPage(){
		return self::$page;
	}
	public static function setCurrentPage($cPage){
		self::$page=$cPage;
	}
	public static function getCurrentTemplate(){
		return self::$template;
	}
	public static function setCurrentTemplate($cTemplate){
		self::$template=$cTemplate;
	}
	public static function setCurrentLanguage($cLanguage){
		self::$language=$cLanguage;
	}
	public static function getCurrentLanguage(){
		return self::$language;
	}
	public static function setConfiguration($cConfiguration){
		self::$configuration=$cConfiguration;
	}
	public static function getConfiguration(){
		return self::$configuration;
	}
	public static function setParameters($cParameters){
		self::$parameters=$cParameters;
	}
	public static function getParameters(){
		return self::$parameters;
	}
	public static function setDataSource($cDataSource){
		self::$dataSource=$cDataSource;
	}
	public static function getDataSource(){
		return self::$dataSource;
	}
	public static function setUserSpace($cUserSpace){
		self::$userSpace=$cUserSpace;
	}
	public static function getUserSpace(){
		return self::$userSpace;
	}
	public static function setSessionManager($cSessionManager){
		self::$sessionManager=$cSessionManager;
	}
	public static function getSessionManager(){
		return self::$sessionManager;
	}
	
	
}
?>
