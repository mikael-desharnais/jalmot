<?php
/**
* Used to order/filter/concatenate/modify a set of CSS files given by the application and templates
* This one a very basic version, it does not concatenate the files. If you want a better version try the one given with the JalmotBootstrap
* 
* 
*/
class CoreCSSFilterFlow{
	/**
	* Does nothing
	* 
	*/
	public function __construct(){

	}
	/**
	* sorts and filters CSS files
	* 
	* @return array with files ordered/filtered/concatenated/modified
	* @param array $CSSArray two dimensions array containing the css files to filter
	*/
	public function filter($CSSArray){
		$tmp_array=array();
		foreach ($CSSArray as $order=>$CSSLevel){
			foreach ($CSSLevel as $count=>$CSSFile){
				if (in_array($CSSFile->toURL(),$tmp_array)){
					unset($CSSArray[$order][$count]);
				}else {
					$tmp_array[]=$CSSFile->toURL();
				}
			}
			$CSSArray[$order]=array_values($CSSArray[$order]);
		}
		ksort($CSSArray);
		return $CSSArray;
	}
	/**
	* Filters and Compresses a one dimension array containing CSS files
	* @return array Filtered and Compressed CSS files
	* @param array $CSSArray one dimension array containing CSS files to compress and filter
	*/
	public function filterAndCompress($CSSArray){
		return $this->compress($this->filter($CSSArray));
	}
	/**
	* Compresses a one dimension array containing CSS files
	* @return array Compressed CSS files
	* @param array $CSSArray one dimension array containing CSS files to compress
	*/
	public function compress($CSSArray){
	    return $CSSArray;
	}
}
?>
