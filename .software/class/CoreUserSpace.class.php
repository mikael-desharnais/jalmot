<?php
/**
* A user space manages all the logged users
*/
class CoreUserSpace{
	
	public $slots=array();
	
	private $rightManager;
    
    private function __construct(){
        
    }
    public static function readFromXML($class,$xml){
    	$userspace = new $class();
    	foreach($xml->slots->children() as $slotXML){
    		$slotClass = $slotXML->class."";
    		$slot = call_user_func(array($slotClass,"readFromXML"),$slotClass,$slotXML);
    		$userspace->addSlot($slot);
    	}
    	return $userspace;
    }
    public static function getCurrentUserSpace(){
	    if (!Resource::getSessionManager()->valueExists("UserSpace")){
	    	$xml = XMLDocument::parseFromFile(Resource::getCurrentTemplate()->getFile(new File('xml','userspace.xml',false)));
	    	$class = $xml->class."";
	    	$userspace = call_user_func(array($class,"readFromXML"),$class,$xml);
	        Resource::getSessionManager()->setValue("UserSpace",$userspace);
	    }
        return Resource::getSessionManager()->getValue("UserSpace");
    }
    public function getSlot($slotName){
    	return $this->slots[$slotName];
    }
    public function addSlot($slot){
    	$this->slots[$slot->getName()]=$slot;
    }
}


?>