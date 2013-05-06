<?php

class NMBreadCrumbTitleME extends SimpleBreadCrumbTitleME {
	protected $title;
	public function loadRelations($formerElement){
		foreach($this->relations as $relation){
			$parentElement = $relation->getElement($formerElement);
			array_unshift($this->parentElements,$parentElement);
		}
	}
	
}
