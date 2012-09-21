<?php

class ProductDetailModelListingDescriptor extends ModelListingDescriptor {

    public $currentElementParent;
    
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
	    	$this->currentElementParent=$currentElement->lstParent()->getModelDataElement();
	        $this->list->addModelData($this->currentElementParent);
	    }
	    
	    $childrenQuery = $currentElement->lstChildren();
	    $productQuery = $currentElement->lstProduct();
	    
	    foreach($this->filters as $filter){
	        $childrenQuery->addCondition($filter->getModelDataQueryCondition($childrenQuery->getModel()));
	        $productQuery->addCondition($filter->getModelDataQueryCondition($productQuery->getModel()));
	    }
	    
	    $children = $childrenQuery->getModelData();
	    $childrenSize = $childrenQuery->getFoundRows();
	    $product = $productQuery->getModelData();
	    $productSize = $productQuery->getFoundRows();
	    $this->list->merge($children)->merge($product);
	    $this->originalSize=$childrenSize+$productSize;
	    
	    $pageList = new ModelDataCollection();
	    
	    $counter = 0;
	    
	    foreach($this->list as $element){
	    	if ($counter>=($this->page*$this->pageSize)&&$counter<(($this->page+1)*$this->pageSize)){
	    	   $pageList->addModelData($element);
	    	}
	    	$counter++;
	    }
	    
	    $this->list = $pageList;
	    
	    
	    
	}
}


