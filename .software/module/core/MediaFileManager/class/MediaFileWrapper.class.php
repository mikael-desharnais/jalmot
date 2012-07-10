<?php
/**
* The class that wraps the data of a MediaFile, used to find the file on disk
*/
class MediaFileWrapper {
    /**
    * The DataModel wrapped
    */
    protected $data;
	/**
	* The list of mime types
	*/
	private static $mimeTypes;
    /**
    * Initialises the DataModel
    * @param DataModel $data The DataModel Wrapped
    */
    public function __construct($data){
        $this->data=$data;
    }
    /**
    * Returns the Mime type of this file
    */
    public function getMimeType(){
        $file=$this->getFilename();
        return MediaFileWrapper::getMimeTypeStatic(File::getExtensionStatic($file));
    }
    /**
    * Returns the URL of the file corresponding the DataModel Wrapped
    * @return string the URL of the file corresponding the DataModel Wrapped
    */
    public function getFileURL(){
        return "media/file_".str_pad($this->data->getIdMediaFile(),10,'0',STR_PAD_LEFT).".".File::getExtensionStatic($this->data->getFile());
    }
    /**
    * Returns the Name of the file
    * @return string the Name of the file
    */
    public function getFilename(){
        return $this->data->getFile(); 
    }
    /**
    * Returns the mimetype corresponding to the given extension
    * @return string the mimetype corresponding to the given extension
    * @param string $extension the extension
    */
    public static function getMimeTypeStatic($extension){
        if (empty(self::$mimeTypes)){
            self::loadMimeTypes();
        }
        return self::$mimeTypes[$extension];
    }
    /**
    * Loads the mime types of files from xml/mime-type.xml
    */
    public static function loadMimeTypes(){
        $xml=XMLDocument::parseFromFile(new File("xml","mime-type.xml",false));
        foreach($xml->children() as $filetype){
            self::$mimeTypes[$filetype->extension.""]=$filetype->content_type."";
        }
    }
    /**
    * Moves an uploaded file to the correct URL for the wrapped DataModel
    * @param string $tempFile URL of the uploaded file
    */
    public function copyFromTempFile($tempFile){
    	@rename($tempFile,$this->getFileURL());
    }
    /**
    * Deletes the file from HDD
    */
    public function delete(){
        @unlink($this->getFileURL());
    }
}


