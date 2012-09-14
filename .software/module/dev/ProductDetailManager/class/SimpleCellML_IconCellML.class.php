<?php

class IconCellML extends SimpleCellML {

	public function toHTML($element){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/IconCellML.phtml"));
		return ob_get_clean();
	}
}
