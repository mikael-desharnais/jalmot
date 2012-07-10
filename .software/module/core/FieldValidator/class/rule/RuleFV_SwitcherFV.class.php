<?php
/**
* The equivalent of a Switch case for rules
* 
*/
abstract class SwitcherFV extends RuleFV {
	/**
	* The cases for this switch. A case is a RuleFV
	*/
	public $cases=array();
	/**
	* Adds a case to this switcher.A case is a RuleFV
	* @param RuleFV $case The case to add to this switcher
	*/
	public function addCase($case){
		$this->cases[]=$case;
	}
    /**
    * Returns an instance of SwitcherFV as described in the SimpleXMLElement given as parameter
    * @return SwitcherFV an instance of SwitcherFV as described in the SimpleXMLElement given as parameter
    * @param SimpleXMLElement $xml The SimpleXMLElement that describes the SwitcherFV to return
    */
    public static function readFromXML($xml){
        $toReturn=parent::readFromXML($xml);
        foreach($xml->cases->children() as $caseXML){
            $toReturn->addCase(call_user_func(array($caseXML->class."","readFromXML"),$caseXML));
        }
        return $toReturn;
    }
    

}


