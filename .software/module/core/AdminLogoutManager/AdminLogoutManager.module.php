<?php

class AdminLogoutManager extends Module{
	
	public function init(){
		parent::init();
		$this->importClasses();
        $this->addToGlobalExecuteStack();
	}
	/**
	* If Parameters contain userInput and passwordInput, creates a query to find the corresponding user
	* if a user is found, it is added to UserSpace and ConnectionManager is called to return from Connection Form
	*/
	public function execute(){
	    parent::execute();
	    $users = Ressource::getUserSpace()->getSlot('ADMIN')->getUsers();
	    foreach($users as $user){
	    	Ressource::getUserSpace()->getSlot('ADMIN')->removeUser($user);
	    }
	    Page::headerRedirection(File::createFromURL("..")->toURL());
	    Ressource::getCurrentPage()->stopExecution();
	}
	
}


