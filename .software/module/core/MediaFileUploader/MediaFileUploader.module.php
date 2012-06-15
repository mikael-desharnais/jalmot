<?php

class MediaFileUploader extends Module{
    
	protected $descriptors=array();
	
	public function init(){
		parent::init();
        $this->addToGlobalExecuteStack();
	}
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
	protected $uploaded;
	protected $filename;
	protected $tempFilename;
	
	public function setUploaded($uploaded){
	    $this->uploaded=$uploaded;
	}
	public function setFilename($filename){
	    $this->filename=$filename;
	}
	public function setTempFilename($tempFilename){
	    $this->tempFilename=$tempFilename;
	}
	public function getUploaded(){
	    return $this->uploaded;
	}
	public function getFilename(){
	     return $this->filename;
	}
	public function getTempFilename(){
	     return $this->tempFilename;
	}
}
