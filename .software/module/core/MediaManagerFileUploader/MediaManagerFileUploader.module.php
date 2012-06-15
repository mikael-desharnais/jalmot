<?php

class MediaManagerFileUploader extends Module{
    
	public function init(){
		parent::init();
		
		if (Ressource::getParameters()->valueExists('action')&&Ressource::getParameters()->getValue('action')=="create_file"){
			$listener=new EventListener($this);
			$listener->afterExecutePerformed=function ($listenedTo,$listeningObject){
			    $model = Model::getModel("MediaFile");
			    $id=Ressource::getParameters()->getValue('id');
			    $idMediaDirectory=$id['idMediaDirectory'];
			    if (empty($id)||!array_key_exists('idMediaDirectory',$id)){
			        $idMediaDirectory=1;
			    }
			    $element=$model->getInstance();
			    $element->data_source=Ressource::getDataSource();
			    $element->setFile($listenedTo->getFilename());
			    $element->setName($listenedTo->getFilename());
			    $element->setIdMediaDirectory($idMediaDirectory);
			    $element->save();
			    $fileWrapper = new MediaFileWrapper($element);
			    $fileWrapper->copyFromTempFile($listenedTo->getTempFilename());
			};
			Module::getInstalledModule('MediaFileUploader')->addAfterExecuteListener($listener);
		}
	}
}
