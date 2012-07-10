<?php

class TextContentDisplayer extends Module{
    
    protected $textContent;
    protected $textContentLang;
    
    public function __construct($file,$class,$name){
        parent::__construct($file,$class,$name);
        $this->usesCache=true;
    }
    public function init(){
        parent::init();
        $this->importClasses();
    }
    public function getCacheValues(){
        return array('id'=>$this->htmlProducer->getConfParam('id'));
    }
    public function toHTML($currentHook, $instance){
        
        $textContentModel=Model::getModel('TextContent');
        $textContentLangModel=Model::getModel('TextContentLang');
        $this->textContent=Ressource::getDataSource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$textContentModel)
        	->addConditionBySymbol('=',$textContentModel->getField('idTextContent'), $this->htmlProducer->getConfParam('id'))
        	->getModelDataElement();
        $this->textContentLang=$this->textContent
        		->lstLang()
				->addConditionBySymbol('=',$textContentLangModel->getField('idLang'), Ressource::getCurrentLanguage()->getId())
				->getModelDataElement();
        return parent::toHTML($currentHook, $instance);
    }
}
