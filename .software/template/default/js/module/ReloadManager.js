ReloadManager=function(){
	this.propagateChangeEvent=function(source){
		if (jQuery(source).hasClass('.reload-change-propagator')){
			var parent=jQuery(source);
		}else {
			var parent=jQuery(source).closest('.reload-change-propagator');
		}
		var toReload = parent.data('reload-change-type');
		jQuery('.reload-on-'+toReload+'-change').each(function(){
			if (jQuery(this).hasClass('reload-change-listener')){
				jQuery(this).data('ReloadManager').reload();
			}else {
				jQuery(this).closest('.reload-change-listener').data('ReloadManager').reload();
			}
			
		});
		
	};
}
var ReloadManager = new ReloadManager();