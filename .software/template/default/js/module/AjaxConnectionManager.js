jQuery(document).ready(function(){
	jQuery('body').live('AjaxHTMLFetcherStatus',function(event,sourceHtmlFetcher){
		if (sourceHtmlFetcher.status==301){
			sourceHtmlFetcher.status=404;
			var fetcher=new AjaxHTMLFetcher();
			fetcher.setURL(new URL(Ressource.getConfiguration().getValue('AliasName')+sourceHtmlFetcher.html));
			fetcher.setCallBack(function(htmlCont){
				var window = new Window();
				window.setConfiguration(this.windowConfiguration);
				window.setContent(htmlCont);
				window.setURL(this.url);
				window.display();
			});
			fetcher.fetch();
			fetcher.integrate();
		}
	});
	jQuery('#ajaxConnection').live('submit',function(){
		jQuery(this).closest('.reload-change-listener').data('ReloadManager').url.params=jQuery(this).serialize();
		jQuery(this).closest('.reload-change-listener').data('ReloadManager').reload();
		return false;
	})
});