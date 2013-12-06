<?php

class DateCellML extends SimpleCellML {
    
	public function toHTML($line){
		ob_start();
		include(Resource::getCurrentTemplate()->getURL("html/module/ModelListing/DateCellML".(empty($this->instance)?"":"_".$this->instance).".phtml"));
		return ob_get_clean();
	}
}
