<?php

class CoreSimpleEncryptor{
    
    protected $value;
    
	public function __construct($value){
	    $this->value=$value;
	}
	public function getValue(){
	    return sha1(md5($this->value));
	}
}
?>
