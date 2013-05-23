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
    	if (Ressource::getParameters()->valueExists("idMediaFile")){
    		$idMediaFile = Ressource::getParameters()->getValue("idMediaFile");
    		$imageMediaFile = Module::getInstalledModule('ImageMediaFileManager')->getFileById($idMediaFile);
    		$image = Image::getImage($imageMediaFile->getFile());
    		if (Ressource::getParameters()->valueExists("width")&&Ressource::getParameters()->valueExists("height")){
    			$thumb=$image->getThumb(Ressource::getParameters()->getValue("width"),Ressource::getParameters()->getValue("height"));
    			$fileName=$idMediaFile.'-'.Ressource::getParameters()->valueExists("width").'x'.Ressource::getParameters()->getValue("height").'.'.$file->getExtension();
    		}else {
    			$thumb=$image->getImageContent();
    			$fileName=$idMediaFile.'.'.$file->getExtension();
    		}
    		$fileToWrite = new File('.cache/template/'.$directory->getFile().'/media',$fileName,false);
    		$image->writeRawImageToFile($thumb,$fileToWrite);
    		Ressource::getCurrentPage()->stopExecution();
    		Ressource::getCurrentPage()->addHeader('Content-type: '.$imageMediaFile->getMimeType());
    		Ressource::getCurrentPage()->sendHeaders();
    		readfile($fileToWrite->toURL());
    	}
       
    }
    
}


