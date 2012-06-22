<?php
/**
 *
 */
class ModelEditorElementCM extends ElementCM {
    public static function readFromXML($parentDescriptor,$xml){
        $contextMenuElement=parent::readFromXML($parentDescriptor, $xml);
        $contextMenuElement->setModel($xml->model."");
        $contextMenuElement->setModelEditor($xml->modelEditor."");
        $contextMenuElement->setMode($xml->mode."");
        return $contextMenuElement;
    }
    public $model;
    public $modelEditor;
    public $mode;
    public function setModel($model){
        $this->model = $model;
    }
    public function setModelEditor($modelEditor){
        $this->modelEditor = $modelEditor;
    }
    public function setMode($mode){
        $this->mode = $mode;
    }
}
