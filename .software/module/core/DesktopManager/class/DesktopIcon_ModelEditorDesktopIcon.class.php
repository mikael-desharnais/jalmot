<?php

class ModelEditorDesktopIcon extends DesktopIcon {
    
    public function toHTML(){
        ob_start();
        $file=Ressource::getCurrentTemplate()->getURL("html/module/DesktopManager/icons/ModelEditorDesktopIcon_".$this->instance.".phtml",true);
        if (empty($file)){
            $file=Ressource::getCurrentTemplate()->getURL("html/module/DesktopManager/icons/ModelEditorDesktopIcon.phtml");
        }
        include($file);
        return ob_get_clean();
    }	
	public static function readFromXML($icon){
	    $desktopIcon=new ModelEditorDesktopIcon($icon->instance,$icon->image."",$icon->text."");
	    $desktopIcon->setConfParams(XMLParamsReader::read($icon));
	    return $desktopIcon;
	}
}
