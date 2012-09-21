<?php

class ProductDetailElementDeleteCellML extends DeleteCellML {
    
    
	public function toHTML($line){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/ProductDetailElementDeleteCellML.phtml"));
		return ob_get_clean();
	}

	public static function readFromXML($xml){
	    $cellDescriptor=new self($xml->key."");
	    $cellDescriptor->setConfParams(XMLParamsReader::read($xml));
		return $cellDescriptor;
	}
}
