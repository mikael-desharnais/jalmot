<?php

class CategoryEditCellML extends EditCellML {
    
	public function toHTML($line){
		ob_start();
		include(Resource::getCurrentTemplate()->getURL("html/module/ModelListing/CategoryEditCellML.phtml"));
		return ob_get_clean();
	}
}
