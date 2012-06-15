<?php

abstract class SwitcherFV extends RuleFV {
	
	public $cases=array();

	public function addCase($case){
		$this->cases[]=$case;
	}    


    public static function readFromXML($xml){
        $toReturn=parent::readFromXML($xml);
        foreach($xml->cases->children() as $caseXML){
            $toReturn->addCase(call_user_func(array($caseXML->class."","readFromXML"),$caseXML));
        }
        return $toReturn;
    }
    

}
