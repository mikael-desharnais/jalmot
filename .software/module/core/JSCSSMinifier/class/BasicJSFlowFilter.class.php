<?php
/**
 *
 */
class BasicJSFlowFilter extends JSFilterFlow {
    public function compress($JSArray){
        $file_key = "";
        foreach($JSArray as $file_array){
            foreach($file_array as $file){
            	$file_key.=$file->toURL().'-'.filemtime($file->toURL()).'-';
            }
        }
        $fileToUse = new File(".cache/template/".Ressource::getCurrentTemplate()->getName()."/js","js-".md5($file_key).".js",false);
        @mkdir($fileToUse->getDirectory());
        if (Ressource::getConfiguration()->getValue('cacheJS')==0||!$fileToUse->exists()){
            $filecontent = "";
            foreach($JSArray as $file_array){
                foreach($file_array as $file){
           		$filecontent.=file_get_contents($file->toURL());
                }
            }
        	$filecontent_min = JSMin::minify($filecontent);
            $fileToUse->write($filecontent_min);
        }
        return array(array($fileToUse));
    }
} 
