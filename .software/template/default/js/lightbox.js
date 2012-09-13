function openLightBox(name){
	jQuery("#blackAlphaDiv").fadeIn();
	jQuery("#"+name).fadeIn();
	jQuery("#"+name).css('opacity','0');
	jQuery("#"+name).css('display','block');
	positionLightBox(name);
	document.multipleSelector=false;
	adaptLightBoxBackground();
}
function closeLightBox(name){
	jQuery("#blackAlphaDiv").fadeOut();
	jQuery("#"+name).fadeOut();
	document.multipleSelector=true;
}
function positionLightBox(name){
	jQuery("#"+name).css('left',(jQuery(window).width()-jQuery("#"+name).width())/2);
	jQuery("#"+name).css('top',(Math.max(jQuery(window).height(),300)-jQuery("#"+name).height())/2);
	jQuery("#"+name).css('position','absolute');
}
function adaptLightBoxBackground(){
	jQuery("#blackAlphaDiv").css('left',jQuery(window).scrollLeft());
	jQuery("#blackAlphaDiv").css('top',jQuery(window).scrollTop());
	jQuery("#blackAlphaDiv").css('width',jQuery(window).width());
	jQuery("#blackAlphaDiv").css('height',jQuery(window).height());
}
