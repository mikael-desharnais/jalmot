jQuery(document).ready(function(){
	jQuery('.tabs .tabTitleGroup .tabTitle a').live('click',function(event){
		event.preventDefault();
		jQuery(this).closest('.tabs').find('.currentTabTitle').removeClass('currentTabTitle').removeClass('active');
		jQuery(this).closest('.tabs').find('.currentTab').removeClass('currentTab');
		jQuery(this).closest('.tabTitle').addClass('currentTabTitle').addClass('active');
		var element = jQuery(this).closest('.tabs').find('.tab'+jQuery(this).closest('.tabTitle').data('tab'));
		var viewPort = element.closest('.tabViewPort');
		var group = viewPort.find('.tabGroup');
		element.addClass('currentTab');
		group.animate({left:(group.offset().left-element.offset().left)+'px'},600);
		return false;
	});
	jQuery('body').live('htmlAppending',function(){
		console.log('ok '+jQuery('.tabs').size());
		jQuery('.tabs .tabViewPort:not(.tabViewPortOK)').each(function(){
			var height=0;
			jQuery(this).find('.tab').each(function(){
				height = Math.max(height,jQuery(this).height());
			});
			var width = jQuery(this).width();
			console.log('ok '+width+'x'+height);
			jQuery(this).css('width',width+'px');
			jQuery(this).css('height',height+'px');
			jQuery(this).find('.tabGroup').css('width',(width*jQuery('.tab').size())+'px');
			jQuery(this).find('.tab').css('width',width+'px').css('float','left');
			jQuery(this).addClass('tabViewPortOK');
		});
	});
});