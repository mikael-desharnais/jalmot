<?php

class DeleteCellML extends SimpleCellML {
    
	protected $changeTypes = array();
	
	public function toHTML($line){
		ob_start();
		include(Resource::getCurrentTemplate()->getURL("html/module/ModelListing/DeleteCellML.phtml"));
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
	protected function getChangeTypes(){
		return $this->changeTypes;
	}
	public function addChangeType($changeType){
		$this->changeTypes[]=$changeType;
	}
	public static function readFromXML($xml){
	    $toReturn = parent::readFromXML($xml);
	    if (isset($xml->changeTypes)){
	    	foreach($xml->changeTypes->children() as $changeTypeXML){
	    		$toReturn->addChangeType($changeTypeXML."");
	    	}
	    }
		return $toReturn;
	}
}
