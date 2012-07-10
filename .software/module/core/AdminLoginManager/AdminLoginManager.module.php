<?php
/**
* Module that manages the Secure Connection to the Back Office
*/
class AdminLoginManager extends Module{
	/**
	* Imports classes
	* Executes in the Global Execution Stack
	* On page start, checks if a user logged in the UserSpace has the right ACCESS_ADMIN, if not, Finds ConnectionManager and asks to display the connectionForm
	*/
	public function init(){
		parent::init();
		$this->importClasses();
        $this->addToGlobalExecuteStack();
		$startPageListener=new EventListener($this);
		$startPageListener->actionPerformed=function ($sourcePage){
			if (!Ressource::getUserSpace()->hasRight('ACCESS_ADMIN')&&Ressource::getCurrentPage()->getName()!='connection'){
				Module::getInstalledModule('ConnectionManager')->displayConnectionForm();
			}
		};
		Ressource::getCurrentPage()->addStartPageEventListener($startPageListener);
	}
	/**
	* If Parameters contain userInput and passwordInput, creates a query to find the corresponding user
	* if a user is found, it is added to UserSpace and ConnectionManager is called to return from Connection Form
	*/
	public function execute(){
	    parent::execute();
	    if (Ressource::getParameters()->valueExists("userInput")&&Ressource::getParameters()->valueExists("passwordInput")){
	        $userModel=Model::getModel('UserAdmin');
	        $users=Ressource::getDataSource()->getModelDataRequest(ModelDataRequest::$SELECT_REQUEST,$userModel)
	        										->addConditionBySymbol('=',$userModel->getField('username'), Ressource::getParameters()->getValue("userInput"))
	        										->addConditionBySymbol('=',$userModel->getField('password'), $userModel->getField('password')->getEncryptor(Ressource::getParameters()->getValue("passwordInput"))->getValue())
	        										->getModelData();

	        if (count($users)>0){
	            $userAdmin=new AdminUser($users[0]);
	            Ressource::getUserSpace()->addUser($userAdmin);
				Module::getInstalledModule('ConnectionManager')->returnFromConnectionForm();
	        }
	    }
	}
	
}


