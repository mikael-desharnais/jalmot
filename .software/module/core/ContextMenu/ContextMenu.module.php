<?php
/**
* Module that allows the use of a ContextMenu
* ContextMenus are usally described using XML.
* Any ContextMenuDescriptor or ContextMenuElement can be created and specified in XML files
* @see ContextMenuDescriptor
*/
class ContextMenu extends Module{
    /**
    * List of all descriptors
    */
    private $elementDescriptors=array();
    /**
    * Initialises the module and imports the classes
    */
    public function init(){
        parent::init();
        $this->importClasses();
    }
    /**
    * Returns the ContextMenuDescriptor corresponding to the given key
    * @return ContextMenuDescriptor the ContextMenuDescriptor corresponding to the given key
    * @param string $key the key that correspond to the required ContextMenuDescriptor
    */
    public function getElementDescriptor($key){
        if (!array_key_exists($key,$this->elementDescriptors)){
            $this->loadElementDescriptor($key);
        }
        return $this->elementDescriptors[$key];
    }
    /**
    * Load a ContextMenuDescriptor from XML File given its name
    * File : [template]/xml/module/ContextMenu/descriptor/[name].xml
    * @param string $key The name of the ContextMenuDescriptor
    */
    public function loadElementDescriptor($key){
        $xml=XMLDocument::parseFromFile(Resource::getCurrentTemplate()->getFile(new File("xml/module/ContextMenu/descriptor",$key.".xml",false)));
        $classname=$xml->class."";
        $this->addElementDescriptor(call_user_func(array($classname,"readFromXML"),$key,$xml));
    }
    /**
    * Adds a ContextMenuDescriptor
    * @param ContextMenuDescriptor $descriptor The ContextMenuDescriptor  to add
    */
    public function addElementDescriptor($descriptor){
        $this->elementDescriptors[$descriptor->getName()]=$descriptor;
    }
}


