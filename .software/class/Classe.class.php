<?php
/**
 * Base Class mostly static that loads others system classes
 *
 * 
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */

class Classe {
	
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
	
	public static function includeDirectory($directoryName){
		self::includeClasses(Classe::parseDirectory($directoryName));
	}
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
	public static function includeAll() {
		Classe::includeDirectory("class");
	}

	public static function autoload($class){
	    $directory=glob("module/*");
	    foreach($directory as $subdir){
	        $subdirectory=glob($subdir."/*");
	        foreach($subdirectory as $module){
	           if (file_exists($module."/class/".$class.".class.php")){
	               include_once($module."/class/".$class.".class.php");
	           }
	        }
	    }
	}
}

