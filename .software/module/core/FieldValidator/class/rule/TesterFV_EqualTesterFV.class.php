<?php

class EqualTesterFV extends TesterFV {

	public function corresponds($element){
	    return $element==$this->getConfParam('value');
	}
    
}
