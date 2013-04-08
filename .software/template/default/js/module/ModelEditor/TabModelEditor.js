jQuery(document).ready(function(){
	jQuery('.tabs .tabTitleGroup .tabTitle a').live('click',function(event){
		event.preventDefault();
		var idTab = jQuery(this).closest('.tabTitle').data('tab');
		jQuery(this).closest('.tabs').find('.currentTabTitle').removeClass('currentTabTitle').removeClass('active');
		jQuery(this).closest('.tabs').find('.currentTab').removeClass('currentTab');
		jQuery(this).closest('.tabTitle').addClass('currentTabTitle').addClass('active');
		var element = jQuery(this).closest('.tabs').find('.tab'+idTab);
		var viewPort = element.closest('.tabViewPort');
		var group = viewPort.find('.tabGroup');
		element.addClass('currentTab');
		group.animate({left:(group.offset().left-element.offset().left)+'px'},600,'swing',function(){
			jQuery(this).closest('.tabManager').trigger('tabChange',jQuery(this));
		});
		jQuery(this).closest('.reload-change-listener').data('ReloadManager').url.params['tab']=idTab;
		return false;
	});
	jQuery('body').live('htmlAppending',function(){
		jQuery('.tabs .tabViewPort:not(.tabViewPortOK)').each(function(){
			var height=0;
			jQuery(this).find('.tab').each(function(){
				height = Math.max(height,jQuery(this).height());
			});
			height=height+20;
			var width = jQuery(this).width();
			jQuery(this).css('width',width+'px');
			jQuery(this).css('height',height+'px');
			jQuery(this).find('.tabGroup').css('width',(width*jQuery('.tab').size())+'px');
			jQuery(this).find('.tab').css('width',width+'px').css('float','left');
			jQuery(this).addClass('tabViewPortOK');
			if (typeof jQuery(this).closest('.reload-change-listener').data('ReloadManager').url.params['tab'] == 'undefined'){
				jQuery(this).closest('.reload-change-listener').data('ReloadManager').url.params['tab']=0;
			}else {
				var currentTab = jQuery(this).closest('.reload-change-listener').data('ReloadManager').url.params['tab'];
				var element = jQuery(this).closest('.tabs').find('.tab'+currentTab);
				var group = jQuery(this).find('.tabGroup');
				jQuery(this).closest('.tabs').find('.currentTabTitle').removeClass('currentTabTitle').removeClass('active');
				jQuery(this).closest('.tabs').find('.currentTab').removeClass('currentTab');
				jQuery(this).closest('.tabs').find('.tabTitle'+currentTab).addClass('currentTabTitle').addClass('active');
				element.addClass('currentTab');
				jQuery(this).closest('.tabManager').trigger('tabChange',element);
				group.css({left:(group.offset().left-element.offset().left)+'px'});
			}
		});
	});
});