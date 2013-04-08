<?php

class CategoryLanguageCellML extends LanguageCellML{

	public function toHTML($element){
		$line_query=$element->lstLang();
		$line = $line_query->addConditionBySymbol('=',$line_query->getModel()->getField('idLang'), Ressource::getCurrentLanguage()->getId())
							->getModelDataElement();
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/CategoryCellML.phtml"));
		return ob_get_clean();
	}
	
	protected function getParams($line){
		$toReturn = array();
		foreach($line->getPrimaryKeys() as $key=>$value){
			$toReturn['id['.$key.']']=$value;
		}
		return $toReturn;
	}
}
