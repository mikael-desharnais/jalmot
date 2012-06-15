<?php
/**
 * Base object for a web page
 *
 * @author Mikael Desharnais
 * @version 1.0
 * @package CoreClass
 */
 class CorePage{

	private $name;
	private $title;
	private $cssListe= array();
	private $jsListe= array();
	private $CSSFilterFlow;
	private $JSFilterFlow;
	private $startPageEventListener=array();
	private $goOnExecuting=true;
	private $headers=array();
	
	public function __construct($name){
		$this->name = $name;
		$this->JSFilterFlow=new JSFilterFlow();
		$this->CSSFilterFlow=new CSSFilterFlow();
	}
	public function getName(){
		return $this->name;
	}
	public function getPointerName(){
		return $this->name;
	}
	public function setTitle($title){
		$this->title = $title;
	}
	public function getTitle(){
		return $this->title;
	}
	public function addCSS($file,$order){
		if (!empty($file)){
			$this->cssListe[$order][]=$file;
		}
	}
	public function addJS($file,$order){
		if (!empty($file)){
			$this->jsListe[$order][]=$file;
		}
	}
	public function toHTML($templateToLoad="html/standardPage.phtml"){
		$this->propagateStartPageEvent();
		$this->sendHeaders();
		if ($this->goOnExecuting){
			include(Ressource::getCurrentTemplate()->getURL($templateToLoad));
		}	
	}
	public function sendHeaders(){
	    $this->loadHeaders();
	    foreach($this->headers as $header){
	        header($header);
	    }
	}
	public function loadHeaders(){
	    $xml=XMLDocument::parseFromFile($this->getConfigurationFile());
	    foreach($xml->headers->children() as $header){
	    	$this->addHeader($header."");
	    }
	}
	public function addHeader($header){
	    $this->headers[]=$header;
	}
	public function getConfigurationFile(){
		return Ressource::getCurrentTemplate()->getFile(new File("xml/page/".$this->name,"configuration.xml",false));
	}
	public function getHookDescriptionFile($name,$silent=false){
		return Ressource::getCurrentTemplate()->getFile(new File("xml/page/".$this->name."/hook",$name.".xml",$silent));
	}
	public function propagateStartPageEvent(){
		for ($x=0;$x<count($this->startPageEventListener)&&$this->goOnExecuting;$x++){
			$functionToExecute=$this->startPageEventListener[$x]->actionPerformed;
			$functionToExecute($this);
		}
	}
	public function addStartPageEventListener($listener){
		$this->startPageEventListener[]=$listener;
	}
	public function stopExecution(){
		$this->goOnExecuting=false;
	}
	public static function headerRedirection($pageName){
		header('Location: '.$pageName);
	}
	public function headerReload(){
		header("Refresh: 0;");
	}
	public static function getCurrentPage(){
	    $currentPage=Ressource::getParameters()->getValue('page');
	    if (empty($currentPage)){
	        $currentPage='index';
	    }
	    if (Ressource::getParameters()->getValue('pageMode')=='ajax'){
	    	return new AjaxPage($currentPage);
	    }else {
	        return new page($currentPage);
	    }
	}
	public function getXMLModuleFileConfiguration(){
		return Ressource::getCurrentTemplate()->getFile(new File('xml/page/'.$this->name,'modules.xml',false));
	}
	
}
?>
