<?php

abstract class TranslationManager{
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
	public abstract function getTranslationBuilder();
}