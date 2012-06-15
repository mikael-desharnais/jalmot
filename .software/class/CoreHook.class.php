<?php
/**
 * Hook object. A hook is a collector of HTML to be displayed somewhere. Modules have to register for each Hook they want to append HTML to. On displaying of a hook, it calls all registered modules and asks for content
 * 
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */
	class CoreHook{
    /**
     * List of all hooks
     * @access private
     * @var array
     */
		private static $hooklist=array();
    /**
     * name of the hook
     * @access private
     * @var string
     */
		private $name;
    /**
     * list of elements witch will append HTML to this hook
     * @access private
     * @var array
     */
		private $listenerList=array();


	/**
    * Builds a hook (the name has to be unique)
	* @param $name	 	name of the hook
    */
		public function __construct($name){
			$this->name=$name;
		}
	/**
    * returns a hook object considering its name (TODO : SHOULD perhaps throws an exeception instead of create an row in logs)
	* @param $name	 	name of the hook required
	* @return a hook object considering its name
    */
		public static function getHook($name){
			if (!array_key_exists($name,self::$hooklist)){
				self::initHook($name);
			}
			return self::$hooklist[$name];
		}
		
		public static function hookExists($name){
		    if (!array_key_exists($name,self::$hooklist)){
		        if (Ressource::getCurrentPage()->getHookDescriptionFile(self::getHookFileName($name),true)->exists()){
		            return true;
		        }
		        return false;
		    }else {
		        return true;
		    }
		}

	/**
    * returns the HTML content of the hook. calls all registered modules and witch return their generated content
	* @return the HTML content of the hook
    */
		public function toHTML(){
			$toReturn="";
			foreach($this->listenerList as $val){
				$toReturn.=$val->toHTML($this->name);
			}
		return $toReturn;
		}

	/**
    * Allows a module or any element to register a content supplier for this Hook
	* @param $listener	 	content supplier for this hook
    */
		public function addToHTMLListener($listener){
			$this->listenerList[]=$listener;
		}
	/**
    * Init all hooks : parses the config hook folder, checks the name of hook written in XML (filename=name in XML), registers modules for the hook (based on the content of the config file);
    */
		public static function getHookFileName($name){
		    $name_array=explode('.',$name);
		    $name_instance=$name_array[1];
		    return  $name_instance;
		}
		public static function initHook($name){
			$xml_url=Ressource::getCurrentPage()->getHookDescriptionFile(self::getHookFileName($name));
			$xml = XMLDocument::parseFromFile($xml_url);
			self::initHookFromXML($name,$xml);
		}
		public static function initHookFromXML($name,$xml){
			if ($name!=$xml->name){
				Log::Error('The hook description filename must be the name of the module '.$name."!=".$xml->name);
			}
			self::$hooklist[$xml->name.""]=new Hook($xml->name."");
			foreach ($xml->modules->module as $module){
				if (empty($module->name)){
					Log::Error('There is no name for module in hook '.$xml->name);
				}
				else {
					if (empty($module->instance)){
						Log::Error('There is no instance name for module '.$module->name.' in hook '.$xml->name);
					}
					else {
					    $moduleHTMLProducer=new ModuleHTMLProducer(Module::getInstalledModule($module->name.""),$module->instance."");
					    $moduleHTMLProducer->setConfParams(XMLParamsReader::read($module));
						self::$hooklist[$xml->name.""]->addToHTMLListener($moduleHTMLProducer);
					}
				}
			}
		}
	}
?>
