jQuery('.icn-media-manager').live('click',function(event){
	event.preventDefault();
	jQuery(this).closest('.window_panel').data('object').url.params=jQuery(this).data('url-params');
	jQuery(this).closest('.window_panel').data('object').reload();
	return false;
});
