jQuery('.model-editor-cancel-button').live('click',function(){
	jQuery(this).closest('.reload-change-listener').data('object').close();
});
jQuery('.model-editor-delete-button').live('click',function(){
	var parent=this;
	var url=jQuery(this).closest('.reload-change-listener').data('object').url;

	if (confirm('Etes vous sûr de vouloir supprimer cet élement ?')){
		jQuery.post(url.address+'delete/',url.params,function(){
			ReloadManager.propagateChangeEvent(jQuery(parent));
			jQuery(parent).closest('.reload-change-listener').data('object').close();
		});
	}
});

jQuery('.model-editor-form').live('submit',function(event){
	event.preventDefault();
	var before_submit_result=new Object();
	before_submit_result.result=true;
	jQuery(this).trigger('before_submit',before_submit_result);
	if (!before_submit_result.result){
		return false;
	}
	var parent=this;
	var url=jQuery(this).closest('.reload-change-listener').data('object').url;
	jQuery.post(url.address+'save/',url.params+'&'+jQuery(this).closest('.window_frame').find(':input').serialize(),function(){
		ReloadManager.propagateChangeEvent(jQuery(parent));
		jQuery(parent).closest('.reload-change-listener').data('object').close();
	});
	
	return false;
	
});
