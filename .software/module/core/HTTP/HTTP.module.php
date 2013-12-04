<?php
class HTTP extends Module {
	
	protected $address;
	protected $customer;
	protected $emailAddress;
	protected $phone;
	
	public function init(){
		parent::init();
		$this->importClasses();
	}
}