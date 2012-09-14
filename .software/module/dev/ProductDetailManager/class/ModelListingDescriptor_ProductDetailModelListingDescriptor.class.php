<?php

class ProductDetailModelListingDescriptor extends ModelListingDescriptor {

	/**
	* Fetches the data for the current directory taken from Parameter id[idMediaDirectory]
	* If the current directory is not root directory, a ParentDirectory Is added to the list of elements
	*/
	public function fetchData(){
	    $this->list=new ModelDataCollection;
	    $model=Model::getModel($this->model);
	    $id_category=1;
	    if (Ressource::getParameters()->valueExists('id')){
	        $id=Ressource::getParameters()->getValue('id');
	        if (array_key_exists('idProductDetailCategory',$id)){
	            $id_category=$id['idProductDetailCategory'];
	        }
	    }
	    $currentElement=$model->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$model)
		          		->addConditionBySymbol('=',$model->getField('idProductDetailCategory'), $id_category)
		           		->getModelDataElement();

	    if ($id_category!=1){
	        $parent_category=$model->getInstance();
	        $parent_category->setName('Parent Category');
	        $parent_category->editable=false;
	        $parent_category->setIdMediaDirectory($currentElement->lstParent()->getModelDataElement()->getIdMediaDirectory());
	        $this->list->addModelData($parent_category);
	    }
	    $this->list=$this->list->merge($currentElement->lstChildren()->getModelData())->merge($currentElement->lstProduct()->getModelData());
	}
}


