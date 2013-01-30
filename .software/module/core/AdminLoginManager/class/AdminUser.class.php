<?php
/**
* Class wrapper for user used as back office user
*/
class AdminUser {
    /**
    * The UserAdmin data Model containing the data concerning this user
    */
    private $data;
	/**
	* Initialises the data Model for the user
	* @param DataModel $data the data Model for the user
	*/
	public function __construct($data){
		$this->data=$data;
	}
	/**
	* returns the data Model for the user
	* @return DataModel The data Model for the user
	*/
	public function getData(){
		return $this->data;
	}
	/**
	* Checks if the user has the right given as parameter
	* @return boolean true if  the user has the right given as parameter
	* @param string $rightName The right to test
	*/
	public function checkRight($rightName){
	    if ($rightName=='ACCESS_ADMIN'){
	        return true;
	    }
	}
	/**
	* Returns the Id of the user
	*/
	public function getId(){
	    return $this->data->getIdUserAdmin();
	}
	
}


