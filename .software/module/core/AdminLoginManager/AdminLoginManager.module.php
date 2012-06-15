<?php

class AdminLoginManager extends Module{
    
	public function init(){
		parent::init();
		$this->importClasses();
        $this->addToGlobalExecuteStack();
		$startPageListener=new StartPageListener();
		$startPageListener->actionPerformed=function ($sourcePage){
			if (!Ressource::getUserSpace()->hasRight('ACCESS_ADMIN')&&Ressource::getCurrentPage()->getName()!='connection'){
				Module::getInstalledModule('ConnectionManager')->displayConnectionForm();
			}
		};
		Ressource::getCurrentPage()->addStartPageEventListener($startPageListener);
	}
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
