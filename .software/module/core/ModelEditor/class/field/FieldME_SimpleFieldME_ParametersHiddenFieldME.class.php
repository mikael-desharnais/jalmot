<?php

class ParametersHiddenFieldME extends SimpleFieldME {
    public function toHTML($dataFetched){
        ob_start();
        $element=$this->getUsefullData($dataFetched);
        $value=$this->getValue($element);
        if (!empty($value)){
            $element = $value;
        }else {
            $element_array=Ressource::getParameters()->getValue("id");
            $element = $element_array[$this->getConfParam('paramKey')];
            if (empty($element)){
                $element = $this->getConfParam('defaultValue');
            }
        }
        include(Ressource::getCurrentTemplate()->getFile(new File("html/module/ModelEditor",$this->class.".phtml",false))->toURL());
        return ob_get_clean();
    }	
}
