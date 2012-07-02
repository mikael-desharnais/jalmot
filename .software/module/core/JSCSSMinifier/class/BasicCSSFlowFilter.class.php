<?php
class BasicCSSFlowFilter extends CSSFilterFlow {
    /**
     * 
     * @see CoreCSSFilterFlow::compress()
     */
    public function compress($CSSArray){
        $file_key = "";
        foreach($CSSArray as $file_array){
            foreach($file_array as $file){
            	$file_key.=$file->toURL().'-'.filemtime($file->toURL()).'-';
            }
        }
        $fileToUse = new File(".cache/template/".Ressource::getCurrentTemplate()->getName()."/css","css-".md5($file_key).".css",false);
        @mkdir($fileToUse->getDirectory());
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
    
    public $file;
    
    public function adaptContentURLs($file,$content){
        $this->currentFile = $file;
        return preg_replace_callback('/url\(./',array($this,"replaceURL"),$content);
    }
    private function replaceURL($match){
        if ($match[0][strlen($match[0])-1]=='"'||$match[0][strlen($match[0])-1]=="'"){
            return $match[0]."../../../".$this->currentFile->getDirectory().'/';
        }else {
        	return "url(../../../".$this->currentFile->getDirectory().'/'.$match[0][strlen($match[0])-1];
        } 
    }
} 
