<?php

class LanguageCellML extends SimpleCellML {

	public function toHTML($element){
		$line = ModelLangRelation::getModelDataElement($element,$this->getKey());
		ob_start();
		include(Resource::getCurrentTemplate()->getURL("html/module/ModelListing/SimpleCellML.phtml"));
		return ob_get_clean();
	}
}
