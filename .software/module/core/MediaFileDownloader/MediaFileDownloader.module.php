<?php
/**
* Module that forces the download of a MediaFile : The MediaFile is the one with the Id given as parameter
*/
class MediaFileDownloader extends Module{
	/**
	* Imports classes
	* Adds to global execution Stack
	*/
	public function init(){
		parent::init();
		$this->importClasses();
        $this->addToGlobalExecuteStack();
	}
	/**
	* Stops the execution of the page and then forces the download
	*/
	public function execute(){
	    Ressource::getCurrentPage()->stopExecution();
	    if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off');  }
	    
	    $fileWrapper = Module::getInstalledModule('MediaFileManager')->getFileById(Ressource::getParameters()->getValue('id'));
	    
		$mime=$fileWrapper->getMimeType();
		
		$filename=$fileWrapper->getFilename();
		
		$fileURL = $fileWrapper->getFileURL();
		Ressource::getCurrentPage()->addHeader('Pragma: public');
  		Ressource::getCurrentPage()->addHeader('Expires: 0');    // no cache
  		Ressource::getCurrentPage()->addHeader('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  		Ressource::getCurrentPage()->addHeader('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($fileURL)).' GMT');
  		Ressource::getCurrentPage()->addHeader('Cache-Control: private',false);
  		Ressource::getCurrentPage()->addHeader('Content-Type: '.$mime);
  		Ressource::getCurrentPage()->addHeader('Content-Disposition: attachment; filename="'.basename($filename).'"');
  		Ressource::getCurrentPage()->addHeader('Content-Transfer-Encoding: binary');
  		Ressource::getCurrentPage()->addHeader('Content-Length: '.filesize($fileURL));  // provide file size
  		Ressource::getCurrentPage()->addHeader('Connection: close');
  		Ressource::getCurrentPage()->sendHeaders();
		readfile($fileURL);
	}
}


