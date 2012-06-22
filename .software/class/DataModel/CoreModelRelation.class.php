<?php
/**
* This class describes the relation between two models
* A relation is a one direction link between two models
* 
*/
class CoreModelRelation{
	/**
	* The name of the relation.
	* To use when using magin method lst[RELATIONNAME]
	*/
	private $name;
	/**
	* The start point of the relation
	*/
	private $source;
	/**
	* The end point of the relation
	*/
	private $destination;
	/**
	* The type of the relation
	* Example :
	* if the type is deleteOnCascade 
	* deleting an element in the source of this relation will delete all the element linked to the element in the destination of this relation
	*/
	private $type;
    /**
    * Initialises the name, source, destination and type of this relation
    * @param string $name The name of the relation
    * @param ModelField $source The field that is the start point of the relation
    * @param ModelField $destination The field that is the end point of the relation
    * @param string $type the type of the relation
    */
    public function __construct($name,$source,$destination,$type){
		$this->name=$name;
		$this->source=$source;
		$this->destination=$destination;
		$this->type=$type;
	}
	/**
	* Returns the name of the relation
	* @return string the name of the relation
	*/
	public function getName(){
		return $this->name;
	}
	/**
	* Returns the end point of the relation
	* @return ModelField the end point of the relation
	*/
	public function getDestination(){
		return $this->destination;
	}
	/**
	* Returns the start point of the relation
	* @return ModelField  the start point of the relation
	*/
	public function getSource(){
		return $this->source;
	}
	/**
	* Returns the type of this relation
	* @return string the type of this relation
	*/
	public function getType(){
	    return $this->type;
	}
}



