<?php
/**
* Module that imports the Icon and ModelListingDescriptor for MediaManager
*/
class ProductDetailManager extends Module{
	/**
	* Imports the classes
	*/
	public function init(){
		parent::init();
		$this->importClasses();
	}
}


