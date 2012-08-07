<?php
/**
* Base bloc for the entire system.Modules are what act/display .... everything
* A module goes through several steps first it gets  instanciated (the order of instanciation is unknown)
* Then, it is initiated (At this step, all modules exist)
* Then it is executed according to the way it chose to be executed
*/
class CoreModule{
	/**
	* List of all modules
	*/
	private static $module=array();
	/**
	* Standard stack of execution, contains references to modules to be executed in the basic execution flow
	*/
	private static $executeStack=array();
	/**
	* list of classes to load at the end of the INIT step
	*/
	private static $classesToLoad=array();
	/**
	* Allows modules to be declared as installed (so as to be reused later)
	* @param Module $cModule adds a module to the list of installed module
	*/
	private static function addInstalledModule($cModule){
		self::$module[$cModule->getName()]=$cModule;
	}
	/**
	* Returns an installed module given its name
	* If the module can't be found, a Log error is executed
	* @return Module the module required, if the module is not found, an error is logged
	* @param string $moduleName name of the instance of the module to return
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
	Log::LogData("Module before Init",Log::$LOG_LEVEL_INFO);
	self::initModule();
	Log::LogData("Module before Execution",Log::$LOG_LEVEL_INFO);
	self::executeModule();
	}
	/**
	* Loads a module by class
	* @return Module A new instance of the module
	* @param string $instancename name of the module instance
	* @param string $class Class of the module
	*/
	public static function loadModule($instancename,$class){
		$filename=self::findModule($class);
		include_once($filename->toURL());
		$object=new $class($filename,$class,$instancename);
		CoreModule::addInstalledModule($object);
		return $object;
	}
	/**
	* Finds the file containing a module by classname
	* @return File the file containing a module by classname
	* @param string $class class of the module
	*/
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
	/**
	* Loops over installed modules and inits them
	* At the end of the loop, includes the classes
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
	/**
	* adds classes to Load at the end of the INIT step
	* @param array $classes array of classes to load at the end of the INIT step
	*/
	protected static function addClassesToLoad($classes){
	    self::$classesToLoad=array_merge(self::$classesToLoad,$classes);
	}
	/**
	* name of the module
	*/
	protected $name;
	/**
	* base directory for this module
	*/
	protected $class;
	/**
	* File containing this module
	*/
	protected $file;
	/**
	* Configuration parameters of this module
	*/
	protected $confParams;
	/**
	* True if this module uses a cache for HTML.
	*/
	protected $usesCache = false;
	/**
	* Execution stack for the event before the current module
	*/
	protected $beforeExecuteListener=array();
	/**
	* Execution stack for the event after the current module
	*/
	protected $afterExecuteListener=array();
	/**
	* XML containing the configuration of this module
	*/
	protected $xml_configuration;
	/**
	* Was this module executed
	*/
	protected $executed = false;
	/**
	* The current HTML producer for this module
	* TODO : try to find something nicer
	*/
	protected $htmlProducer=false;
	/**
	* Is set to true when cache is being generated
	*/
	protected $cacheCreateMode = false;
	/**
	* Defines file, name and class for the module
	* @param File $file file containing the module
	* @param string $class  =class of the module
	* @param string  $name name of the instance of module
	*/
	public function __construct($file,$class,$name){
		$this->file=$file;
		$this->name=$name;
		$this->class=$class;
	}
	/**
	* Defines the Configuration Parameters Array
	* @param array $params the Configuration Parameters Array
	*/
	public function setConfParams($params){
		$this->confParams=$params;
	}
	/**
	* Returns a configuration parameter value according to its key
	* @return string the configuration parameter value corresponding to the given key
	* @param string $key the key to the configuration parameter
	*/
	public function getConfParam($key){
	    $merge=array_merge($this->confParams,($this->htmlProducer==null?array():$this->htmlProducer->getConfParams()));
	    return $merge[$key];
	}
	/**
	* Called at the end of configuration loading (registers the module for Htaccess writing and then unsets the xml_configuration)
	*/
	public function endConfiguration(){
		if (!empty($this->htaccessOrder)){
			HTAccess::addHTAccessElement($this,$this->htaccessOrder);
		}
		unset($this->xml_configuration);
	}
	/**
	* generates the HTML content of this module for the given hook and instance : Should not be called before execution
	* @return string the HTML corresponding to this module
	* @param string $currentHook The name of the hook being executed
	* @param string $instance The name of the HTML instance being executed
	*/
	public function toHTML($currentHook,$instance){
		ob_start();
		include(Ressource::getCurrentTemplate()->getFile($this->getTemplateFile($instance))->toURL());
		return ob_get_clean();
	}
	/**
	* Returns the phtml File to use for HTML output
	* @return File the phtml File to use for HTML output
	* @param string $instance the name of the HTML instance
	*/
	public function getTemplateFile($instance){
	    return new File("html/module",$this->class."_".$instance.".phtml",false);
	}
	/**
	* Returns the file containing the HTML cache
	* @return File the file containing the HTML cache
	* @param File $baseFile the phtml file that would be used without cache
	*/
	public function getCacheFile($baseFile){
	    $filename=pathinfo($baseFile->getFile());
	    return new File (".cache/template/".Ressource::getCurrentTemplate()->getName()."/".$baseFile->getDirectory(),$filename['filename'].'-'.md5(implode($this->getCacheValues())).".".$filename['extension'],$baseFile->isFolder());
	}
	/**
	* Returns the values to take into account for cache management
	* @return array the values to take into account for cache management
	*/
	public function getCacheValues(){
	    return array();
	}
	/**
	* generates the HTML content of this module for the given hook and instance : Should not be called before execution
	* This method uses Cache if possible
	* @return string  the HTML content of this module for the given hook and instance
	* @param string $currentHook the phtml File to use for HTML output
	* @param string $instance the name of the HTML instance
	*/
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
	/**
	* Adds JS File to the current page. Should be used instead of direct call to the page.
	* @param File $file JS file to add to page
	* @param boolean $silent true if no error will be triggered if no file was found
	*/
	public function addJS($file,$silent){
	    if ($this->cacheCreateMode){
	        print('<?php $this->addJS(new File(\''.$file->getDirectory().'\',\''.$file->getFile().'\',\''.var_export($file->isFolder(),true).'\'),'.var_export($silent,true).'); ?>');
	    }
	    Ressource::getCurrentPage()->addJS($file,$silent);
	}
	/**
	* Adds CSS File to the current page. Should be used instead of direct call to the page.
	* @param File $file CSS file to add to page
	* @param boolean $silent true if no error will be triggered if no file was found
	*/
	public function addCSS($file,$silent){
		if ($this->cacheCreateMode){
	        print('<?php $this->addCSS(new File(\''.$file->getDirectory().'\',\''.$file->getFile().'\',\''.var_export($file->isFolder(),true).'\'),'.var_export($silent,true).'); ?>');
	    }
	    Ressource::getCurrentPage()->addCSS($file,$silent);
	}
	/**
	* Defined the current HTML producer
	* @param HTMLProducer $htmlProducer the current HTMLProducer
	*/
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
		Log::LogData('Module '.$this->getName().' Start Execution',Log::$LOG_LEVEL_INFO);
		$this->propagateBeforeExecute();
		$this->execute();
		$this->propagateAfterExecute();
		Log::LogData('Module '.$this->getName().' End Execution',Log::$LOG_LEVEL_INFO);
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
	* @return string the string to be added to HTAccess
	*/
	public function getHtaccess(){
		return "\n\n".'# Ajout HTACCESS Module '.$this->name;
	}
	/**
	* Returns true if module is installed
	* @return boolean true if module is installed
	*/
	public function isInstalled(){
		return $this->installed;
	}
	/**
	* Defines if the module is installed
	* @param boolean $installed true if module is installed
	*/
	public function setInstalled($installed){
		if ($installed){
			Log::LogData("Module ".$this->name." is installed for page ".Ressource::getCurrentPage()->getName(),Log::$LOG_LEVEL_INFO);
		}
		$this->installed=$installed;
	}
	/**
	* Returns the name of the module
	* @return string The name of the module
	*/
	public function getName(){
		return $this->name;
	}
	/**
	* Allows the current module to import a class
	*/
	public function importClasses(){
		Module::addClassesToLoad(Classe::parseDirectory($this->file->getDirectory()."/class"));
	}
	/**
	* Adds the current module to global execution stack 
	*/
	protected function addToGlobalExecuteStack(){
		self::$executeStack[]=$this;
	}
	/**
	* Triggers a Before Execute event
	*/
	private function propagateBeforeExecute(){
		foreach($this->beforeExecuteListener as $listener){
			$functionToExecute=$listener->beforeExecutePerformed;
			$functionToExecute($this,$listener->getListeningObject());
		}
	}
	/**
	* Triggers an After Execute event
	*/
	private function propagateAfterExecute(){
		foreach($this->afterExecuteListener as $listener){
			$functionToExecute=$listener->afterExecutePerformed;
			$functionToExecute($this,$listener->getListeningObject());
		}
	}
	/**
	* Adds a listener to the Before Execute Event
	* @param EventListener $beforeExecuteListener the listener to the Before Execute Event
	*/
	public function addBeforeExecuteListener($beforeExecuteListener){
		$this->beforeExecuteListener[]=$beforeExecuteListener;
	}
	/**
	* Adds a listener to the After Execute Event
	* @param EventListener $afterExecuteListener the listener to the Before Execute Event
	*/
	public function addAfterExecuteListener($afterExecuteListener){
		$this->afterExecuteListener[]=$afterExecuteListener;
	}
}




