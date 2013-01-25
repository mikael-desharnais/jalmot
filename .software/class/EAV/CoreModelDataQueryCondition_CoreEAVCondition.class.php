<?php

class CoreEAVCondition extends CoreModelDataQueryCondition{
	private $args;
	public function __construct($args){
		$this->args=$args;
	}
	public function getSQL(){
		return md5(serialize($this->args));
	}
	public function getArguments(){
		return $this->args;
	}
}







