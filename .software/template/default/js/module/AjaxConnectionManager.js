jQuery(document).ready(function(){
	jQuery('body').live('AjaxHTMLFetcherStatus',function(event,sourceHtmlFetcher){
		if (sourceHtmlFetcher.status==301){
			sourceHtmlFetcher.status=404;
			var fetcher=new AjaxHTMLFetcher();
			fetcher.setURL(new URL(Resource.getConfiguration().getValue('AliasName')+sourceHtmlFetcher.html,typeof sourceHtmlFetcher.params == "undefined"?new Object():sourceHtmlFetcher.params));
			fetcher.setCallBack(function(htmlCont){
				var window = new Window();
				window.setConfiguration(this.windowConfiguration);
				window.setContent(htmlCont);
				window.setURL(this.url);
				window.display();
			});
			fetcher.fetch();
		}
	});
	jQuery('#ajaxConnection').live('submit',function(){
		var params = new Object();
		var inputs = jQuery(this).serializeArray();
		var url = jQuery(this).closest('.reload-change-listener').data('ReloadManager').url;
		url.addSerializedArrayParams(inputs);
		jQuery(this).closest('.reload-change-listener').data('ReloadManager').reload();
		return false;
	})
}); 