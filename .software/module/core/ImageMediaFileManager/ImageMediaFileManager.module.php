<?php
/**
* Module that manages the link between HDD files and DataModel files
*/
class ImageMediaFileManager extends Module{
	/**
	* Imports the classes
	*/
	public function init(){
		parent::init();
		$this->importClasses();
	}

	/**
	* Returns an ImageMediaFileWrapper Instance correspoding to the given id
	* @return ImageMediaFileWrapper  an ImageMediaFileWrapper Instance correspoding to the given id
	* @param int $id The id of the file to use
	*/
	public function getFileById($id){
	    $mediaFileModel=Model::getModel('MediaFile');
	    $file=$mediaFileModel->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$mediaFileModel)
				    ->addConditionBySymbol('=',$mediaFileModel->getField('idMediaFile'),(int)$id)
				    ->getModelData();
	    if ($file->valid()){
	        return new ImageMediaFileWrapper($file->current());
	    }else {
	        throw new Exception("Unkown File");
	    }
	}
}


