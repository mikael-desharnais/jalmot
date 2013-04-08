<?php
/**
* Base object for a web page
* 
* 
*/
 class CorePage{
	/**
	* The name of the page
	*/
	private $name;
	/**
	* The title of the page
	*/
	private $title;
	/**
	* The list of all CSS files to add to the page
	*/
	private $cssListe= array();
	/**
	* The list of all JS files to add to the page
	*/
	private $jsListe= array();
	/**
	* The CSS flow filterer
	*/
	private $CSSFilterFlow;
	/**
	* The JS flow filterer
	*/
	private $JSFilterFlow;
	/**
	* List of all listeners for Start Page Event
	*/
	private $startPageEventListener=array();
	/**
	* If true, the execution of module goes on
	*/
	private $goOnExecuting=true;
	/**
	* List of headers to send before HTML
	*/
	private $headers=array();

	/**
	 * Configuration parameters of this module
	 */
	protected $confParams;
	/**
	* Defines the name of the page
	* Sets JSFilterFlow and CSSFilterFlow with default filterers
	* @param string $name the name of the page
	*/
	public function __construct($name){
		$this->name = $name;
		$this->JSFilterFlow=new JSFilterFlow();
		$this->CSSFilterFlow=new CSSFilterFlow();
		$this->configure();
	}
	public function configure(){
		$xml=XMLDocument::parseFromFile($this->getConfigurationFile());
		$this->loadHeaders($xml);
		$this->setConfParams(XMLParamsReader::read($xml));
		if ((string)$xml->silent=="1"){
			Log::setGlobalLogLevel(Log::$LOG_LEVEL_SILENT);
		}
	}
	public function loadHeaders($xml){
	    foreach($xml->headers->children() as $header){
	    	$this->addHeader($header."");
	    }
	}
	/**
	* Returns the name of the page
	* @return string The name of the page
	*/
	public function getName(){
		return $this->name;
	}
	/**
	* Returns the unique name of this page
	* @return string  the unique name of this page
	*/
	public function getPointerName(){
		return $this->name;
	}
	/**
	* Defines the CSS filterer to use
	* @param CSSFilterFlow $cssFilterFlow the CSS filterer to use
	*/
	public function setCSSFilterFlow($cssFilterFlow){
	    $this->CSSFilterFlow=$cssFilterFlow;
	}
	/**
	* @param JSFilterFlow $jsFilterFlow the JS filterer to use
	*/
	public function setJSFilterFlow($jsFilterFlow){
	    $this->JSFilterFlow=$jsFilterFlow;
	}
	/**
	* Defines the title of the page
	* @param string $title the title of the page
	*/
	public function setTitle($title){
		$this->title = $title;
	}
	/**
	* Returns the title of the page
	* @return string the title of the page
	*/
	public function getTitle(){
		return $this->title;
	}
	/**
	* Adds a CSS file to page with an order
	* @param File $file The css file to add
	* @param integer $order the order to follow to include the file
	*/
	public function addCSS($file,$order){
		if (!empty($file)){
			$this->cssListe[$order][]=$file;
		}
	}
	/**
	* Adds a JS file to page with an order
	* @param File $file The js file to add
	* @param integer $order the order to follow to include the file
	*/
	public function addJS($file,$order){
		if (!empty($file)){
			$this->jsListe[$order][]=$file;
		}
	}
	/**
	* Generates the HTML code for this page
	*/
	public function toHTML($templateToLoad="html/standardPage.phtml"){
		$this->propagateStartPageEvent();
		$this->sendHeaders();
		if ($this->goOnExecuting){
			include(Ressource::getCurrentTemplate()->getURL($templateToLoad));
		}	
	}
	/**
	* Sends headers
	*/
	public function sendHeaders(){
	    foreach($this->headers as $header){
	        header($header);
	    }
	}
	/**
	* adds a header
	* @param string $header header to send
	*/
	public function addHeader($header){
	    $this->headers[]=$header;
	}
	/**
	* Returns the File used for Configuration
	* @return File the File used for Configuration
	*/
	public function getConfigurationFile(){
		return Ressource::getCurrentTemplate()->getFile(new File("xml/page/".$this->name,"configuration.xml",false));
	}
	/**
	* Returns the File used for Hook description
	* @return File the File used for Hook description
	* @param string $name the name of the hook
	* @param boolean $silent=false false if no error should be triggered on file not found
	*/
	public function getHookDescriptionFile($name,$silent=false){
		return Ressource::getCurrentTemplate()->getFile(new File("xml/page/".$this->name."/hook",$name.".xml",$silent));
	}
	/**
	* Triggers the start Page event
	*/
	public function propagateStartPageEvent(){
		for ($x=0;$x<count($this->startPageEventListener)&&$this->goOnExecuting;$x++){
			$functionToExecute=$this->startPageEventListener[$x]->actionPerformed;
			$functionToExecute($this);
		}
	}
	/**
	* Adds a listener for the Start Page Event
	* @param EventListener $listener the listener for the Start Page Event
	*/
	public function addStartPageEventListener($listener){
		$this->startPageEventListener[]=$listener;
	}
	/**
	* Stops the execution of the page (to HTML is not called)
	*/
	public function stopExecution(){
		$this->goOnExecuting=false;
	}
	/**
	* Sends header to redirect
	* @param string $pageName page name to redirect
	*/
	public static function headerRedirection($pageName){
		header('Location: '.$pageName);
	}
	/**
	* Sends header to refresh the page
	*/
	public function headerReload(){
		header("Refresh: 0;");
	}
	/**
	* Returns the current page
	* @return Page the current page
	*/
	public static function getCurrentPage(){
	    $currentPage=Ressource::getParameters()->getValue('page');
	    if (empty($currentPage)){
	        $currentPage='index';
	    }
	    if (Ressource::getParameters()->getValue('pageMode')=='ajax'){
	    	return new AjaxPage($currentPage);
	    }else {
	        return new Page($currentPage);
	    }
	}
	/**
	* Returns the XML File used to described installed modules for this page
	* @return Fome  the XML File used to described installed modules for this page
	*/
	public function getXMLModuleFileConfiguration(){
		return Ressource::getCurrentTemplate()->getFile(new File('xml/page/'.$this->name,'modules.xml',false));
	}
	/**
	* Defines the Configuration Parameters Array
	* @param array $params the Configuration Parameters Array
	*/
	public function setConfParams($params){
		$this->confParams=$params;
	}
	/**
	* Returns a configuration parameter value according to its key
	* @return string the configuration parameter value corresponding to the given key
	* @param string $key the key to the configuration parameter
	*/
	public function getConfParam($key){
	    return $this->confParams[$key];
	}
	
}


?>
