<?php
/**
 * HTACCESS Management : Modules declare in their config file if thay need to write things in HTACCESS. The declaration of the modules calls the adHTAccessElement of this class and when the generate method is called, the method calls all modules requesting the content to be written in htaccess.
 * 
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */
class CoreHTAccess{
	 /**
     * List of all elements that can generate HTACCESS content
     * @access private
     * @var array
     */
	private static $HTAccessElementList = array();
	/**
    * generates and write the HTACCESS content to file : mainly calls all registered modules.
    */
	public static function generate() {
		$fileContent="RewriteEngine On\n".
					  "RewriteCond 	%{ENV:REDIRECT_STATUS} 	200\n".
					  "RewriteRule 	.* 						- 							[L]\n\n".
					  "RewriteBase							/".Resource::getConfiguration()->getValue("aliasName")."/\n";
					  
		ksort(self::$HTAccessElementList);
		foreach(self::$HTAccessElementList as $array){
			foreach($array as $htaccess){
				$fileContent.=$htaccess->getHtaccess()."\n";
			}
		}
		$file=new File(Resource::getConfiguration()->getValue('baseDirectory'),".htaccess",false);
		$file->write($fileContent);
	}
	/**
    * Adds a HTACCESS code generator to the stack
	* @param $element	 	htaccess code generator
	* @param $order	 		order in calling the htaccess code generator
    */
	public static function addHTAccessElement($element,$order){
		self::$HTAccessElementList[$order][]=$element;
	}
}
?>
