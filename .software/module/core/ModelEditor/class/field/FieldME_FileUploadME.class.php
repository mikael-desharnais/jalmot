<?php
class FileUploadME extends FieldME {
	protected $currentFile;
	protected $relation;
	public function fetchElementsToSave($dataFetched) {
		$contentContainer = $this->model_editor->getParameterContainer ();
		if (array_key_exists ( $this->key, $contentContainer )) {
			$idFile = $contentContainer [$this->key];
		}
		$relationName = "lst" . ucfirst ( $this->relation );
		$this->currentFile = $dataFetched ['simple']->$relationName ()->getModelDataElement ( true );
		if ($dataFetched ['simple']->getSource () == ModelData::$SOURCE_FROM_DATASOURCE && (Resource::getParameters ()->getValue ( "action" ) == 'save')) {
			if (isset($idFile)&&!empty($this->currentFile)&&$this->currentFile->getIdMediaFile()==$idFile){
				return;
			}
			if (!empty($this->currentFile)){
				$fileWrapper = new MediaFileWrapper ( $this->currentFile );
				$fileWrapper->delete ();
				$this->currentFile->delete ();
				$this->currentFile=null;
			}
			if (!empty ( $idFile ) ) {
				$newFile = QueryBuilder::getMediaFileByIdMediaFile($idFile)->getModelDataElement();
				$newFile->setIdMediaDirectory ( $this->getConfParam('idMediaDirectoryParent') );
				$newFile->save ();
				$this->currentFile=$newFile;
				$setter = "set".$dataFetched['simple']->getParentModel()->getRelation($this->relation)->getSource()->getName();
				$dataFetched ['simple']->$setter($this->currentFile->getIdMediaFile());
				$dataFetched ['simple']->save();
			}
		}
	}
	public function getValue($element) {
	}
	public function getUsefullData($element) {
	}
	public function setRelation($relation) {
		$this->relation = $relation;
	}
	public static function readFromXML($model_editor, $xml) {
		$toReturn = parent::readFromXML ( $model_editor, $xml );
		$toReturn->setRelation ( $xml->relation . "" );
		return $toReturn;
	}
}
