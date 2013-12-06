jQuery(document).ready(function(){
	jQuery('body').live('htmlAppending',function(event,htmlContent){
		jQuery(htmlContent).find('.HTMLLanguageFieldMEToWYSIWYG').each(function(){
			var parent=this;
			var element = jQuery(this).sceditor({
				plugins: "xhtml",
				emoticonsEnabled : false
				});
			var redactor=element.data('redactor');
		});
	});
	jQuery('body').live('closeWindow',function(event,htmlContent){
		jQuery(htmlContent).find('textarea.HTMLLanguageFieldMEToWYSIWYG').each(function(){ jQuery(this).data('redactor').destroy(); });
	});
});