<?php

class TextContentPageTitleFromParams extends Module{
    
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
        return array('id'=>$this->htmlProducer->getConfParam('id'),'instance'=>$this->htmlProducer->getInstance(),"lang"=>Resource::getCurrentLanguage()->getId());
    }
    public function toHTML($currentHook, $instance){
        
        $textContentModel=Model::getModel('TextContent');
        $textContentLangModel=Model::getModel('TextContentLang');
        $this->textContent=$textContentModel->getDatasource()->getModelDataQuery(ModelDataQuery::$SELECT_QUERY,$textContentModel)
        	->addConditionBySymbol('=',$textContentModel->getField('idTextContent'), Resource::getParameters()->getValue('id'))
        	->getModelDataElement(true);
        if (!empty($this->textContent)){
	        $this->textContentLang=$this->textContent
	        		->lstLang()
					->addConditionBySymbol('=',$textContentLangModel->getField('idLang'), Resource::getCurrentLanguage()->getId())
					->getModelDataElement(true);
        }
        return parent::toHTML($currentHook, $instance);
    }
}
