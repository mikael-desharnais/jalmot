jQuery('.model-editor-cancel-button').live('click',function(){
	jQuery(this).closest('.reload-change-listener').data('object').close();
});
jQuery('.model-editor-delete-button').live('click',function(){

	if (confirm(Ressource.getLanguage().getTranslation('Are you sure you want to delete this element ?'))){
		var parent=this;
		var reloadChangeListener = jQuery(this).closest('.reload-change-listener').data('object');
		var url=new URL();
		url.address=reloadChangeListener.url.address;
		url.params=jQuery.extend({},reloadChangeListener.url.params);
		url.address += 'delete/'
		var fetcher=new AjaxHTMLFetcher();
		fetcher.setURL(url);
		fetcher.setCallBack(function(data){
			ReloadManager.propagateChangeEvent(jQuery(parent));
			if (this.status==1){
				jQuery(parent).closest('.reload-change-listener').data('object').close();
			}
		});
		fetcher.fetch();
		fetcher.integrate(); 
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
	var reloadChangeListener = jQuery(this).closest('.reload-change-listener').data('object');
	var url=new URL();
	url.address=reloadChangeListener.url.address;
	url.params=jQuery.extend({},reloadChangeListener.url.params);
	url.address += 'save/'
	var inputs = jQuery(this).closest('.window_frame').find(':input').serializeArray();
	url.addSerializedArrayParams(inputs);
	var fetcher=new AjaxHTMLFetcher();
	fetcher.setURL(url);
	fetcher.setCallBack(function(data){
		ReloadManager.propagateChangeEvent(jQuery(parent));
		if (this.status==1){
			jQuery(parent).closest('.reload-change-listener').data('object').close();
		}
	});
	fetcher.fetch();
	fetcher.integrate(); 
	
	return false;
	
});
