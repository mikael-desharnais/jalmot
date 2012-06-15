<?php
/**
 * static class that encapsulate different file formats
 * 
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */
class CoreImage{
	 /**
     * Does nothing
     */
	public function __construct(){
		
	}
	
	/**
    * Returns true if the file is an  image (which type is known)
	* @param $file	 	File to test
	* @return true if the file is an image
    */
	public static function isImage($file){
		return in_array($file->getExtension(),array("jpg","jpeg","gif","png"));
	}
	/**
    * Returns the image object corresponding to a file
	* @param $file	 	File corresponding to the image
	* @return  Image object corresponding to the file
    */
	public static function getImage($file){
		if (in_array($file->getExtension(),array("jpg","jpeg"))){
			return new ImageJPG($file);
		}
		else if (in_array($file->getExtension(),array("gif"))){
			return new ImageGIF($file);
		}
		if (in_array($file->getExtension(),array("png"))){
			return new ImagePNG($file);
		}
	}
}