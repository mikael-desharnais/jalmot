<?php

class DirectoryModelListingDescriptor extends ModelListingDescriptor {
    
	public function toHTML(){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/MediaManager/DirectoryModelListingDescriptor_".$this->type.".phtml"));
		return ob_get_clean();
	}
	public function fetchData(){
	    $this->list=array();
	    $model=Model::getModel($this->model);
	    $id_directory=1;
	    if (Ressource::getParameters()->valueExists('id')){
	        $id=Ressource::getParameters()->getValue('id');
	        if (array_key_exists('idMediaDirectory',$id)){
	            $id_directory=$id['idMediaDirectory'];
	        }
	    }
	    $currentElement=Ressource::getDataSource()->getModelDataRequest(ModelDataRequest::$SELECT_REQUEST,$model)
		          		->addConditionBySymbol('=',$model->getField('idMediaDirectory'), $id_directory)
		           		->getModelDataElement();

	    if ($id_directory!=1){
	        $parent_directory=$model->getInstance();
	        $parent_directory->setName('Parent Directory');
	        $parent_directory->editable=false;
	        $parent_directory->setIdMediaDirectory($currentElement->lstParent()->getModelDataElement()->getIdMediaDirectory());
	        $this->list[]=$parent_directory;
	    }
	    $this->list=array_merge($this->list,$currentElement->lstChildren()->getModelData(),$currentElement->lstFile()->getModelData());
	}
}
