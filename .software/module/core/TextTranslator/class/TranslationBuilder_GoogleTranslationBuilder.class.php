<?php

class GoogleTranslationBuilder extends TranslationBuilder{
	protected $translationManager;
	protected $sourceLanguage;
	protected $targetLanguage;
	protected $text;
	
	public function __construct(){
		
	}
	public function setTranslationManager($translationManager){
		$this->translationManager=$translationManager;
	}
	public function setText($text){
		$this->text = $text;
	}
	public function setSourceLanguage($sourceLanguage){
		$this->sourceLanguage=$sourceLanguage;
	}
	public function setTargetLanguage($targetLanguage){
		$this->targetLanguage = $targetLanguage;
	}
	public function getTranslation(){
		return $this->translationManager->translate($this->text,$this->sourceLanguage,$this->targetLanguage);
	}
}