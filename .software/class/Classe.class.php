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
	    
	            if (file_exists ( "override/class/" . $fullclassname . ".class.php" )) {
	                include_once ("override/class/" . $fullclassname . ".class.php");
	            } else {
	                if (!file_exists(".cache/class/".$fullclassname . ".class.php")){
	                    file_put_contents (".cache/class/".$fullclassname . ".class.php","<?php class " . $classname . " extends Core" . $classname . " {} ?>");
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
		Classe::includeDirectory("class");
	}
	/**
	* Manages Autoload, if a class is not found, this method will be called, it searches in all modules for the class.
	* This method should never be called because modules should import their classes properly.
	* The only good reason is when SessionStart requires the import of classes before the call to Module::init
	* @param string $class Name of the missing class
	*/
	public static function autoload($class){
	    $fileCache=new File(".cache/class","autoLoad.php",false);
	    @include($fileCache->toURL());
	    if (!isset($autoloadCache)){
	        $autoloadCache=array();
	    }
	    if (array_key_exists($class,$autoloadCache)){
	        include_once($autoloadCache[$class]);
	    }else {
	        try {
	        	$file = File::findFile("module",$class.".class.php");
	        	$url=$file->toURL();
	        	include_once($url);
	        	$autoloadCache[$class]=$url;
	        	@mkdir($fileCache,0777,true);
	        	$fileCache->write('<?php $autoloadCache='.var_export($autoloadCache,true).';');
	        }catch (Exception $exc){
	            
	        }
	    }
	}
}



