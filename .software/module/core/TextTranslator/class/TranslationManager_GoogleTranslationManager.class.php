<?php
class GoogleTranslationManager extends TranslationManager{
	
	protected $browser;
	
	public function __construct(){
		$this->browser = new Browser();
	}
	public function getTranslationBuilder(){
		$toReturn  = new GoogleTranslationBuilder();
		$toReturn->setTranslationManager($this);
		return $toReturn;
	}
	public function translate($text,$sourceLanguage,$targetLanguage){

		if (empty($text)){
			return;
		}
		
		$parameters = array();
		$parameters['client']='t';
		$parameters['hl']='fr';
		$parameters['sl']=$sourceLanguage;
		$parameters['tl']=$targetLanguage;
		$parameters['ie']='UTF-8';
		$parameters['oe']='UTF-8';
		$parameters['oc']='1';
		$parameters['otf']='2';
		$parameters['ssel']='0';
		$parameters['tsel']='0';
		$parameters['sc']='1';
		$parameters['q']=str_replace("\n","",str_replace("\r","",strip_tags($text)));

		$browserResult = $this->browser->openURL(Browser::$GET, "http://translate.google.fr/translate_a/t?".http_build_query($parameters));
		$response = $browserResult->getResponse();
		$result = Javascript::parseArray($response);
		return $result[0][0][0][0];
	}
}