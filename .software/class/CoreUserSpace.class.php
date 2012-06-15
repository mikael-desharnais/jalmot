<?php
class CoreUserSpace{
	private $users=array();
	private $rightManager;
    public function UserSpace(){
        
    }
    public function addUser($user){
        $this->users[$user->getId()]=$user;
    }
    public function removeUser($user){
        unset($this->users[$user->getId()]);
    }
    public static function getCurrentUserSpace(){
	    if (!Ressource::getSessionManager()->valueExists("UserSpace")){
	        Ressource::getSessionManager()->setValue("UserSpace",new UserSpace());
	    }
        return Ressource::getSessionManager()->getValue("UserSpace");
    }
	public function hasRight($right){
		$toReturn=false;
		foreach($this->users as $user){
			$toReturn=$toReturn||$user->checkRight($right);
		}
		return $toReturn;
	}
}
?>