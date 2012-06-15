<?php

class SimpleLengthRuleFV extends RuleFV {
    
    public function isValid(){
        $value=Ressource::getParameters()->getValue($this->target);
        $maxLength=$this->getConfParam('maxLength');
        $minLength=$this->getConfParam('minLength');
        return ((empty($maxLength)||strlen($value)<=$maxLength)&&(empty($minLength)||strlen($value)>=$minLength));
    }
}
