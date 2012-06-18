<?php
/**
 * Wrapper for elements of the file system, like in any UNIX, a folder is considered a file. A file is composed of a directory and a file name.
 * 
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */
	class CoreFile{

    /**
     * Name of the file
     * @access private
     * @var string
     */
	public $file;
	public $directory;
    /**
     * Name of the folder containing this directory
     * @access private
     * @var string
     */
	private $folder;
    /**
     * Is this file a folder ? Used to cache the result of isDirectory()
     * @access private
     * @var boolean
     */
	private $isFolder=null;
    /**
     * Extension for the current file. Used to cache the result of getExtension
     * @access private
     * @var string
     */
	private $extension=null;
	
	

	/**
    * Builds a file Objects
	* @param $folder	 	name of the folder containing the file
	* @param $file	 		name of the file
    */
	public function __construct($folder,$file,$isFolder){
		$this->file = $file;
		$this->directory = $folder;
		$this->isFolder=$isFolder;
	}
	/**
    * returns the name of the current file
	* @return the name of the current file
    */
	public function getFile(){
		return $this->file;
	}
	/**
    * USES CACHE : returns true if this file is a folder (TODO : Check if it should be named isFolder)
	* @return true if this file is a folder
    */
	public function isFolder(){
		return $this->isFolder;
	}
	/**
    * USES CACHE : returns true if this file is a true file (not a folder) (TODO : Check if it should be named isFolder)
	* @return true if this file is a true file
    */
	public function isFile(){
		return !$this->isFolder();
	}
	/**
    * SHOULD USE CACHE : returns true if this file exists
	* @return true if this file exists
    */
	public function exists(){
		return file_exists((!empty($this->directory)?$this->directory."/":"").$this->file);
	}
	/**
    * returns the name of the directory containing the file (A string : TODO : check if it should return an object)
	* @return the name of the directory containing the file 
    */
	public function getDirectory(){
		return $this->directory;
	}
	/**
    * returns the name of a file without extension
	* @param $filename	 	full name of a file
	* @return the name of a file without extension
    */
	public static function stripExtension($filename){
		$info = pathinfo($filename);
		return $info['filename'];
	}
	/**
    * returns the extension of a file
	* @param $filename	 	full name of a file
	* @return the extension of a file
    */
	public static function getExtensionStatic($filename){
		$info = pathinfo($filename);
		return strtolower(array_key_exists('extension',$info)?$info['extension']:"");
	}
	/**
    * returns the extension of the current file
	* @return the extension of the current file
    */
	public function getExtension(){
		if ($this->extension==null){
			 $this->extension=self::getExtensionStatic($this->file);
		}
		return $this->extension;
	}
	
	
	/**
    * returns the full url to access this file
	* @return the full url to access this file
    */
	public function toURL(){
		return $this->toURLAppendToDirectory("");
	}
	/**
    * returns the full url to access this file, right appending a name the folder
	* @return the full url to access this file, right appending a name the folder
    */
	public function toURLAppendToDirectory($value){
		return (!empty($this->directory)?$this->directory."/":"").$value.$this->file;
	}
	/**
    * TODO : change the location of this method (SHOULD BE IN override)
	* @return 
    */
	public function toFullURL(){
		return '/'.Ressource::getConfiguration()->getValue("AliasName").'/'.Ressource::getConfiguration()->getValue("SoftwareDirectory").'/'.$this->toURL();
	}
	/**
    * returns a file object created from URL
	* @param $url	 	url of the file
	* @return a file object created from URL
    */
	public static function createFromURL($url){
	    if (is_dir(Ressource::getConfiguration()->getValue("baseDirectory").'/'.$url)){
	        return new File($url,"",true);
	    }else {
	        return new File(dirname($url),basename($url),false);
	    } 
	}
	/**
    * write to file (TODO : specify the type of access mode)
	* @param $toWrite	 	string to write to file
    */
	public function write($toWrite){
		$fh = fopen($this->directory.'/'.$this->file, 'w');
		fwrite($fh, $toWrite);
		fclose($fh);
	}
	/**
    * TODO : check use and name
    */
	public function appendRightToDirectory($folder){
		return new File($this->folder.$folder,$this->file,$this->isFolder);
	}
	public function appendLeftToDirectory($folder){
		return new File($folder.$this->folder,$this->file,$this->isFolder);
	}
}
?>
