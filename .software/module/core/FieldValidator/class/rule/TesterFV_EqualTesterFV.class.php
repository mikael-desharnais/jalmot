<?php
/**
* Implementation for a simple equal tester.
* In a switcher, this tester test only [switcher value]=[configuration parameter called value]
* It uses the configuration parameter : value
*/
class EqualTesterFV extends TesterFV {
	/**
	* Returns true if the given element is equal to the configuration parameter called value, false otherwise
	* @return boolean true if the given element is equal to the configuration parameter called value, false otherwise
	* @param mixed $element The value to test
	*/
	public function corresponds($element){
	    return $element==$this->getConfParam('value');
	}
    
}


