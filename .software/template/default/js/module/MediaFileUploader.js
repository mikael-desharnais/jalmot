FileUploader = function(){
	
};

FileUploader.propagateFileUploaded=function(htmlElement,file,htmlResult){
	htmlElement.find('input.tempFilename').attr('value',htmlResult.tempFilename);
	htmlElement.find('input.filename').attr('value',htmlResult.filename);
};