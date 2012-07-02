<?php
/**
* Desktop Icon that opens a Model Listing
* Contains two params :
* 
* 			<params>
* 				<param name="modelListing" type="simple">[ModelListing]</param>
* 				<param name="modelEditor" type="simple">[ModelEditor]</param>
* 			</params>
*/
class ModelListingDesktopIcon extends DesktopIcon {
    /**
    * Returns the HTML output for this icon and specific instance
    * File used for output : [template]/html/module/DesktopManager/icons/ModelListingDesktopIcon_[instance].phtml
    * if this file is not found : 
    * [template]/html/module/DesktopManager/icons/ModelListingDesktopIcon_standard.phtml
    * @return string the HTML output for this icon and specific instance
    */
    public function toHTML(){
        ob_start();
        $file=Ressource::getCurrentTemplate()->getURL("html/module/DesktopManager/icons/ModelListingDesktopIcon_".$this->instance.".phtml",true);
        if (empty($file)){
            $file=Ressource::getCurrentTemplate()->getURL("html/module/DesktopManager/icons/ModelListingDesktopIcon.phtml");
        }
        include($file);
        return ob_get_clean();
    }
	/**
	* Returns an instance of DesktopIcon as described in XML object
	* @return ModelListingDesktopIcon an instance of DesktopIcon as described in XML object
	* @param SimpleXMLElement $icon the xml describing the Icon
	*/
	public static function readFromXML($icon){
	    $desktopIcon=new ModelListingDesktopIcon($icon->instance,$icon->image."",$icon->text."");
	    $desktopIcon->setConfParams(XMLParamsReader::read($icon));
	    return $desktopIcon;
	}
	/**
	* TODO : see the use of this method
	*/
	public function getTemplateFile($instance){
	    return Ressource::getCurrentTemplate()->getURL("html/module/".$this->class."_".$instance.".phtml");
	}
}


