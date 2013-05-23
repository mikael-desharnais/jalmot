jQuery(document).ready(function(){
	jQuery('body').live('htmlAppending',function(event,htmlContent){
		jQuery(htmlContent).find('.HTMLLanguageFieldMEToWYSIWYG').each(function(){
			var parent=this;
			var element = jQuery(this).redactor({ 
				buildCallBack : function(){
									jQuery(element.data('redactor').$box).addClass(jQuery(parent).attr('class'));
								},
				path: Ressource.getConfiguration().getValue('domainName')+'/'+Ressource.getConfiguration().getValue('TemplateDirectory')+'lib/redactorjs/' ,
				lang: 'fr'});
			var redactor=element.data('redactor');
			redactor.start();
		});
	});
	jQuery('body').live('closeWindow',function(event,htmlContent){
		jQuery(htmlContent).find('textarea.HTMLLanguageFieldMEToWYSIWYG').each(function(){ jQuery(this).data('redactor').destroy(); });
	});
});