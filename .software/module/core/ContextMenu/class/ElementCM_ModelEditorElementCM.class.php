<?php
/**
* The class for a Context Menu Element that opens a ModelEditor
*/
class ModelEditorElementCM extends ElementCM {
    /**
    * Returns an instance of ModelEditorElementCM as described in the XML given as parameter
    * Defines the Model, ModelEditor and Mode
    * @return ModelEditorElementCM  an instance of ModelEditorElementCM as described in the XML given as parameter
    * @param ContextMenuDescriptor $parentDescriptor The ContextMenuDescriptor that contains this ModelEditorElementCM 
    * @param SimpleXMLElement $xml XML containing the description of the ModelEditorElementCM 
    */
    public static function readFromXML($parentDescriptor,$xml){
        $contextMenuElement=parent::readFromXML($parentDescriptor, $xml);
        $contextMenuElement->setModel($xml->model."");
        $contextMenuElement->setModelEditor($xml->modelEditor."");
        $contextMenuElement->setMode($xml->mode."");
        return $contextMenuElement;
    }
    /**
    * Name of the Model
    */
    public $model;
    /**
    * Name of the ModelEditor to open
    */
    public $modelEditor;
    /**
    * Mode for the ModelEditor
    */
    public $mode;
    /**
    * Defines the Model of this ModelEditorElementCM 
    * @param string $model the Model of this ModelEditorElementCM 
    */
    public function setModel($model){
        $this->model = $model;
    }
    /**
    * Defines the ModelEditor of this ModelEditorElementCM 
    * @param string $modelEditor the ModelEditor of this ModelEditorElementCM
    */
    public function setModelEditor($modelEditor){
        $this->modelEditor = $modelEditor;
    }
    /**
    * Defines the Mode for the ModelEditor of this ModelEditorElementCM 
    * @param string $mode the Mode for the ModelEditor of this ModelEditorElementCM 
    */
    public function setMode($mode){
        $this->mode = $mode;
    }
}


