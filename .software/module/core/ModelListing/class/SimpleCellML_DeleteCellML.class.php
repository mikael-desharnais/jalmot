<?php

class DeleteCellML extends SimpleCellML {
    
    
	public function toHTML($line){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/DeleteCellML.phtml"));
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
		$toReturn = array();
		foreach($line->getPrimaryKeys() as $key=>$value){
			$toReturn['id['.$key.']']=$value;
		}
		return array_merge($toReturn,$this->getListing()->getFiltersURLParams());
	}
}
