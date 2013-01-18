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
		$primary_keys=$line->getPrimaryKeys();
		$toReturn="";
		foreach($primary_keys as $name=>$value){
			$toReturn.=($toReturn==""?"":"&")."id[".$name."]=".$value;
		}
		return $toReturn;
	}
}
