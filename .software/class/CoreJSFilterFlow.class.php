<?php
/**
 * Used to order/filter/concatenate/modify a set of JS files given by the application and templates
 * May be overriden to implement other ways to filter/concatenate/modify JS files
 *
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */
class CoreJSFilterFlow{
	/**
     * Does nothing, may be overriden if required
     */
	public function __construct(){

	}
	/**
    * orders/filters/concatenates/modifies JS files
	* @param $JSArray	 	Two dimensions array : 1st dimension : order of files : 2nd dimension : files 
	* @return Array with files ordered/filtered/concatenated/modified
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
	
	public function filterAndCompress($JSArray){
	    return $this->compress($this->filter($JSArray));
	}
	public function compress($JSArray){
	    return $JSArray;
	}
}
?>