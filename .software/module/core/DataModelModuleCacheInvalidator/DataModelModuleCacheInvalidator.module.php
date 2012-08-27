<?php

class DataModelModuleCacheInvalidator extends Module{
    
    public $xmlLoaded = false;
    public $modules = array();
    
    public function init(){
        $listener=new EventListener($this);
        $listener->afterUpdatePerformed=function ($listenedTo,$listeningObject){
            return $listeningObject->invalidateCache($listenedTo);
        };
        $listener->afterCreatePerformed=$listener->afterUpdatePerformed;
        $listener->afterDeletePerformed=$listener->afterUpdatePerformed;
        ModelData::addAfterUpdateStaticListener($listener);
        ModelData::addAfterCreateStaticListener($listener);
        ModelData::addAfterDeleteStaticListener($listener);
    }
    public function invalidateCache($element){
        if (!$this->xmlLoaded){
      		$file=Template::getCurrentTemplate()->getFile(new File('xml/module/DataModelModuleCacheInvalidator','configure.xml',false));
        	$xml = XMLDocument::parseFromFile($file);
            foreach($xml as $dataModel){
                foreach($dataModel->modules->children() as $module){
                    $this->modules[$dataModel->name.""][]=$module."";
                }
            }
            $this->xmlLoaded=true;
        }
        if (array_key_exists($element->getParentModel()->getName(),$this->modules)){
	        foreach($this->modules[$element->getParentModel()->getName()]  as $module){
	        	$moduleFile = Module::findModule($module);
		        include_once($moduleFile->toURL());
		        $directory = call_user_func(array($module,"getCacheDirectory"),$module,new File('html/module/','',false));
		        if (file_exists($directory->toURL())){
		            Log::LogData("Invalidating Cache for module ".$module." because of DataModel ".$element->getParentModel()->getName(), Log::$LOG_LEVEL_INFO);
		        	$directory->delete();
		        }
	        }
        }
    }
}
