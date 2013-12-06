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
			if (!Resource::getUserSpace()->getSlot('ADMIN')->hasRight('ACCESS_ADMIN')&&Resource::getCurrentPage()->getName()!='connection'){
				Module::getInstalledModule('ConnectionManager')->displayConnectionForm();
			}
		};
		Resource::getCurrentPage()->addStartPageEventListener($startPageListener);
	}
	/**
	* If Parameters contain userInput and passwordInput, creates a query to find the corresponding user
	* if a user is found, it is added to UserSpace and ConnectionManager is called to return from Connection Form
	*/
	public function execute(){
	    parent::execute();
	    if (Resource::getParameters()->valueExists("userInput")&&Resource::getParameters()->valueExists("passwordInput")){
	        $userModel=Model::getModel('UserAdmin');
	        $users=$userModel->getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$userModel)
	        										->addConditionBySymbol('=',$userModel->getField('username'), Resource::getParameters()->getValue("userInput"))
	        										->addConditionBySymbol('=',$userModel->getField('password'), $userModel->getField('password')->getEncryptedValue(Resource::getParameters()->getValue("passwordInput"))->getValue())
	        										->getModelData();

	        if ($users->valid()){
	            $userAdmin=new AdminUser($users->current());
	            Resource::getUserSpace()->getSlot('ADMIN')->addUser($userAdmin);
				Module::getInstalledModule('ConnectionManager')->returnFromConnectionForm();
	        }
	    }
	}
	
}


