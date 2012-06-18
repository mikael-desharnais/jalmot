<?php
/**
 * Manages a language and the translations
 *
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */
	class CoreLanguage{
	    /**
	     * language id
	     * @access private
	     * @var int
	     */
		private $id;
		/**
		 * language name
		 * @access private
		 * @var string
		 */
		private $name;
		/**
		 * list of all translations
		 * @access private
		 * @var array
		 */
		private $translations;
		/**
		 * is the language loaded
		 * @access private
		 * @var boolean
		 */
		private $loaded=false;
		
		
		/**
		 * Builds a language : to get a language, use Language::getLanguageById()
		 * @param $id	 	id of the language
		 * @param $name	 	name of the language
		 */
		protected function __construct($id,$name){
			$this->id=$id;
			$this->name=$name;
			$this->translations=array();
		}
		
		/**
		 * Reads a .lng.xml language file and loads its content
		 * @param $file	 	filename (ex : mytranslation)
		 */
		public function init($file){
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
		 * @param $id		text to translate
		 * @return			the translation or the given text if there is no translation
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

		public function getId(){
			return $this->id;
		}

		public function getName(){
			return $this->name;
		}
		
		/**
		 * List of loaded languages
		 * @access private
		 * @var array
		 */
		private static $languages=array();
		private static $defaultLanguageId;
		
		/**
		 * Returns a language given an ID
		 * @param $id		language id
		 * @return			the language
		 */
		public static function getLanguageById($id){
		    if (!array_key_exists($id,self::$languages)){
		        self::$languages[$id]=new Language($id);
		    }
		    return self::$languages[$id];
		}

		public static function addLanguage($language){
			self::$languages[$language->getId()]=$language;
		}
		
		/**
		 * Returns the current language
		 * @return The current language
		 */
		public static function getCurrentLanguage(){
			self::loadAll();
		    return self::getLanguageById(self::$defaultLanguageId);
		}

		public static function setDefaultLanguageId($id){
			self::$defaultLanguageId=$id;
		}

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

		public static function getAvailableLanguages(){
			return self::$languages;
		}
	}
?>
