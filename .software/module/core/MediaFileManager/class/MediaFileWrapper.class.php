<?php

class MediaFileWrapper {
    
    protected $data;
	private static $mimeTypes;
    
    public function __construct($data){
        $this->data=$data;
    }
    public function getMimeType(){
        $file=$this->getFilename();
        return MediaFileWrapper::getMimeTypeStatic(File::getExtensionStatic($file));
    }
    public function getFileURL(){
        return "media/file_".str_pad($this->data->getIdMediaFile(),10,'0',STR_PAD_LEFT).".".File::getExtensionStatic($this->data->getFile());
    }
    public function getFilename(){
        return $this->data->getFile(); 
    }
    
    public static function getMimeTypeStatic($extension){
        if (empty(self::$mimeTypes)){
            self::loadMimeTypes();
        }
        return self::$mimeTypes[$extension];
    }
    public static function loadMimeTypes(){
        $xml=XMLDocument::parseFromFile(new File("xml","mime-type.xml",false));
        foreach($xml->children() as $filetype){
            self::$mimeTypes[$filetype->extension.""]=$filetype->content_type."";
        }
    }
    public function copyFromTempFile($tempFile){
    	@rename($tempFile,$this->getFileURL());
    }
    public function delete(){
        @unlink($this->getFileURL());
    }
}
