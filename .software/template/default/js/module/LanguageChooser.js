jQuery(document).ready(function(){
	jQuery('.LanguageFieldME-languageChooser')
			.live('click',function(event){
				event.preventDefault();
				jQuery(this).closest('.window_panel').find('.LanguageFieldME-visible').removeClass('LanguageFieldME-visible');
				jQuery(this).closest('.window_panel').find('.LanguageFieldME-lng'+jQuery(this).attr('href')).addClass('LanguageFieldME-visible');
				return false;
			});
	jQuery('.LanguageFieldME-default').click();
	jQuery('.LanguageFieldME-default').removeClass('LanguageFieldME-default');
});
