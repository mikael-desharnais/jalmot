<?php
/**
* An implementation of the Core Class CSSFilterFlow that simply merges the files and adapts the url contents. The cache key is generated using the last modification dates of all files
*/
class BasicCSSFlowFilter extends CSSFilterFlow {
    /**
    * Compresses the files
    * Simply merges the files and adapts the url contents
    * The cache key is generated using the last modification dates of all files
    * @see CoreCSSFilterFlow::compress()
    * @return array An array of array containing the file with the compressed content
    * @param array $CSSArray Array of files to compress
    */
    public function compress($CSSArray){
        $file_key = "";
        foreach($CSSArray as $file_array){
            foreach($file_array as $file){
            	$file_key.=$file->toURL().'-'.filemtime($file->toURL()).'-';
            }
        }
        $fileToUse = new File(".cache/template/".Ressource::getCurrentTemplate()->getName()."/css","css-".md5($file_key).".css",false);
        @mkdir($fileToUse->getDirectory(),0777,true);
        if (Ressource::getConfiguration()->getValue('cacheCSS')==0||!$fileToUse->exists()){
            $filecontent = "";
            foreach($CSSArray as $file_array){
                foreach($file_array as $file){
           		$filecontent.=$this->adaptContentURLs($file,file_get_contents($file->toURL()));
                }
            }
        	$filecontent_min = $filecontent;
            $fileToUse->write($filecontent_min);
        }
        return array(array($fileToUse));
    }
    public $currentFile;
    /**
    * Adapts the url contents in the css file to change the directory
    * @return string the content of the file modified
    * @param File $file The file that is being modified
    * @param String $content The content of the file being modified
    */
    public function adaptContentURLs($file,$content){
        $this->currentFile = $file;
        return preg_replace_callback('/url\(./',array($this,"replaceURL"),$content);
    }
    /**
    * Used by the adaptContentURLs to modify the url contents properly
    * @return string the matched part modified
    * @param string $match the matched part
    */
    private function replaceURL($match){
        if ($match[0][strlen($match[0])-1]=='"'||$match[0][strlen($match[0])-1]=="'"){
            return $match[0]."../../../../".$this->currentFile->getDirectory().'/';
        }else {
        	return "url(../../../../".$this->currentFile->getDirectory().'/'.$match[0][strlen($match[0])-1];
        } 
    }
}

 
