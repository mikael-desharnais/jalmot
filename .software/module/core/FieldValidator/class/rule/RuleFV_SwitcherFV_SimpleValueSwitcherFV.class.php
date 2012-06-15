<?php

class SimpleValueSwitcherFV extends SwitcherFV {
    
    public function isValid(){
        $value=Ressource::getParameters()->getValue($this->target);
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
