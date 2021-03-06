<?php
/**
* File class : may contain a file or a directory (just like the Unix File Concept)
*/
	class CoreFile{
	/**
	* The filename
	*/
	public $file;
	/**
	* The directory name
	*/
	public $directory;
	/**
	* True if the File is a directory, False otherwise 
	*/
	private $isFolder=null;
	/**
	* The extension of the file
	*/
	private $extension=null;
	
	private $appendFileHandler;
	/**
	* Initialises the directory name, filename, and isFolder
	* @param string $folder The directory name
	* @param string $file The filename
	* @param boolean $isFolder True if the file is a directory, false Otherwise
	*/
	public function __construct($folder,$file,$isFolder){
		$this->file = $file;
		$this->directory = $folder;
		$this->isFolder=$isFolder;
	}
	/**
	* Returns the filename
	* @return string filename
	*/
	public function getFile(){
		return $this->file;
	}
	/**
	* Returns true if the file is a directory, false otherwise
	* @return boolean  true if the file is a directory, false otherwise
	*/
	public function isFolder(){
		return $this->isFolder;
	}
	/**
	* Returns false if the file is a directory, true otherwise
	* @return boolean false if the file is a directory, true otherwise
	*/
	public function isFile(){
		return !$this->isFolder();
	}
	/**
	* Returns true if the file exists
	* @return boolean true if the file exists
	*/
	public function exists(){
		return file_exists((!empty($this->directory)?$this->directory."/":"").$this->file);
	}
	/**
	* Returns the name of the directory
	* @return string  the name of the directory
	*/
	public function getDirectory(){
		return $this->directory;
	}
	/**
	* Returns the filename without the extension
	* @return string the filename without the extension
	* @param string $filename the filename to use
	*/
	public static function stripExtension($filename){
		$info = pathinfo($filename);
		return $info['filename'];
	}
	/**
	* Returns the extension of a filename
	* @return string the extension of a filename
	* @param string $filename the filename
	*/
	public static function getExtensionStatic($filename){
		$info = pathinfo($filename);
		return strtolower(array_key_exists('extension',$info)?$info['extension']:"");
	}
	/**
	* Returns the extension of the current File
	* @return string the extension of the current File
	*/
	public function getExtension(){
		if ($this->extension==null){
			 $this->extension=self::getExtensionStatic($this->file);
		}
		return $this->extension;
	}
	/**
	* Returns the URL of the file
	* @return string the URL of the file
	*/
	public function toURL(){
		return $this->toURLAppendToDirectory("");
	}
	/**
	* Returns the URL of the file while appending a value to the directory name's end
	* @return string  the URL of the file while appending a value to the directory name's end
	* @param string $value the value to append to directory name
	*/
	public function toURLAppendToDirectory($value){
		return (!empty($this->directory)?$this->directory."/":"").$value.$this->file;
	}
	/**
	* Returns the absolute URL of the file
	* Uses configuration values : AliasName , SoftwareDirectory
	* @return string  the absolute URL of the file
	*/
	public function toFullURL(){
		return Resource::getConfiguration()->getValue("AliasName").Resource::getConfiguration()->getValue("SoftwareDirectory").'/'.$this->toURL();
	}
	/**
	* Creates a File Object From URL
	* @return File The File Object corresponding to the URL
	* @param string $url The URL to analyse
	*/
	public static function createFromURL($url){
	    if (is_dir($url)){
	        return new File($url,"",true);
	    }else {
	        return new File(dirname($url),basename($url),false);
	    } 
	}
	/**
	* Writes data to the current File
	* @param string $toWrite the data to write to file
	*/
	public function write($toWrite){
		$fh = fopen($this->directory.'/'.$this->file, 'w');
		fwrite($fh, $toWrite);
		fclose($fh);
	}
	
	public function read(){
		return file_get_contents($this->directory.'/'.$this->file);
	}
	/**
	* Writes data to the current File
	* @param string $toWrite the data to write to file
	*/
	public function append($toWrite){
		if (empty($this->appendFileHandler)){
			$this->appendFileHandler = fopen($this->directory.'/'.$this->file, 'a+');
			flock($this->appendFileHandler, LOCK_UN);
		}
		fwrite($this->appendFileHandler, $toWrite);
	}
	/**
	* Appends a string to the end of the directory
	* @return File File containing the modified directory
	* @param string $folder string to append to the directory name's end
	*/
	public function appendRightToDirectory($folder){
		return new File($this->folder.$folder,$this->file,$this->isFolder);
	}
	/**
	* Appends a string to the start of the directory
	* @return File File containing the modified directory
	* @param string $folder string to append to the directory name's end
	*/
	public function appendLeftToDirectory($folder){
		return new File($folder.$this->folder,$this->file,$this->isFolder);
	}
	public static function findFile($directory,$name){
	    $directory=glob($directory."/*");
	    foreach($directory as $subdir){
	        if (is_dir($subdir)&&$subdir!="."&&$subdir!=".."){
	            try {
	             	$file = File::findFile($subdir,$name);
	             	return $file;
	            }catch (Exception $exc){}
	        }else {
	            $path=pathinfo($subdir);
	            if ($path['basename']==$name){
	                return File::createFromURL($subdir);
	            }
	        }
	    }
	    throw new Exception("File not found");
	}
	public function listFiles(){
	    $files = glob($this->toURL().'/*');
	    $toReturn = array();
	    foreach($files as $file){
	        if ($file!="."&&$file!=".."){
	            $toReturn[] = File::createFromURL($file);
	        }
	    }
	    return $toReturn;
	}
	public function delete(){
	    if ($this->isFile()){
	        unlink($this->toURL());
	    }else {
	        $files = $this->listFiles();
	        foreach($files as $file){
	            $file->delete();
	        }
	        rmdir($this->toURL());
	    }
	}
}


?>
