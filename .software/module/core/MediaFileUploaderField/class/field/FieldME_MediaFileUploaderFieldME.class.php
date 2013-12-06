<?php
/**
* A FieldMe that allows you to upload files : It uses a HTML5 drag and drop feature. It may not work with a standard input file ...
*/
class MediaFileUploaderFieldME extends FieldME {
	/**
	* Returns the usefull element from the fetched data of the Descriptor
	* @return mixed the usefull element from the fetched data of the Descriptor
	* @param mixed $dataFetched The data fetched for the descriptor
	*/
	public function getUsefullData($dataFetched){
	    return $dataFetched['simple'];
	}
	/**
	* Returns the value used by this field from the current DataModel
	* @return mixed  the value used by this field from the current element
	* @param mixed $element the current DataModel
	*/
	protected function getValue($element){
		$getter="get".ucfirst($this->key);
		return $element->$getter();
	}
	/**
	* Returns the elements to save
	* Copies the file from tmp/upload/[time]/[filename] to its name in the media directory
	* @param mixed $dataFetched the data fetched for this descriptor
	*/
	public function fetchElementsToSave($dataFetched){
	    if (Resource::getParameters()->valueExists($this->getName())){
	        $fileInfos=Resource::getParameters()->getValue($this->getName());
	        if (!empty($fileInfos['tempFilename'])){
			    $eventListener=new EventListener($this);
			    $functionEventListener=function ($target,$listener){
			        $fileWrapper = new MediaFileWrapper($target);
			        $fileInfos = Resource::getParameters()->getValue($listener->getName());
			        $fileWrapper->copyFromTempFile($fileInfos['tempFilename']);
			    };
			    
			    $fileWrapper = new MediaFileWrapper($dataFetched['simple']);
			    $fileWrapper->delete();
			    $eventListener->afterSavePerformed=$functionEventListener;
			    $dataFetched['simple']->addAfterSaveListener($eventListener);
		    	$function="set".ucfirst($this->getName());
			    $dataFetched['simple']->$function($fileInfos['filename']);
	        }
	    }
	}
	/**
	* Returns the html output for this field
	* @return string the html output for this field
	* @param mixed $dataFetched The data fetched for the descriptor
	*/
	public function toHTML($dataFetched){
	    $element=$this->getUsefullData($dataFetched);
		ob_start();
		include(Resource::getCurrentTemplate()->getFile(new File("html/module/MediaFileUploader",$this->class.".phtml",false))->toURL());
		return ob_get_clean();
	}
}


