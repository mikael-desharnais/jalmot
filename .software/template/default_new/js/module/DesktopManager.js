jQuery(document).ready(function(){
	jQuery('.desktop').outerHeight(jQuery(window).height());
	jQuery('.windowManager').outerHeight(jQuery(window).height());
	jQuery('.windowManager').outerWidth(jQuery(window).width()-75);
	jQuery(window).resize(function(){
		jQuery('.desktop').outerHeight(jQuery(window).height());
		jQuery('.windowManager').outerHeight(jQuery(window).height());
		jQuery('.windowManager').outerWidth(jQuery(window).width()-75);
	});
	jQuery('.desktop').data('mouseenter',false);
	jQuery('.desktop').mouseenter(function(){
		jQuery(this).data('mouseenter',true);
	});
	jQuery('.desktop').mouseleave(function(){
		jQuery(this).data('mouseenter',false);
		jQuery('.desktop img').animate({width : '40px'},400);
	});
	jQuery('html').mousemove(function(event){
		jQuery('.desktop').each(function(){
				jQuery(this).find('.icon').each(function(){
					var xDistance = event.pageX-jQuery(this).offset().left-jQuery(this).width()/2;
					var yDistance = event.pageY-jQuery(this).offset().top-jQuery(this).height()/2;
					var distance = Math.sqrt(xDistance*xDistance+yDistance*yDistance);
					var rapport = Math.max(120-distance,0)/120;
					jQuery(this).find('img').width((40*(1+rapport)));
				}); 
		});
	});
});