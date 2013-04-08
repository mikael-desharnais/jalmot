<?php

class EmbeddedModelEditorDescriptor extends ModelEditorDescriptor {
	
	protected $parentField;
	
	public function setParentField($field){
		$this->parentField = $field;
	}
	
	public function wrapFieldName($name){
		return 'ModelEditor['.$this->parentField->getKey().']['.$name.']';
	}
	public function getParameterContainer(){
		$container=$this->parentField->getModelEditor()->getParameterContainer();
		return array_key_exists($this->parentField->getKey(),$container)?$container[$this->parentField->getKey()]:array();
	}
	public function toHTML(){
		ob_start();
		include(Ressource::getCurrentTemplate()->getFile(new File("html/module/ModelEditor","EmbeddedModelEditorDescriptor_".$this->type.".phtml",false))->toURL());
		return ob_get_clean();
	}
}
