jQuery(document).ready(function(){
	jQuery('.tabs .tabTitleGroup .tabTitle a').live('click',function(event){
		event.preventDefault();
		jQuery(this).closest('.tabs').find('.currentTabTitle').removeClass('currentTabTitle').removeClass('active');
		jQuery(this).closest('.tabs').find('.currentTab').removeClass('currentTab');
		jQuery(this).closest('.tabTitle').addClass('currentTabTitle').addClass('active');
		jQuery(this).closest('.tabs').find('.tab'+jQuery(this).closest('.tabTitle').data('tab')).addClass('currentTab');
		return false;
	});
});
