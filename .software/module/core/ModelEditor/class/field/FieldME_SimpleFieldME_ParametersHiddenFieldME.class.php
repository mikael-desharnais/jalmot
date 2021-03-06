<?php

class ParametersHiddenFieldME extends SimpleFieldME {
    public function toHTML($dataFetched){
        ob_start();
        $element=$this->getUsefullData($dataFetched);
        $value=$this->getValue($element);
        if (!empty($value)){
            $element = $value;
        }else {
            $element_array=Resource::getParameters()->getValue("id");
            if (array_key_exists($this->getConfParam('paramKey'),$element_array)){
	            $element = $element_array[$this->getConfParam('paramKey')];
	            if (empty($element)){
	                $element = $this->getConfParam('defaultValue');
	            }
            }else {
            	$element = $this->getConfParam('defaultValue');
            }
        }
        include(Resource::getCurrentTemplate()->getFile(new File("html/module/ModelEditor",$this->class.".phtml",false))->toURL());
        return ob_get_clean();
    }	
}
