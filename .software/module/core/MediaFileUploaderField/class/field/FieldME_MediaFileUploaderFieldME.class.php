<?php

class MediaFileUploaderFieldME extends FieldME {
    
	public function getUsefullData($dataFetched){
	    return $dataFetched['simple'];
	}
	protected function getValue($element){
		$getter="get".ucfirst($this->key);
		return $element->$getter();
	}
	public function fetchElementsToSave($dataFetched){
	    if (Ressource::getParameters()->valueExists($this->getName())){
	        $fileInfos=Ressource::getParameters()->getValue($this->getName());
	        if (!empty($fileInfos['tempFilename'])){
			    $eventListener=new EventListener($this);
			    $functionEventListener=function ($target,$listener){
			        $fileWrapper = new MediaFileWrapper($target);
			        $fileInfos = Ressource::getParameters()->getValue($listener->getName());
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
	public function toHTML($dataFetched){
	    $element=$this->getUsefullData($dataFetched);
		ob_start();
		include(Ressource::getCurrentTemplate()->getFile(new File("html/module/MediaFileUploader",$this->class.".phtml",false))->toURL());
		return ob_get_clean();
	}
}
