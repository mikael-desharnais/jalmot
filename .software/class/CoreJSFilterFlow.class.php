<?php
/**
* Could order/filter/concatenate/modify a set of JS files given by the application and templates
* This one a very basic version, it does not concatenate the files. If you want a better version try the one given with the JalmotBootstrap
* 
* 
* 
*/
class CoreJSFilterFlow{
	/**
	* Does nothing
	*/
	public function __construct(){

	}
	/**
	* sorts and filters CSS files
	* @return Array with files ordered/filtered/concatenated/modified
	* @param array $JSArray two dimensions array containing the js files to filter
	*/
	public function filter($JSArray){
		$tmp_array=array();
		foreach ($JSArray as $order=>$JSLevel){
			foreach ($JSLevel as $count=>$JSFile){
				if (in_array($JSFile->toURL(),$tmp_array)){
					unset($JSArray[$order][$count]);
				}else {
					$tmp_array[]=$JSFile->toURL();
				}
			}
		}
		ksort($JSArray);
		return $JSArray;
	}
	/**
	* Filters and Compresses a one dimension array containing JS files
	* @return array Filtered and Compressed CSS files
	* @param array $JSArray one dimension array containing CSS files to compress and filter
	*/
	public function filterAndCompress($JSArray){
	    return $this->compress($this->filter($JSArray));
	}
	/**
	* Compresses a one dimension array containing JS files
	* @return array Compressed CSS files
	* @param array $JSArray one dimension array containing CSS files to compress
	*/
	public function compress($JSArray){
	    return $JSArray;
	}
}


?>