<?php
/**
 * Base bloc for the entire system.Modules are what act/display .... everything
 * 
 * 
 * A module goes through several steps first it gets loaded and instanciated (the order of instanciation is unknown)
 * Then, it is initiated (At this step, all modules exist)
 * Then it is executed according to the way it chose to be executed
 *
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */
class CoreModule{
    /**
     * List of all modules
     * @access private
     * @var array
     */
	private static $module=array();
    /**
     * Standard stack of execution, contains references to modules to be executed in the basic execution flow
     * @access private
     * @var array
     */
	private static $executeStack=array();
	
	private static $classesToLoad=array();
	
	/**
	 * Allows modules to be declared as installed (so as to be reused later)
	 *
	 * @param $cModule		module to declare as installed
	 */
	private static function addInstalledModule($cModule){
		self::$module[$cModule->getName()]=$cModule;
	}
	/**
	 * Returns an installed module given its name
	 *
	 * @param $moduleName		name of the module to return
	 * @return the module required, if the module is not found, an error is logged 
	 */
	public static function getInstalledModule($moduleName){
		if (!array_key_exists($moduleName,self::$module)){
			Log::Error('Unknown Module : Required : '.$moduleName);
		}
		return self::$module[$moduleName];
	}
	/**
	 * Load all modules : in module/core, module/dev/, module/usr
	 * Loading means Including files, creating an instance, init all modules and execute the basic execution stack
	 */
	public static function loadAll(){
		$xml=XMLDocument::parseFromFile(Ressource::getCurrentPage()->getXMLModuleFileConfiguration());
		foreach($xml as $xmlModule){
			$object=self::loadModule($xmlModule->instancename."",$xmlModule->class."");
			$object->setConfParams(XMLParamsReader::read($xmlModule));
		}
	self::initModule();
	self::executeModule();
	}
	
	public static function loadModule($instancename,$class){
		$filename=self::findModule($class);
		include_once($filename->toURL());
		$object=new $class($filename,$class,$instancename);
		CoreModule::addInstalledModule($object);
		return $object;
	}
	public static function findModule($class){
		if (file_exists("module/core/".$class."/".$class.".module.php")){
			return new File("module/core/".$class,$class.".module.php",false);
		}
		if (file_exists("module/dev/".$class."/".$class.".module.php")){
			return new File("module/dev/".$class,$class.".module.php",false);
		}
		if (file_exists("module/usr/".$class."/".$class.".module.php")){
			return new File("module/usr/".$class,$class.".module.php",false);
		}
		throw new Exception("Cannot Find Module File : ".$class);
	}
/*
	/**
	 * Loops over installed modules and inits them
	 */
	protected static function initModule(){
		foreach (self::$module as $module){
			$module->init();
		}
		Classe::includeClasses(self::$classesToLoad);
	}
	/**
	 * Loops over installed modules and executes them
	 */
	protected static function executeModule(){
		for ($x=0;$x<count(self::$executeStack);$x++){
			self::$executeStack[$x]->execution();
		}
	}
	
	protected static function addClassesToLoad($classes){
	    self::$classesToLoad=array_merge(self::$classesToLoad,$classes);
	}
	
	
	/**
	 * name of the module
	 * @access protected
	 * @var string
	 */
	protected $name;
	/**
	 * base directory for this module
	 * @access protected
	 * @var string
	 */
	protected $class;
	protected $file;
	protected $confParams;
	protected $usesCache = false;
	
	/**
	 * Execution stack for the event before the current module
	 * @access protected
	 * @var array
	 */
	protected $beforeExecuteListener=array();
	/**
	 * Execution stack for the event after the current module
	 * @access protected
	 * @var array
	 */
	protected $afterExecuteListener=array();
	/**
	 * XML containing the configuration of this module
	 * @access protected
	 * @var XMLElement
	 */
	protected $xml_configuration;
	/**
	 * Was this module executed
	 * @access protected
	 * @var boolean
	 */
	protected $executed = false;
	
	protected $htmlProducer=false;
	protected $cacheCreateMode = false;
	
	
	/**
	 * Defines name and directory for the module
	 *
	 * @param $directory		directory containing the module
	 * @param $name			name of the module
	 */
	public function __construct($file,$class,$name){
		$this->file=$file;
		$this->name=$name;
		$this->class=$class;
	}
	public function setConfParams($params){
		$this->confParams=$params;
	}
	public function getConfParam($key){
	    $merge=array_merge($this->confParams,($this->htmlProducer==null?array():$this->htmlProducer->getConfParams()));
	    return $merge[$key];
	}
	
	/**
	 * Called at the end of configuration loading (registers the module for Htaccess writing and then unsets the xml_configuration)
	 *
	 */
	public function endConfiguration(){
		if (!empty($this->htaccessOrder)){
			HTAccess::addHTAccessElement($this,$this->htaccessOrder);
		}
		unset($this->xml_configuration);
	}
	/**
	 * generates the HTML content of this module for the given hook and instance : Should not be called before execution
	 *
	 *@param	$currentHook		hook requesting HTML
	 *@param	$instance			instance of the hook
	 *@returns		html code for the given hook and instance
	 */
	public function toHTML($currentHook,$instance){
		ob_start();
		include(Ressource::getCurrentTemplate()->getFile($this->getTemplateFile($instance))->toURL());
		return ob_get_clean();
	}
	
