jQuery(document).ready(function(){
	jQuery('input.number-content').live('keyup', function(e) {
		jQuery(this).val(jQuery(this).val().replace(/[^0-9-.]/g, ''));
	});
});

