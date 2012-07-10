<?php
/**
* Used to read Configuration parameters from XML
*/
class CoreXMLParamsReader{
	/**
	* Reads Configuration parameters from XML
	* @return array Configuration Parameter Array
	* @param SimpleXMLElement $xml XML containing the Configuration Parameter XML
	*/
	public static function read($xml){
	    $params=array();
	    if ($xml->params->getName()=="params"){
	        foreach($xml->params->children() as $param){
	            $attribute=$param->attributes();
	            if ($attribute->type.""=="simple"){
	            	$params[$attribute->name.""]=$param."";
	            }
	            if ($attribute->type.""=="array"){
	                $params[$attribute->name.""]=array();
	                foreach($param->element as $element){
	                    $attribute_element=$element->attributes();
	                    $params[$attribute->name.""][$attribute_element->key.""]=$element."";
	                }
	            }
	        }
	    }
	    return $params;
	}
}

?>
