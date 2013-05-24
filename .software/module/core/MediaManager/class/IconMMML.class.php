<?php
/**
* Basic icon for MediaModuleManagerListing
* Is an icon for both files and directories.
* I did not find a solution to use a different Icon Class for each file (is it a good idea ?)
*/
class IconMMML {
	/**
	* The key to the field name modified by this Icon (useful only to keep the compatibility with the Classical Listings)
	*/
	protected $key;
	/**
	* TODO : find usefulness
	*/
	protected $listing;
	/**
	* The array of configuration parameters
	*/
	protected $confParams=array();
	/**
	* True if the File behind the icon is editable
	*/
	protected $editable=true;
	
	protected $changeTypes=array();
    /**
    * Initialises the Key
    */
    public function __construct($key){
		$this->key=$key;
	}
	/**
	* Returns the HTML output for this Icon
	* @return string The HTML output for this Icon
	* @param ModelData $line The DataModel for this icon
	*/
	public function toHTML($line){
		ob_start();
		include(Ressource::getCurrentTemplate()->getURL("html/module/MediaManager/IconMMML.phtml"));
		return ob_get_clean();
	}
	/**
	* TODO : see the usefulness
	*/
	protected function getValue($line){
		$getter="get".ucfirst($this->key);
		return $line->$getter();
	}
	/**
	* Returns an instance of IconMMML based on the content of the xml given
	* @return IconMMML an instance of IconMMML based on the content of the xml given
	* @param SimpleXMLElement $xml The XML element describing the IconMMML to create
	*/
	public static function readFromXML($xml){
	    $cellDescriptor=new self((string)$xml->key);
	    $cellDescriptor->setConfParams(XMLParamsReader::read($xml));
	    if (isset($xml->changeTypes)){
	    	foreach($xml->changeTypes->children() as $changeTypeXML){
	    		$cellDescriptor->addChangeType($changeTypeXML."");
	    	}
	    }
		return $cellDescriptor;
	}
	public function addChangeType($changeType){
		$this->changeTypes[]=$changeType;
	}
	public function getChangeTypes(){
		return $this->changeTypes;
	}
	/**
	* TODO : override a Cell
	*/
	public function getListing(){
	    return $this->listing;
	}
	/**
	* TODO : override a Cell
	*/
	public function setListing($listing){
	    $this->listing=$listing;
	}

	public function getConfParams(){
	    return $this->confParams;
	}

	public function setConfParams($confParams){
	    $this->confParams=$confParams;
	}

	public function getConfParam($key){
	    return $this->confParams[$key];
	}

	protected function getId($line){
		$primary_keys=$line->getPrimaryKeys();
		$toReturn="";
		foreach($primary_keys as $name=>$value){
			$toReturn.=($toReturn==""?"":"-").$value;
		}
		return $toReturn;
	}

	protected function isDirectory($line){
	    if ($line->getParentModel()->getName()=="MediaDirectory"){
	        return true;
	    }else {
	        return false;
	    }
	}

	public function setEditable($editable) {
	    $this->editable=$editable;
	}

	public function isEditable($line){
	    if (isset($line->editable)){
	    	return $line->editable;
	    }else {
	        return true;
	    }
	}
	public function getParams($line){
		$toReturn = array();
		foreach($line->getPrimaryKeys() as $key=>$value){
			$toReturn['id['.$key.']']=$value;
		}
		return $toReturn;
	}
}




