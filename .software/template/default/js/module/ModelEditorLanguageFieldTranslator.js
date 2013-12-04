jQuery(document).ready(function(){
	jQuery(document).on('click','.translateButton',function(){
		var defaultField = jQuery(this).closest('.LanguageFieldME-container').find('.LanguageFieldME-default');
		var text = defaultField.find(':input.LanguageFieldME').val();
		var sourceLanguage = defaultField.data('idlang');
		var targetField = jQuery(this).closest('.LanguageFieldME-container').find('.LanguageFieldME-visible');
		var targetLanguage = targetField.data('idlang');

		var fetcher=new AjaxHTMLFetcher();
		fetcher.setURL(new URL(Ressource.getConfiguration().getValue('AliasName')+'translate/'+sourceLanguage+'/'+targetLanguage+'/',{'text':text}));
		fetcher.setCallBack(function(htmlCont){
			targetField.find(':input.LanguageFieldME').val(htmlCont);
			targetField.find(':input.LanguageFieldME').change();
		});
		fetcher.fetch();
	});
	jQuery('body').bind('htmlAppending',function(event,htmlContent){
		jQuery(htmlContent).find('.LanguageFieldME-container:not(.LanguageFieldME-container-static)').each(function(){
			jQuery(this).find('.LanguageFieldME-wrapper').each(function(){
				var count = 0;
				jQuery(this).find('.LanguageFieldME:not(.LanguageFieldME-default)').each(function(){
					if (jQuery(this).val().length>0){
						count++;
					}
				})
				if (count==0){
					jQuery(this).append('<a href="#" class="translateButton"></a>');
				}
			});
		});
	});
});

