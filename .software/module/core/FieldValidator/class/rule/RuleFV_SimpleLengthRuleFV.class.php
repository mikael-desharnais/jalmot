<?php
/**
* Rule that checks if a Field's length is between two values
* maxLength : Taken from Configuration Parameter : maxLength
* minLength : Taken from Configuration Parameter : minLength
* if one of this values is not defined, there is no maxLength or MinLength
*/
class SimpleLengthRuleFV extends RuleFV {
    /**
    * Returns true if the Parameters length is between minLength and maxLength, false otherwise
    * @return boolean true if the Parameters length is between minLength and maxLength, false otherwise
    */
    public function isValid(){
        $value=Ressource::getParameters()->getValue($this->target);
        $maxLength=$this->getConfParam('maxLength');
        $minLength=$this->getConfParam('minLength');
        return ((empty($maxLength)||strlen($value)<=$maxLength)&&(empty($minLength)||strlen($value)>=$minLength));
    }
}


