jQuery(document).ready(function(){
	jQuery(document).on('click','.LanguageFieldME-languageChooser',function(event){
				event.preventDefault();
				jQuery(this).closest('.window_frame').find('.LanguageFieldME-visible').removeClass('LanguageFieldME-visible');
				jQuery(this).closest('.window_frame').find('.LanguageFieldME-lng'+jQuery(this).attr('href')).addClass('LanguageFieldME-visible');
				jQuery(document).trigger('refreshTabViewPort');
				return false;
			});
	jQuery('.LanguageFieldME-default').click();
});
