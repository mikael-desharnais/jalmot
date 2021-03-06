<?php

class TextTranslator extends Module{
	protected $translation;
	public function init(){
		parent::init();
		$this->importClasses();
		$this->addToGlobalExecuteStack();
	}
	public function execute(){
		parent::execute();
		$translationManager = new GoogleTranslationManager();
		$translationBuilder=$translationManager->getTranslationBuilder();
		$translationBuilder->setText(Resource::getParameters()->getValue('text'));
		$translationBuilder->setSourceLanguage(Language::getLanguageById(Resource::getParameters()->getValue('sourceLanguage'))->getName());
		$translationBuilder->setTargetLanguage(Language::getLanguageById(Resource::getParameters()->getValue('targetLanguage'))->getName());
		$this->translation = $translationBuilder->getTranslation();
	}
}
