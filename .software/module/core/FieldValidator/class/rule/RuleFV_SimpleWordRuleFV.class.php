<?php
/**
* Rule that checks if a Field contains only letters
*/
class SimpleWordRuleFV extends RuleFV {
    /**
    * Returns true if the Parameter contains only letters, false otherwise
    * @return boolean  true if the Parameter contains only letters, false otherwise
    */
    public function isValid(){
        $value=Resource::getParameters()->getValue($this->target);
        return preg_match('/^[a-zA-Z ]*$/',$value);
    }
}


