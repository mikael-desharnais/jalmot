<?php

class EditCellML extends SimpleCellML {
    
	public function toHTML($line){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/EditCellML.phtml"));
		return ob_get_clean();
	}

	protected function getId($line){
		$primary_keys=$line->getPrimaryKeys();
		$toReturn=$line->getParentModel()->getName();
		foreach($primary_keys as $name=>$value){
			$toReturn.=($toReturn==""?"":"-").$value;
		}
		return $toReturn;
	}
	protected function getParams($line){
		$primary_keys=$line->getPrimaryKeys();
		$toReturn="";
		foreach($primary_keys as $name=>$value){
			$toReturn.=($toReturn==""?"":"&")."id[".$name."]=".$value;
		}
		$paramsListing = $this->getListing()->getFiltersURLParams();
		return $toReturn.($paramsListing==""?"":"&").$paramsListing;
	}
}
