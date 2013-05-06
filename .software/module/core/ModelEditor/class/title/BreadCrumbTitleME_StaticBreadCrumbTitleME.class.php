<?php

class StaticBreadCrumbTitleME extends BreadCrumbTitleME {
	protected $createTitle;
	protected $updateTitle;
	public static function readFromXML($model_editor,$xml){
		$toReturn = parent::readFromXML($model_editor,$xml);
		$toReturn->setCreateTitle($xml->createTitle."");
		$toReturn->setUpdateTitle($xml->updateTitle."");
		return $toReturn;
	}
	public function loadData(){
		$fetchedData = $this->model_editor->getFetchedData();
		$title = Ressource::getCurrentLanguage()->getTranslation($fetchedData['simple']->getSource()==ModelData::$SOURCE_NEW?$this->createTitle:$this->updateTitle);
		$parentElement = new ElementBreadCrumbME(Ressource::getCurrentLanguage()->getTranslation($this->model_editor->getModel()." in breadcrumb"),$title,$fetchedData['simple']);
		$this->parentElements[] = $parentElement;
		$this->loadRelations($parentElement->getModelData());
	}
	public function setCreateTitle($createTitle){
		$this->createTitle = $createTitle;
	}
	public function setUpdateTitle($updateTitle){
		$this->updateTitle = $updateTitle;
	}
}
