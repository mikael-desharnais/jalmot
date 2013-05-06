ReloadManager=function(){
	this.propagateChangeEvent=function(source){
		if (jQuery(source).hasClass('.reload-change-propagator')){
			var parent=jQuery(source);
		}else {
			var parent=jQuery(source).closest('.reload-change-propagator');
		}
		var toReloads = parent.data('reload-change-types').split(' ');
		for(var i in toReloads){
			this.propagateChangeEventByName(toReloads[i]);
		}
		
	};
	this.propagateChangeEventByName=function(toReload){
		jQuery.unique(jQuery('.reload-on-'+toReload+'-change').closest('.reload-change-listener')).each(function(){
			jQuery(this).data('ReloadManager').reload();
		});
	}; 
}
var ReloadManager = new ReloadManager();