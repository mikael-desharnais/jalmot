<?php
/**
* A user space manages all the logged users
*/
class CoreUserSpace{
	/**
	* List of all users logged
	*/
	private $users=array();
	/**
	* The right Manager
	*/
	private $rightManager;
    /**
    * Builds a UserSpace
    */
    private function UserSpace(){
        
    }
    /**
    * Adds a logged user to the userspace
    * @param mixed $user Any User object
    */
    public function addUser($user){
        $this->users[$user->getId()]=$user;
    }
    /**
    * Removes a logged user from the userspace
    * @param mixed $user Any User object
    */
    public function removeUser($user){
        unset($this->users[$user->getId()]);
    }
    /**
    * Returns the UserSpace to use taking it either from Session or create a new instance
    * @return UserSpace  the UserSpace to use taking it either from Session or create a new instance
    */
    public static function getCurrentUserSpace(){
	    if (!Ressource::getSessionManager()->valueExists("UserSpace")){
	        Ressource::getSessionManager()->setValue("UserSpace",new UserSpace());
	    }
        return Ressource::getSessionManager()->getValue("UserSpace");
    }
	/**
	* Returns true if one of the users logged has the right given in parameters, false otherwise
	* @return boolean true if one of the users logged has the right given in parameters, false otherwise
	* @param String $right the string that describes the right
	*/
	public function hasRight($right){
		$toReturn=false;
		foreach($this->users as $user){
			$toReturn=$toReturn||$user->checkRight($right);
		}
		return $toReturn;
	}
	
	public function getUsers(){
		return $this->users;
	}
}


?>