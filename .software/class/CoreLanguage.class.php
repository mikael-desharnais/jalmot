<?php
/**
* Manages a language and the translations
* 
* 
*/
	class CoreLanguage{
		/**
		* language id
		* 
		*/
		private $id;
		/**
		* language name
		* 
		*/
		private $name;
		/**
		* list of all translations
		* 
		*/
		private $translations;
		/**
		* is the language loaded
		* 
		*/
		private $loaded=false;
		/**
		* Builds a language : to get a language, use Language::getLanguageById()
		* 
		* @param int $id Id of the Language
		* @param string $name name of the language
		*/
		protected function __construct($id,$name){
			$this->id=$id;
			$this->name=$name;
			$this->translations=array();
		}
		/**
		* Reads a .lng.xml language file and loads its content
		* 
		* @param string $file name of the file to load
		*/
		public function init($file){
		    Log::LogData("Load language/".$this->name.'/'.$file.".lng.xml",Log::$LOG_LEVEL_INFO);
			$xml_file=Ressource::getCurrentTemplate()->getFile(new File("language/".$this->name,$file.".lng.xml",false));
			if (!empty($xml_file)){
				$xml = XMLDocument::parseFromFile($xml_file);
				$translationList=$xml->translation;
				
				foreach($translationList as $translation){
					$this->translations[$translation->id.""]=$translation->value."";
				}
			}
		}
		/**
		* Returns the translation of the given text
		* if the configuration value : debugMode is larger than 1, missing translations will be displayer : [Missing Translation : [$id]]
		* @return string the translation of the given text
		* @param string $id reference for the translation
		*/
		public function getTranslation($id){
			if (array_key_exists($id,$this->translations)){
				return $this->translations[$id];
			}
			else {
				if (Ressource::getConfiguration()->getValue('debugMode')>=1){
					Log::Warning("[Missing Translation : ".$id."]");
					return "[Missing Translation : ".$id."]";
				}
				return $id;
			}
		}
		/**
		* Returns the Id of this language
		* @return integer  the Id of this language
		*/
		public function getId(){
			return $this->id;
		}
		/**
		* Returns the name of this language
		* @return string the name of this language
		*/
		public function getName(){
			return $this->name;
		}
		/**
		* List of loaded languages
		* 
		*/
		private static $languages=array();
		/**
		* Default language id
		*/
		private static $defaultLanguageId;
		/**
		* Returns a language given an ID
		* 
		* @return Language   a language corresponding to the ID given as parameter
		* @param integer $id Id of the required Language
		*/
		public static function getLanguageById($id){
		    if (!array_key_exists($id,self::$languages)){
		        self::$languages[$id]=new Language($id);
		    }
		    return self::$languages[$id];
		}
		/**
		* Adds a Language to used ones
		* @param Language $language the language to add
		*/
		public static function addLanguage($language){
			self::$languages[$language->getId()]=$language;
		}
		/**
		* Returns the current language
		* 
		* @return Language The current language
		*/
		public static function getCurrentLanguage(){
			self::loadAll();
		    return self::getLanguageById(self::$defaultLanguageId);
		}
		/**
		* Defines the default language by ID
		* @param integer $id the Id of the default language
		*/
		public static function setDefaultLanguageId($id){
			self::$defaultLanguageId=$id;
		}
		/**
		* loads all languages described in XML File : xml/language.xml
		*/
		public static function loadAll(){
			$xml=XMLDocument::parseFromFile(Ressource::getCurrentTemplate()->getFile(new File("xml","language.xml",false)));
			foreach($xml as $lng){
				if (isset($lng->default)){
					self::setDefaultLanguageId($lng->id."");
				}
				$language=new Language($lng->id."",$lng->code."");
				self::addLanguage($language);
			}
		}
		/**
		* Returns the list of available Languages
		* @return array the list of available Languages
		*/
		public static function getAvailableLanguages(){
			return self::$languages;
		}
	}


?>
