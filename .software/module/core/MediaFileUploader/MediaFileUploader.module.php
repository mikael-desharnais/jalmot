<?php
/**
* Module that manages the Upload of a MediaFile
*/
class MediaFileUploader extends Module{
	/**
	* Adds to global execution stack
	*/
	public function init(){
		parent::init();
        $this->addToGlobalExecuteStack();
	}
	/**
	* Moves the file to tmp/upload/[time]/[filename]
	*/
	public function execute(){
		parent::execute();
	    $time=time();
		if(count($_FILES)>0) {
		   @mkdir('tmp/upload/'.$time.'/');
		    if( move_uploaded_file( $_FILES['uploadedFile']['tmp_name'] , 'tmp/upload/'.$time.'/'.$_FILES['uploadedFile']['name'] ) ) {
				$this->setUploaded(true);
				$this->setFilename($_FILES['uploadedFile']['name']);
				$this->setTempFilename('tmp/upload/'.$time.'/'.$_FILES['uploadedFile']['name']);
		    }
		} else {
		    // If the browser does not support sendAsBinary ()
		    if(isset($_GET['base64'])) {
		        $content = base64_decode(file_get_contents('php://input'));
		    } else {
		        $content = file_get_contents('php://input');
		    }
		
		    $headers = getallheaders();
		    $headers = array_change_key_case($headers, CASE_UPPER);

		    if (array_key_exists("uploadedFile", $headers)){
			    if(file_put_contents('tmp/upload/'.$time.'/'.$headers['uploadedFile'], $content)) {
					$this->setUploaded(true);
					$this->setFilename($headers['UP-FILENAME']);
					$this->setTempFilename('tmp/upload/'.$time.'/'.$headers['uploadedFile']);
			    }
		    }
		}
	}
	/**
	* True if the current file was uploaded
	*/
	protected $uploaded;
	/**
	* The current file name
	*/
	protected $filename;
	/**
	* The current file temporary name
	*/
	protected $tempFilename;
	/**
	* Defines the current file uploaded status
	* @param boolean $uploaded  the current file uploaded status
	*/
	public function setUploaded($uploaded){
	    $this->uploaded=$uploaded;
	}
	/**
	* Defines the current file name
	* @param string $filename The current file name
	*/
	public function setFilename($filename){
	    $this->filename=$filename;
	}
	/**
	* Defines the current file temporary name
	* @param string $tempFilename the current file temporary name
	*/
	public function setTempFilename($tempFilename){
	    $this->tempFilename=$tempFilename;
	}
	/**
	* Returns the current file upload status
	* @return boolean the current file upload status
	*/
	public function getUploaded(){
	    return $this->uploaded;
	}
	/**
	* Returns the current file name
	* @return string the current file name
	*/
	public function getFilename(){
	     return $this->filename;
	}
	/**
	* Returns the current file temporary name
	* @return string  the current file temporary name
	*/
	public function getTempFilename(){
	     return $this->tempFilename;
	}
}


