<?php
/**
* Manages a template
* 
* 
*/
class CoreTemplate{
	/**
	* name of the template
	* 
	*/
	private $name;
	/**
	* parent template
	* 
	*/
	private $parent;
	/**
	* Defines the name of the template
	* 
	* @param string $name name of the template
	*/
	public function __construct($name){
		$this->name = $name;
	}
	/**
	* Returns the name of the template
	* 
	* @return name of the template
	*/
	public function getName(){
		return $this->name;
	}
	/**
	* Defines the template's parent
	* @param Template Template $parent the template's parent
	*/
	public function setParent(Template $parent){
		$this->parent=$parent;
	}
	/**
	* Returns the URL of a file in the current template
	* 
	* @return string the URL of the file in the current template
	* @param string $file URL of the File outside the template
	* @param boolean $silent=false if false no error will be triggered if file not found
	*/
	public function getURL($file,$silent=false){
		$file = $this->getFile(File::createFromURL($file),$silent);
		return $file->toURL();
	}
	/**
	* Returns a file in the current template given the partial URL of the file : Searches in the current template then recursively in parent templates
	* 
	* @return File the file in the current templatefilein the current template
	* @param File $file File outside the template
	* @param boolean $silent=false if false no error will be triggered if file not found
	*/
	public function getFile($file,$silent=false){
		if (file_exists("template/".$this->name."/".$file->toURL())){
			return new File("template/".$this->name."/".$file->getDirectory(),$file->getFile(),$file->isFolder());
		}
		else {
			if (!empty($this->parent)){
				$toInclude=$this->parent->getFile($file,$silent);	
			}
			if (empty($toInclude)&&!$silent){
				print('<pre>');
				debug_print_backtrace();
				print("template/".$this->name."/".$file->toURL()." Introuvable dans le système de template");
				die();
			}
			else if(empty($toInclude)&&$silent){
				return new EmptyFile();
			}
			else {
				return $toInclude;
			}
		}
	}
	/**
	* Returns the current template
	* 
	* @return Template The current template
	*/
	public static function getCurrentTemplate(){
	    return new Template(Ressource::getConfiguration()->getValue('DefaultTemplate'));
	} 
}


?>
