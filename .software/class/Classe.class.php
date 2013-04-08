<?php
/**
* Base Class mostly static that loads others system classes
* 
* 
* 
*/
class Classe {
	/**
	* Parses recursively a directory and returns all the classes that can be loaded
	* @return array array of all the classes that can be loaded
	* @param string $directoryName directory to parse
	*/
	public static function parseDirectory($directoryName){
		$directory = glob ( $directoryName."/*" );
		$toInclude = array();
		foreach ( $directory as $file ) {
			if (is_dir($file)){
				$toInclude=array_merge($toInclude,Classe::parseDirectory($file));
			}else if (substr($file,-10)==".class.php") {
				$toInclude[substr($file,strlen($directoryName)+1,-10)]=$file;
			}
		}
		return $toInclude;
	}
	/**
	* Includes all the classes from a Directory and its children
	* @param string $directoryName Name of the directory to use
	*/
	public static function includeDirectory($directoryName){
		self::includeClasses(Classe::parseDirectory($directoryName));
	}
	/**
	* Include the classes given as parameters
	* there is an auto override, if the class is called Corexxx, 
	* the system will look for /override/class/xxx.class.php, if it exists, it will be included otherwise, a class will be created that will extend the Corexxx class.
	* @param array $classes Array of classes to include
	*/
	public static function includeClasses($classes){
	    $filenameToInclude=array_keys($classes);
	    sort ( $filenameToInclude );
	    foreach($filenameToInclude as $fullclassname){
	        include_once($classes[$fullclassname]);
	        if (substr ( $fullclassname, 0, 4 ) == "Core") {
	            $classnameArray = explode ( "_", $fullclassname );
	            $classname = substr($classnameArray [count ( $classnameArray ) - 1],4);
	    
	            // TO DO not a good way to do things
	            if (file_exists ( "override/class/" . $classname . ".class.php" )) {
	                include_once ("override/class/" . $classname . ".class.php");
	            } else {
	                if (!file_exists(".cache/class/".$fullclassname . ".class.php")){
	                    $class = new ReflectionClass("Core" . $classname);
	                    file_put_contents (".cache/class/".$fullclassname . ".class.php","<?php ".($class->isAbstract()?"abstract":"")." class " . $classname . " extends Core" . $classname . " {} ?>");
	                }
	                include_once(".cache/class/".$fullclassname . ".class.php");
	            }
	        }
	    }
	}
	/**
	* Includes all the classes of the system (to be found in class/)
	*/
	public static function includeAll() {
		spl_autoload_register(array("Classe","autoload"));
		Classe::includeDirectory("class");
	}
	/**
	* Manages Autoload, if a class is not found, this method will be called, it searches in all modules for the class.
	* This method should never be called because modules should import their classes properly.
	* The only good reason is when SessionStart requires the import of classes before the call to Module::init
	* @param string $class Name of the missing class
	*/
	public static function autoload($class){
		Log::GlobalLogData("Looking for ".$class,Log::$LOG_LEVEL_DEBUG);
	    $fileCache=new File(".cache/class","autoLoad.php",false);
	    @include($fileCache->toURL());
	    if (!isset($autoloadCache)){
	        $autoloadCache=array();
	    }
	    if (array_key_exists($class,$autoloadCache)){
	    	if (file_exists($autoloadCache[$class])){
	    		include_once($autoloadCache[$class]);
	    	}else {
	    		unset($autoloadCache[$class]);
	    	}
	    }else {
	        try {
	        	$file = File::findFile("module",$class.".class.php");
	        	$url=$file->toURL();
	        	include_once($url);
	        	$autoloadCache[$class]=$url;
	        	@mkdir($fileCache,0777,true);
	        	$fileCache->write('<?php $autoloadCache='.var_export($autoloadCache,true).';');
	        }catch (Exception $exc){
	        	try{
	        		Model::getModel($class)->includeClass();
	        	}catch (Exception $exc){
	        	}
	        }
	    }
	}
}



