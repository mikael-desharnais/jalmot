<?php
class ImageURLME extends FieldME {
	public function fetchElementsToSave($dataFetched) {
	}
	public function getValue($element) {
	}
	public function getUsefullData($dataFetched){
	    return $dataFetched['simple'];
	}
	public function getURL($element){
		try {
			$fileWrapper = Module::getInstalledModule("MediaFileManager")->getFileById($element->getIdMediaFile());
			if (Image::isImage($fileWrapper->getFile())){
				$imageFileWrapper = Module::getInstalledModule("ImageMediaFileManager")->getFileById($element->getIdMediaFile());
				return $imageFileWrapper->getURLForSize('[width]','[height]',$element->getName());
			}else {
				return $fileWrapper->getDownloadURL();
			}
		}catch (Exception $ex){
			return "";
		}
	}
}
