<?php

class MediaManagerFileUploader extends Module{
    
	protected $mediaFile;
	
	public function init(){
		parent::init();
		$listener=new EventListener($this);
		
		$listener->afterExecutePerformed=function ($listenedTo,$listeningObject){
			if ($listenedTo->getUploaded()){
				$listeningObject->execution($listenedTo);
			}
		};
		Module::getInstalledModule('MediaFileUploader')->addAfterExecuteListener($listener);
	}
	public function execute(){
		$listenedTo = func_get_arg(0);
		$model = Model::getModel("MediaFile");
		$id=Resource::getParameters()->getValue('id');
		if (empty($id)||!array_key_exists('idMediaDirectory',$id)){
			$idMediaDirectory=null;
		}else {
			$idMediaDirectory=$id['idMediaDirectory'];
		}
		$element=$model->getInstance();
		$element->setFile($listenedTo->getFilename());
		$element->setName($listenedTo->getFilename());
		$element->setIdMediaDirectory($idMediaDirectory);
		$element->save();
		$fileWrapper = new MediaFileWrapper($element);
		$fileWrapper->copyFromTempFile($listenedTo->getTempFilename());
		Resource::getCurrentPage()->setAjaxContent('idMediaFile',$element->getIdMediaFile());
		if (Image::isImage($fileWrapper->getFile())){
			$mediaImage=Module::getInstalledModule('ImageMediaFileManager')->getFileById($element->getIdMediaFile());
			Resource::getCurrentPage()->setAjaxContent('icon',$mediaImage->getURLForSize(100,100,$listenedTo->getFilename()));
		}else {
			Resource::getCurrentPage()->setAjaxContent('icon',Resource::getCurrentTemplate()->getFile(new File('media/image/module/mediaManager','icon-default.png',true),true)->toFullURL());
		}
	}
}
