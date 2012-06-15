<?php
/**
 * Manages a template
 *
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */
class CoreTemplate{
	    /**
	     * name of the template
	     * @access private
	     * @var string
	     */
	private $name;
	    /**
	     * parent template
	     * @access private
	     * @var Template
	     */
	private $parent;
	
	
	/**
	 * Defines the name of the template
	 * @param $name	 	name of the template
	 */
	public function __construct($name){
		$this->name = $name;
	}
	/**
	 * Returns the name of the template
	 * @return 	 	name of the template
	 */
	public function getName(){
		return $this->name;
	}
	public function setParent(Template $parent){
		$this->parent=$parent;
	}
	/**
	 * Returns the URL of a file in the current template
	 * @param $file	 	file to find in the current template
	 * @param $silent	true if the inability to find a file results in nothing / false for an error TODO : use LOG
	 * @return file URL in the current template
	 */
	public function getURL($file,$silent=false){
		$file = $this->getFile(File::createFromURL($file),$silent);
		return $file->toURL();
	}
	/**
	 * Returns a file in the current template given the partial URL of the file : Searches in the current template then recursively in parent templates
	 * @param $file	 	file to find in the current template
	 * @param $silent	true if the inability to find a file results in nothing / false for an error TODO : use LOG
	 * @return file in the current template
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
	 * @return The current template
	 */
	public static function getCurrentTemplate(){
	    return new Template(Ressource::getConfiguration()->getValue('DefaultTemplate'));
	} 
}
?>
