<?php
class HTTPException extends Exception{

	protected $browserResult;
	
	public function __construct($browserResult,$message=NULL,$code=NULL,$previous=NULL){
		$this->browserResult=$browserResult;
		parent::__construct($message,$code,$previous);
	}
	public function getBrowserResult(){
		return $this->browserResult;
	}
	
}