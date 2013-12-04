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
class CoreImageGIF extends Image{
	/**
     * Stores the file object
     * @param	File object
     */
	public function __construct($file){
		$this->file=$file;
	}
	public function writeRawImageToFile($image,$file){
		if (!file_exists($file->getDirectory())){
			mkdir($file->getDirectory(),0777,true);
		}
		imagegif($image,$file->toURL());
	}
	public function getImageContent(){
		return imagecreatefromgif($this->file->toURL());
	}
	public function getThumb($max_width,$max_height){
		$src_width=0;
		$src_height=0;
		
		$baseImage=imagecreatefromgif($this->file->toURL());
		$size_array=getimagesize($this->file->toURL());
		$src_width=$size_array[0];
		$src_height=$size_array[1];
		
		
		$src_ratio=$src_width/$src_height;
		$max_ratio=$max_width/$max_height;
		
		if ($src_ratio>$max_ratio){
			$dest_width=$max_width;
			$dest_height=$max_width/$src_ratio;
		}
		else {
			$dest_height=$max_height;
			$dest_width=$max_height*$src_ratio;
		}
		$dest_image = imagecreatetruecolor($dest_width,$dest_height);
		imagecopyresampled($dest_image, $baseImage, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height);
		return $dest_image;
	}
	public function getMimeType(){
		return "image/gif";
	}
}