jQuery('.change-page a:not(disabled)').live('click',function(event){
	event.preventDefault();
	var reloader = jQuery(this).closest('.reload-change-listener').data('object');
	reloader.url.params['page_number']=jQuery(this).data('page');
	reloader.reload();
	return false;
});
