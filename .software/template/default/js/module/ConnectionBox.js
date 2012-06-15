jQuery(document).ready(function(){
	startLogin();
	jQuery(window).resize(function(){
		positionLightBox("connectionDiv");
		adaptLightBoxBackground();
	});
	jQuery(window).scroll(function(){
		positionLightBox("connectionDiv");
		adaptLightBoxBackground();
	});
	jQuery('#closeLogin').click(function(event){
		event.preventDefault();
		closeLightBox("connectionDiv");
		return false;
	});
});

function startLogin(){
	openLightBox("connectionDiv");
}