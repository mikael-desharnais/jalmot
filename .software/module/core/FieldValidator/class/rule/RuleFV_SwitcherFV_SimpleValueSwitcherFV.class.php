<?php
/**
* Switcher according to a simple Value
* 
*/
class SimpleValueSwitcherFV extends SwitcherFV {
    /**
    * Returns true if the cases corresponding to the value are valid, false otherwise
    * 
    * @return boolean  true if the cases corresponding to the value are valid, false otherwise
    */
    public function isValid(){
        $value=Resource::getParameters()->getValue($this->target);
        $toReturn=true;
        foreach($this->cases as $case){
            if ($case->correponds($value)){
                $result=$case->isValid();
                $toReturn=$result&&$toReturn;
            }
        }
        return $toReturn;
    }
	
}


