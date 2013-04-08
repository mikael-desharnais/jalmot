<?php
/**
* An implementation of ModelListingDescriptor adapted to listing tree categories
*/ 
class CategoryModelListingDescriptor extends ModelListingDescriptor {
	
	protected $linkingField;
	protected $contentRelation;
	protected $currentElement;
	
	/**
	 * Override the ModelListingDescriptor Method to read the linking field name and content relation name
	 */
	public static function readFromXML($name,$xml){
		$toReturn = parent::readFromXML($name,$xml);
		$toReturn->setLinkingField($xml->linkingField."");
		$toReturn->setContentRelation($xml->contentRelation."");
		return $toReturn;
	}
	/**
	* Returns the html output for this module
	* @return string  the html output for this module
	*/
	public function toHTML(){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/ModelListing/CategoryModelListingDescriptor_".$this->type.".phtml"));
		return ob_get_clean();
	}

	public function setLinkingField($linkingField){
		$this->linkingField=$linkingField;
	}
	public function setContentRelation($contentRelation){
		$this->contentRelation=$contentRelation;
	}
	/**
	* Fetches the data for the current directory taken from Parameter id[idMediaDirectory]
	* If the current directory is not root directory, a ParentDirectory Is added to the list of elements
	*/
	public function fetchData(){
	    $model=Model::getModel($this->model);
	    $this->list=new ModelDataCollection($model);
	    $id_directory=1;
	    if (Ressource::getParameters()->valueExists('id')){
	        $id=Ressource::getParameters()->getValue('id');
	        if (array_key_exists($this->linkingField,$id)){
	            $id_directory=$id[$this->linkingField];
	        }
	    }
	    $this->currentElement=$model->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$model)
		          		->addConditionBySymbol('=',$model->getField($this->linkingField), $id_directory)
		           		->getModelDataElement();
	    
	    if ($id_directory!=1){
	        $parent_directory=$model->getInstance();
	        $parent_directory->editable=false;
	        $setterName = "set".ucfirst($this->linkingField);
	        $getterName = "get".ucfirst($this->linkingField);
	        $parent_directory->$setterName($this->currentElement->lstParent()->getModelDataElement()->$getterName());
	        $this->list->addModelData($parent_directory);
	    }
	    $listName = "lst".ucfirst($this->contentRelation);
	    $this->list=$this->list->merge($this->currentElement->lstChildren()->getModelData())->merge($this->currentElement->$listName()->getModelData());
	}
	public function getCurrentElementParams(){
		$toReturn = array();
		foreach($this->currentElement->getPrimaryKeys() as $key=>$value){
			$toReturn['id['.$key.']']=$value;
		}
		return $toReturn;
	}
}


