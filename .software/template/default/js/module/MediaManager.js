jQuery('.icn-media-manager').live('click',function(event){
	event.preventDefault();
	console.log(jQuery(this).closest('.reload-change-listener').data());
	jQuery(this).closest('.reload-change-listener').data('ReloadManager').url.params=jQuery(this).data('url-params');
	jQuery(this).closest('.reload-change-listener').data('ReloadManager').reload();
	return false;
});
