<?php

class SimpleWordRuleFV extends RuleFV {
    
    public function isValid(){
        $value=Ressource::getParameters()->getValue($this->target);
        return preg_match('/^[a-zA-Z ]*$/',$value);
    }
}
