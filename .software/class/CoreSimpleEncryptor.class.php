<?php
/**
* An encryptor implementation, uses sha1 et md5
* Used to encrypt passwords before writing them to db for exemple
*/
class CoreSimpleEncryptor{
    /**
    * The value to encrypt
    */
    protected $value;
	/**
	* Defines the value to be encrypted
	* @param string $value The value to be encrypted
	*/
	public function __construct($value){
	    $this->value=$value;
	}
	/**
	* Returns the encrypted value
	* @return string the encrypted value
	*/
	public function getValue(){
	    return sha1(md5($this->value));
	}
}


?>
