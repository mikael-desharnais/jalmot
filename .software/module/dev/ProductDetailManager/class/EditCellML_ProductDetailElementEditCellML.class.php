<?php

class ProductDetailElementEditCellML extends EditCellML {
    
	public function toHTML($line){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/ProductDetailElementEditCellML.phtml"));
		return ob_get_clean();
	}

	public static function readFromXML($xml){
	    $cellDescriptor=new self($xml->key."");
	    $cellDescriptor->setConfParams(XMLParamsReader::read($xml));
		return $cellDescriptor;
	}
}
