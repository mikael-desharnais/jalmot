<?php

class AdminUser {
    
    private $data;
	
	public function __construct($data){
		$this->data=$data;
	}
	public function getData(){
		return $this->data;
	}
	public function checkRight($rightName){
	    if ($rightName=='ACCESS_ADMIN'){
	        return true;
	    }
        return false;
	}
	public function getId(){
	    $this->data->getId();
	}
	
}
