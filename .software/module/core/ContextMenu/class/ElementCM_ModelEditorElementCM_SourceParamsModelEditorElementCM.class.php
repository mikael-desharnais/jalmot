<?php
class SourceParamsModelEditorElementCM extends ModelEditorElementCM {
	
	public $dataField;
	
	public function setDataField($dataField){
		$this->dataField = $dataField;
	}
	
	public static function readFromXML($parentDescriptor,$xml){
		$contextMenuElement=parent::readFromXML($parentDescriptor, $xml);
		if (!empty($xml->dataField)){
			$contextMenuElement->setDataField($xml->dataField."");
		}else {
			$contextMenuElement->setDataField("url-params");
		}
		return $contextMenuElement;
	}
}
