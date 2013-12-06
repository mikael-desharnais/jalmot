<?php
/**
* Module that manages the link between HDD files and DataModel files
*/
class ImageMediaFileCacheCreator extends Module{
    /**
     * Imports the classes
     */
    public function init(){
        parent::init();
        $this->addToGlobalExecuteStack();
    }
    public function execute(){
        $file = File::createFromURL($_SERVER['REQUEST_URI']);
        $directory = File::createFromURL($file->getDirectory());
    	if (Resource::getParameters()->valueExists("idMediaFile")){
    		$idMediaFile = Resource::getParameters()->getValue("idMediaFile");
    		$imageMediaFile = Module::getInstalledModule('ImageMediaFileManager')->getFileById($idMediaFile);
    		$image = Image::getImage($imageMediaFile->getFile());
    		if (Resource::getParameters()->valueExists("width")&&Resource::getParameters()->valueExists("height")){
    			$thumb=$image->getThumb(Resource::getParameters()->getValue("width"),Resource::getParameters()->getValue("height"));
    			$fileName=$idMediaFile.'-'.Resource::getParameters()->getValue("width").'x'.Resource::getParameters()->getValue("height").'.'.$file->getExtension();
    		}else {
    			$thumb=$image->getImageContent();
    			$fileName=$idMediaFile.'.'.$file->getExtension();
    		}
    		$fileToWrite = new File('.cache/template/'.$directory->getFile().'/media',$fileName,false);
    		$image->writeRawImageToFile($thumb,$fileToWrite);
    		Resource::getCurrentPage()->stopExecution();
    		Resource::getCurrentPage()->addHeader('Content-type: '.$imageMediaFile->getMimeType());
    		Resource::getCurrentPage()->sendHeaders();
    		if ($fileToWrite->exists()){
    			readfile($fileToWrite->toURL());
    			Resource::getCurrentPage()->stopExecution();
    		}
    	}
       
    }
    
}