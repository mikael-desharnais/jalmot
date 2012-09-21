<?php

class ProductDetailElementIconCellML extends SimpleCellML {

	public function toHTML($element){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/ProductDetailElementIconCellML.phtml"));
		return ob_get_clean();
	}
}
