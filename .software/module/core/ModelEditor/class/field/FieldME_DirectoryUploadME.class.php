<?php

class DirectoryUploadME extends FieldME {
	protected $files = array();
	protected $relation;
    public function fetchElementsToSave($dataFetched){
    	$contentContainer = $this->model_editor->getParameterContainer();
    	if (array_key_exists($this->key,$contentContainer)){
    		$idFiles = $contentContainer[$this->key];
    	}else {
    		$idFiles = array();
    	}
    	$relationName = "lst".ucfirst($this->relation);
	    $directory = $dataFetched['simple']->$relationName()->getModelDataElement(true);
    	if ( $dataFetched['simple']->getSource()==ModelData::$SOURCE_FROM_DATASOURCE&&(Resource::getParameters()->getValue("action")=='save')){
	    	if (empty($directory)){
	    		$directory = Model::getModel('MediaDirectory')->getInstance();
	    		$directory->setIdMediaDirectoryParent($this->getConfParam('idMediaDirectoryParent'));
	    		$directory->setName($dataFetched['simple']->getParentModel()->getName()." : ".serialize($dataFetched['simple']->getPrimaryKeys()));
	    		$directory->save();
	    		$setter = "set".$dataFetched['simple']->getParentModel()->getRelation($this->relation)->getSource()->getName();
	    		$dataFetched['simple']->$setter($directory->getIdMediaDirectory());
	    	}
	    	$files = $directory->lstFile()->getModelData();
	    	foreach($files as $file){
	    		if (!in_array($file->getIdMediaFile(),$idFiles)){
					$fileWrapper = new MediaFileWrapper($file);
					$fileWrapper->delete();
					$file->delete();
	    		}else {
	    			$idFiles = $array=array_diff($idFiles, array($file->getIdMediaFile()));
	    		}
	    	}
	    	if (count($idFiles)>0){
		    	$remainingFileQuery = QueryBuilder::getMediaFile();
		    	$conditionContainer = $remainingFileQuery->getConditionContainerInstance(ModelDataQueryConditionContainer::$MODEL_DATA_QUERY_CONDITION_CONTAINER_OR);
		    	$container = $remainingFileQuery->addConditionContainer($conditionContainer);
		    	
		    	$elementModel = $remainingFileQuery->getModel();
		    	foreach($idFiles as $idFile){
		    		$conditionContainer->addConditionBySymbol('=',$elementModel->getField('idMediaFile'), $idFile);
		    	}
		    	
		    	$remainingFiles = $remainingFileQuery->getModelData();
		    	foreach($remainingFiles as $remainingFile){
		    		$remainingFile->setIdMediaDirectory($directory->getIdMediaDirectory());
		    		$remainingFile->save();
		    	}
	    	}
	    	$directory = $dataFetched['simple']->lstMediaDirectory()->getModelDataElement(true);
    	}
    	if (!empty($directory)){
    		$this->files = $directory->lstFile()->getModelData(true);
    	}
    }
    public function getValue($element){
    	
    }
    public function getUsefullData($element){
    	
    }
    public function setRelation($relation){
    	$this->relation=$relation;
    }
	public static function readFromXML($model_editor,$xml){
		$toReturn = parent::readFromXML($model_editor, $xml);
		$toReturn->setRelation($xml->relation."");
		return $toReturn;
	}
}
