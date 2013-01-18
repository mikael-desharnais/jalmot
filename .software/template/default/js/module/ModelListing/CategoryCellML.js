jQuery('.categoryListingCellMLOpen').live('click',function(event){
	event.preventDefault();
	jQuery(this).closest('.reload-change-listener').data('ReloadManager').url.params=jQuery(this).data('url-params');
	jQuery(this).closest('.reload-change-listener').data('ReloadManager').reload();
	return false;
});
