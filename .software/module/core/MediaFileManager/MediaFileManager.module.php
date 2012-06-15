<?php

class MediaFileManager extends Module{
    	
	public function init(){
		parent::init();
		$this->importClasses();
	}
	public function getFileById($id){
	    $mediaFileModel=Model::getModel('MediaFile');
	    $file=Ressource::getDataSource()->getModelDataRequest(ModelDataRequest::$SELECT_REQUEST,$mediaFileModel)
				    ->addConditionBySymbol('=',$mediaFileModel->getField('idMediaFile'),(int)$id)
				    ->getModelData();
	    if (count($file)>0){
	        return new MediaFileWrapper($file[0]);
	    }else {
	        throw new Exception("Unkown File");
	    }
	}
}
