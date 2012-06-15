<?php
/**
 * class managing GIF files
 * 
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 * 
 * TODO : Truly managing GIF Images / Extend CoreImage
 */
class CoreImageGIF{
	/**
     * Stores the file object
     * @param	File object
     */
	public function __construct($file){
		$this->file=$file;
	}
}