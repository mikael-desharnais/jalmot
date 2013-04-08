<?php

class CoreUserSpaceSlot{
	
	public $user=null;
	protected $name;
	
    private function __construct(){
        
    }
    public function getName(){
    	return $this->name;
    }
    public function setName($name){
    	$this->name = $name;
    }
    
    public function addUser($user){
        $this->user=$user;
    }
    
    public function removeUser($user){
        unset($this->user);
    }
    
	public function hasRight($right){
		if (!empty($this->user)){
			return $this->user->checkRight($right);
		}else{
			return false;
		}
	}
    public static function readFromXML($class,$xml){
    	$userSpaceSlot = new $class();
    	$userSpaceSlot->setName($xml->name."");
    	return $userSpaceSlot;
    }
    public function getUsers(){
    	return array($this->user);
    }
}


?>