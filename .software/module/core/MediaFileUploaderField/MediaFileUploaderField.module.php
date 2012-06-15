<?php

class MediaFileUploaderField extends Module{
    
	protected $descriptors=array();
	
	public function init(){
		parent::init();
		$this->importClasses();
	}
}
