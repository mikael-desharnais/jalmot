<?php

class DateCellML extends SimpleCellML {
    
	public function toHTML($line){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/DateCellML.phtml"));
		return ob_get_clean();
	}
}
