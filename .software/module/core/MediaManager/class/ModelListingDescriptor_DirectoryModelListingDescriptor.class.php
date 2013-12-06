<?php
/**
* An implementation of ModelListingDescriptor adapted for MediaDirectoryListing
*/
class DirectoryModelListingDescriptor extends ModelListingDescriptor {
	/**
	* Returns the html output for this module
	* @return string  the html output for this module
	*/
	public function toHTML(){
		ob_start();
		include(Resource::getCurrentTemplate()->getURL("html/module/MediaManager/DirectoryModelListingDescriptor_".$this->type.".phtml"));
		return ob_get_clean();
	}
	/**
	* Fetches the data for the current directory taken from Parameter id[idMediaDirectory]
	* If the current directory is not root directory, a ParentDirectory Is added to the list of elements
	*/
	public function fetchData(){
	    $model=Model::getModel($this->model);
	    $this->list=new ModelDataCollection($model);
	    $id_directory=1;
	    if (Resource::getParameters()->valueExists('id')){
	        $id=Resource::getParameters()->getValue('id');
	        if (array_key_exists('idMediaDirectory',$id)){
	            $id_directory=$id['idMediaDirectory'];
	        }
	    }
	    $currentElement=$model->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$model)
		          		->addConditionBySymbol('=',$model->getField('idMediaDirectory'), $id_directory)
		           		->getModelDataElement();

	    if ($id_directory!=1){
	        $parent_directory=$model->getInstance();
	        $parent_directory->setName('Parent Directory');
	        $parent_directory->editable=false;
	        $parent_directory->setIdMediaDirectory($currentElement->lstParent()->getModelDataElement()->getIdMediaDirectory());
	        $this->list->addModelData($parent_directory);
	    }
	    $this->list=$this->list->merge($currentElement->lstChildren()->getModelData())->merge($currentElement->lstFile()->getModelData());
	}
}


