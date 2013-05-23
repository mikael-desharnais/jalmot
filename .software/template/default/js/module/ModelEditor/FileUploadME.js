
function MediaFileIcon(fileName){
	this.name;
	this.loadState = 0;
	this.icon;
	this.id;
	this.status;
	this.htmlElement = jQuery('<div class="fileUploadElement"><input type="hidden" class="idMediaFileContainer" name="'+fileName+'"><div class="content"></div></div>');
	this.progressBar = jQuery('<div class="progress"><div class="bar"></div>');
	this.deleteButton =  jQuery('<center><button class="btn btn-warning" type="button">'+Ressource.getLanguage().getTranslation('Delete')+'</button></center>');
	this.htmlElement.append(this.progressBar);
	this.htmlElement.append(this.deleteButton);
	var parent = this;
	this.deleteButton.click(function(){
		parent.htmlElement.remove();
		return false;
	});
	
	this.toHTML= function(){
		return this.htmlElement;
	}
	this.setName=function(name){
		this.name= name;
		this.progressBar.find('.bar').html(name);
	}
	this.setLoadState = function(loadState){
		this.loadState = loadState;
		this.progressBar.find('.bar').width(loadState+'%');
	}
	this.setIcon=function(icon){
		this.icon = icon;
    	this.htmlElement.find('.content').html('<img src="'+icon+'">');
	}
	this.setId=function(id){
		this.id = id;
		this.htmlElement.find('.idMediaFileContainer').val(id);
	}
	this.setStatus = function(status){
		this.status = status;
		this.progressBar.find('.bar').addClass('bar-'+status);
	}
};
MediaFileIcon.createFromHTML = function(htmlElement,fieldName){
	var icon = new MediaFileIcon(fieldName);
	icon.setName(htmlElement.data('text'));
	icon.setId(htmlElement.data('id'));
	icon.setIcon(htmlElement.data('icon'));
	icon.setLoadState(100);
	icon.setStatus('success');
	return icon;
}
jQuery(document).ready(function(){
	jQuery('body').bind('htmlAppending',function(event,htmlContent){
		jQuery(htmlContent).find('.fileUploadElementToCreate.fileUploadElement').each(function(){
			var icon = MediaFileIcon.createFromHTML(jQuery(this),jQuery(this).closest('.fileUploadContainer').find('.fileupload').data('inputfieldname'));
			jQuery(this).replaceWith(icon.toHTML());
			
		});
		jQuery(htmlContent).find('.fileupload').each(function(){
			var parent = jQuery(this);
			var configuration = {
			        url: 'MediaManager/upload/',
			        dataType: 'json',
			        singleFileUploads : 'true',
			        maxFileSize: 6000000,
			        previewMaxWidth: 100,
			        previewMaxHeight: 100,
			        progress: function (e, data) {
			            var progress = parseInt(data.loaded / data.total * 100, 10);
			            data.context.setLoadState(progress);
			        },
			        done : function(e, data){
			        	if (typeof data.result.idMediaFile =="undefined"){
			        		data.context.setStatus('danger');
			        		data.context.htmlElement.delay(2000).fadeOut(500,function(){
			        			jQuery(this).remove();
			        		})
			        	}else {
			            	data.context.setStatus('success');
			            	data.context.setId(data.result.idMediaFile);
			            	data.context.setIcon(data.result.icon);
			        	}
			        }
				};
			jQuery(this).fileupload(configuration).on('fileuploadadd',function(e,data){
															        	data.context = new MediaFileIcon(parent.data('inputfieldname'));
															        	data.context.setName(data.files[0].name);
															        	data.context.setLoadState(0);
															        	if (parent.attr('multiple')){
															        		jQuery(this).closest('.fileUploadContainer').find('.fileUploadList').prepend(data.context.toHTML());
															        	}else {
															        		jQuery(this).closest('.fileUploadContainer').find('.fileUploadList').html(data.context.toHTML());
															        	}
															        	data.submit();
															        });
	    });
	});
});