<?php

class ContextMenu extends Module{
    
    private $elementDescriptors=array();
    
    public function init(){
        parent::init();
        $this->importClasses();
    }
    
    public function getElementDescriptor($key){
        if (!array_key_exists($key,$this->elementDescriptors)){
            $this->loadElementDescriptor($key);
        }
        return $this->elementDescriptors[$key];
    }
    public function loadElementDescriptor($key){
        $xml=XMLDocument::parseFromFile(Ressource::getCurrentTemplate()->getFile(new File("xml/module/ContextMenu/descriptor",$key.".xml",false)));
        $classname=$xml->class."";
        $this->setElementDescriptor(call_user_func(array($classname,"readFromXML"),$key,$xml));
    }
    public function setElementDescriptor($descriptor){
        $this->elementDescriptors[$descriptor->getName()]=$descriptor;
    }
}
