<?php
/**
* class managing JPG files
* 
* 
* TODO : TO remove from COre
* 
*/
class CoreImageJPG extends Image{
	/**
	* TODO : TO remove from COre
	*/
	protected $file;
	/**
	* TODO : TO remove from COre
	*/
	protected $dimension;
	/**
	* Stores the file object
	* 
	*/
	public function __construct($file){
		$this->file=$file;
	}
	public function writeRawImageToFile($image,$file){
		if (empty($image)){
			return;
		}
		if (!file_exists($file->getDirectory())){
			mkdir($file->getDirectory(),0777,true);
		}
		imagejpeg($image,$file->toURL());
	}
	/**
	* Returns Exif data from a photo file (TODO : think about caching ???)
	* 
	*/
	public function getExif(){
		if (function_exists('exif_thumbnail')){
			return exif_read_data($this->file->toURL(), 0, true);
		}else {
			return array();
		}
	}
	public function getImageContent(){
		return imagecreatefromjpeg($this->file->toURL());
	}
	public function getThumb($max_width,$max_height){
		if (!$this->file->exists()){
			return;
		}
		$src_width=0;
		$src_height=0;
		if (function_exists('exif_thumbnail')){
			$stringImage=@exif_thumbnail($this->file->toURL(),$src_width,$src_height);
		}
		if (!empty($stringImage)&&$src_width>=$max_width&&$src_height>=$max_height){
			$baseImage=imagecreatefromstring($stringImage);
		}
		else {
			$baseImage=imagecreatefromjpeg($this->file->toURL());
			$size_array=getimagesize($this->file->toURL());
			$src_width=$size_array[0];
			$src_height=$size_array[1];
		}
		
		
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
		$exif=@$this->getExif();
		
		if ($exif!==false&&array_key_exists("IFD0", $exif)&&array_key_exists("Orientation", $exif["IFD0"])){
			$orientation = $exif["IFD0"]["Orientation"];
		}else {
			$orientation=1;
		}
		if ($orientation!=1){
		  $tableau_angle = array();
		  $tableau_angle[6]=-90;
		  $tableau_angle[8]=90;
		  $tableau_angle[3]=180;
		  $coul_blanc = @imagecolorallocate($baseImage, 0xFF, 0xFF, 0xFF);
		  $angle=$tableau_angle[$orientation];
		  $baseImage = @imagerotate($baseImage, $angle, $coul_blanc);
		  if (abs($tableau_angle[$orientation])==90){
			$temp = $dest_width;
			$dest_width = $dest_height;
			$dest_height = $temp;

			$temp = $src_width;
			$src_width = $src_height;
			$src_height = $temp;
			}
		}
		$dest_image = imagecreatetruecolor($dest_width,$dest_height);
		imagecopyresampled($dest_image, $baseImage, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height);
		return $dest_image;
	}
	/**
	* TODO : TO remove from COre
	*/
	public function getMimeType(){
		return "image/jpg";
	}
}


