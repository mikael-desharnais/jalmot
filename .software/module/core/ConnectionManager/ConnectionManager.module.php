<?php
/**
* The module that manages a standard connection page
*/
class ConnectionManager extends Module{
	/**
	* Changes the current page to the connection page
	*/
	public function displayConnectionForm(){
		Page::headerRedirection(($_SERVER['REQUEST_URI'][strlen($_SERVER['REQUEST_URI'])-1]=="/"?$_SERVER['REQUEST_URI']."connection/":dirname($_SERVER['REQUEST_URI'])."/connection/".filename($_SERVER['REQUEST_URI'])));
	}
	/**
	* Returns to the page that required the connection
	*/
	public function returnFromConnectionForm(){
	    Page::headerRedirection(File::createFromURL("..")->toURL());
		Resource::getCurrentPage()->stopExecution();
	}
	/**
	* Returns the HTACCESS for this module
	* @return string the HTACCESS for this module
	*/
	public function getHtaccess(){
		return parent::getHtaccess()."\r\nRewriteRule	^connection/[(.*)/(.*)]|connection/()(.*)|connection/$						.software/connection.php					[L]";
	}
}