	public function getTemplateFile($instance){
	    return new File("html/module",$this->class."_".$instance.".phtml",false);
	}
	public function getCacheFile($baseFile){
	    $filename=pathinfo($baseFile->getFile());
	    return new File (".cache/template/".Ressource::getCurrentTemplate()->getName()."/".$baseFile->getDirectory(),$filename['filename'].'-'.md5(implode($this->getCacheValues())).".".$filename['extension'],$baseFile->isFolder());
	}
	public function getCacheValues(){
	    return array();
	}
	public function toHTMLCache($currentHook,$instance){
	    ob_start();
	    $baseFile = $this->getTemplateFile($instance);
	    if (Ressource::getConfiguration()->getValue('cacheActive')==1&&$this->usesCache){
	        $fileToUse=$this->getCacheFile($baseFile);
	        if (!$fileToUse->exists()){
	            $this->cacheCreateMode=true;
	            @mkdir($fileToUse->getDirectory(),0777,true);
	            echo $this->toHTML($currentHook,$instance);
	            $toWrite = ob_get_clean();
	            $fileToUse->write($toWrite);
	            $this->cacheCreateMode=false;
	            ob_start();
	        }
	        include($fileToUse->toURL());
	        
	    }else {
	        echo $this->toHTML($currentHook,$instance);
	    }
	    return ob_get_clean();
	}
	public function addJS($file,$silent){
	    if ($this->cacheCreateMode){
	        print('<?php $this->addJS(new File(\''.$file->getDirectory().'\',\''.$file->getFile().'\',\''.var_export($file->isFolder(),true).'\'),'.var_export($silent,true).'); ?>');
	    }
	    Ressource::getCurrentPage()->addJS($file,$silent);
	}
	public function addCSS($file,$silent){
		if ($this->cacheCreateMode){
	        print('<?php $this->addCSS(new File(\''.$file->getDirectory().'\',\''.$file->getFile().'\',\''.var_export($file->isFolder(),true).'\'),'.var_export($silent,true).'); ?>');
	    }
	    Ressource::getCurrentPage()->addCSS($file,$silent);
	}
	
	public function setCurrentHTMLProducer($htmlProducer){
	    $this->htmlProducer=$htmlProducer;
	}
	
	/**
	 * executes the module : First executes modules registered to execute before the module, then executes the module and then executes modules registered to execute after the module
	 */
	public final function execution(){
		if ($this->executed){
			Log::Warning('A module should not be executed Twice !!!!');
		}
		$this->propagateBeforeExecute();
		$this->execute();
		$this->propagateAfterExecute();
		$this->executed=true;
	}
	/**
	 * inits the module (does nothing, is to be overriden if need be)
	 */
	public function init(){
	}
	/**
	 * Core execution of the module (does nothing, is to be overriden if need be)
	 */
	public function execute(){
	}
	/**
	 * Returns Htaccess  Content for this module
	 */
	public function getHtaccess(){
		return "\n\n".'# Ajout HTACCESS Module '.$this->name;
	}
	/**
	 * Returns true if module is installed
	 * @return true if module is installed
	 */
	public function isInstalled(){
		return $this->installed;
	}
	/**
	 * Defines if the module is installed
	 * @param	$installed  true if module is installed
	 */
	public function setInstalled($installed){
		if ($installed){
			Log::LogData("Module ".$this->name." is installed for page ".Ressource::getCurrentPage()->getName(),2);
		}
		$this->installed=$installed;
	}
	/**
	 * Returns the name of the module
	 * @return		the name of the module
	 */
	public function getName(){
		return $this->name;
	}
	/**
	 * Allows the current module to import a class
	 * @param		$class		class of the module to load
	 */
	public function importClasses(){
		Module::addClassesToLoad(Classe::parseDirectory($this->file->getDirectory()."/class"));
	}
	
	/**
	 * Adds the current module to global execution stack TODO : should not write directly to the execution stack
	 */
	protected function addToGlobalExecuteStack(){
		self::$executeStack[]=$this;
	}
	/**
	 * TODO : use a global event manager
	 */
	private function propagateBeforeExecute(){
		foreach($this->beforeExecuteListener as $listener){
			$functionToExecute=$listener->beforeExecutePerformed;
			$functionToExecute($this,$listener->getListeningObject());
		}
	}
	/**
	 * TODO : use a global event manager
	 */
	private function propagateAfterExecute(){
		foreach($this->afterExecuteListener as $listener){
			$functionToExecute=$listener->afterExecutePerformed;
			$functionToExecute($this,$listener->getListeningObject());
		}
	}
	/**
	 * TODO : use a global event manager
	 */
	public function addBeforeExecuteListener($beforeExecuteListener){
		$this->beforeExecuteListener[]=$beforeExecuteListener;
	}
	/**
	 * TODO : use a global event manager
	 */
	public function addAfterExecuteListener($afterExecuteListener){
		$this->afterExecuteListener[]=$afterExecuteListener;
	}
}
