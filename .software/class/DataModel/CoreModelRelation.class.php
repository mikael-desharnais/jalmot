<?php 
class CoreModelRelation{

	private $name;
	private $source;
	private $destination;
	private $type;

    public function __construct($name,$source,$destination,$type){
		$this->name=$name;
		$this->source=$source;
		$this->destination=$destination;
		$this->type=$type;
	}
	public function getName(){
		return $this->name;
	}
	public function getDestination(){
		return $this->destination;
	}
	public function getSource(){
		return $this->source;
	}
	public function getType(){
	    return $this->type;
	}
}

