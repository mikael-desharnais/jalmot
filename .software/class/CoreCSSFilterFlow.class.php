<?php
/**
 * Used to order/filter/concatenate/modify a set of CSS files given by the application and templates
 * May be overriden to implement other ways to filter/concatenate/modify CSS files
 * 
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */
class CoreCSSFilterFlow{
	/**
     * Does nothing, may be overriden if required
     */
	public function __construct(){

	}
	/**
    * orders/filters/concatenates/modifies CSS files
	* @param $CSSArray	 	Two dimensions array : 1st dimension : order of files : 2nd dimension : files 
	* @return Array with files ordered/filtered/concatenated/modified
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
	public function filterAndCompress($CSSArray){
		return $this->compress($this->filter($CSSArray));
	}
	public function compress($CSSArray){
	    return $CSSArray;
	}
}
?>
