<?php

class CategoryLanguageCellML extends LanguageCellML{

	public function toHTML($element){
		$line=ModelLangRelation::getModelDataElement($element,$this->getKey());
		ob_start();
		include(Resource::getCurrentTemplate()->getURL("html/module/ModelListing/CategoryCellML.phtml"));
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
