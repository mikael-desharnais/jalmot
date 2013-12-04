<?php
/**
* The class that wraps a MediaFile into an ImageMediaFile
*/
class ImageMediaFileWrapper extends  MediaFileWrapper{
   public function getURLForSize($height,$width,$title=null){
       return Ressource::getConfiguration()->getValue('AliasName').'media/'.Ressource::getCurrentTemplate()->getName().'/'.$this->data->getIdMediaFile().'-'.$height.'x'.$width.'-'.urlencode(str_replace('/',' ',str_replace('-','',str_replace('.','',is_null($title)?$this->data->getName():$title)))).'.'.File::getExtensionStatic($this->data->getFile());
   }
   public function getURL($title=null){
       return Ressource::getConfiguration()->getValue('AliasName').'media/'.Ressource::getCurrentTemplate()->getName().'/'.$this->data->getIdMediaFile().'-'.urlencode(str_replace('/',' ',str_replace('-','',str_replace('.','',is_null($title)?$this->data->getName():$title)))).'.'.File::getExtensionStatic($this->data->getFile());
   }
}