<?php
/**
 * class managing PNG files
 * 
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 * 
 * TODO : Truly managing PNG Images / Extend CoreImage
 */
class CoreImagePNG{
	/**
     * Stores the file object
     * @param	File object
     */
	public function __construct($file){
		$this->file=$file;
	}
}