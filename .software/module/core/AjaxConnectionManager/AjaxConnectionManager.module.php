<?php
/**
* The module that manages a standard connection page
*/
class AjaxConnectionManager extends Module{
	/**
	* Changes the current page to the connection page
	*/
	public function displayConnectionForm(){
		Resource::getCurrentPage()->stopExecution();
		print(json_encode(array('status'=>301,'html'=>'ajax/connection/','css'=>array(),'js'=>array())));
	}
	/**
	* Returns to the page that required the connection
	*/
	public function returnFromConnectionForm(){
		
	}

}


