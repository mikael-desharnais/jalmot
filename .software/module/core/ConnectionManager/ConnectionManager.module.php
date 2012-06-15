<?php

class ConnectionManager extends Module{
	
	
	public function displayConnectionForm(){
		Page::headerRedirection(($_SERVER['REQUEST_URI'][strlen($_SERVER['REQUEST_URI'])-1]=="/"?$_SERVER['REQUEST_URI']."connection/":dirname($_SERVER['REQUEST_URI'])."/connection/".filename($_SERVER['REQUEST_URI'])));
	}
	public function returnFromConnectionForm(){
	    Page::headerRedirection(File::createFromURL("..")->toURL());
		Ressource::getCurrentPage()->stopExecution();
	}
	public function getHtaccess(){
		return parent::getHtaccess()."\r\nRewriteRule	^connection/[(.*)/(.*)]|connection/()(.*)|connection/$						.software/connection.php					[L]";
	}
}
