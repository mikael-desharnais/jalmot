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
	    Resource::getCurrentPage()->stopExecution();
	    if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off');  }
	    
	    $fileWrapper = Module::getInstalledModule('MediaFileManager')->getFileById(Resource::getParameters()->getValue('id'));
	    
		$mime=$fileWrapper->getMimeType();
		
		$filename=$fileWrapper->getFilename();
		
		$fileURL = $fileWrapper->getFileURL();
		Resource::getCurrentPage()->addHeader('Pragma: public');
  		Resource::getCurrentPage()->addHeader('Expires: 0');    // no cache
  		Resource::getCurrentPage()->addHeader('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  		Resource::getCurrentPage()->addHeader('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($fileURL)).' GMT');
  		Resource::getCurrentPage()->addHeader('Cache-Control: private',false);
  		Resource::getCurrentPage()->addHeader('Content-Type: '.$mime);
  		Resource::getCurrentPage()->addHeader('Content-Disposition: attachment; filename="'.basename($filename).'"');
  		Resource::getCurrentPage()->addHeader('Content-Transfer-Encoding: binary');
  		Resource::getCurrentPage()->addHeader('Content-Length: '.filesize($fileURL));  // provide file size
  		Resource::getCurrentPage()->addHeader('Connection: close');
  		Resource::getCurrentPage()->sendHeaders();
		readfile($fileURL);
	}
}


