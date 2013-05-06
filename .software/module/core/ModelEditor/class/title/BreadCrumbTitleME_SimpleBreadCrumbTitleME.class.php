<?php

class SimpleBreadCrumbTitleME extends BreadCrumbTitleME {
	protected $title;
	public function loadData(){
		$fetchedData = $this->model_editor->getFetchedData();
		$this->loadRelations($fetchedData['simple']);
	}
	
}
