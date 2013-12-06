jQuery(document).ready(function(){
	jQuery('body').live('htmlAppending',function(event,htmlContent){
		jQuery(htmlContent).find('.HTMLLanguageFieldMEToWYSIWYG').each(function(){
			var parent=this;
			jQuery(this).sceditor({
				plugins: "xhtml",
				locale : 'fr',
				emoticonsEnabled : false
				});
		});
	});
	jQuery('body').live('closeWindow',function(event,htmlContent){
		jQuery(htmlContent).find('textarea.HTMLLanguageFieldMEToWYSIWYG').each(function(){ jQuery(this).data('sceditor').destroy(); });
	});
});