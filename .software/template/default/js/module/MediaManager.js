jQuery('.icn-media-manager').live('click',function(event){
	event.preventDefault();
	jQuery(this).closest('.reload-change-listener').data('ReloadManager').url.params=jQuery.parseJSON(jQuery(this).data('url-params'));
	jQuery(this).closest('.reload-change-listener').data('ReloadManager').reload();
	return false;
});
