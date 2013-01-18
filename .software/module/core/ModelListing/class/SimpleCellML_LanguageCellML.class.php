<?php

class LanguageCellML extends SimpleCellML {

	public function toHTML($element){
		$line_query=$element->lstLang();
		$line = $line_query->addConditionBySymbol('=',$line_query->getModel()->getField('idLang'), Ressource::getCurrentLanguage()->getId())
							->getModelDataElement();
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/SimpleCellML.phtml"));
		return ob_get_clean();
	}
}
