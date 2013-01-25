<?php

class CategoryDeleteCellML extends DeleteCellML {
    
    
	public function toHTML($line){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/CategoryDeleteCellML.phtml"));
		return ob_get_clean();
	}
}
