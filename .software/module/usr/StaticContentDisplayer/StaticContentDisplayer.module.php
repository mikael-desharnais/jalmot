<?php
class StaticContentDisplayer extends Module{
    
    public function __construct($file,$class,$name){
        parent::__construct($file,$class,$name);
        $this->usesCache=true;
    }
    public function init(){
        parent::init();
        $this->importClasses();
    }
    public function getCacheValues(){
        return array('instance'=>$this->htmlProducer->getInstance());
    }

}
