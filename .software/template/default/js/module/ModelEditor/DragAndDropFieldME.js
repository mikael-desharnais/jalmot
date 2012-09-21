jQuery(document).ready(function(){
	jQuery(".dragAndDropFieldME .element .delete").live('click',function(event){
		event.preventDefault();
		jQuery(this).closest('.element').removeClass('element_selected');
		jQuery(this).closest('.element').find('input').attr("disabled","disabled");
		return false;
	});
	jQuery('body').bind('draggablestop',function(event,draggable,droppable){
		if (jQuery(droppable).hasClass('dragAndDropFieldME')&&(jQuery(droppable).data('target-type')==draggable.data('draggable-type'))){
			droppable.find('.element_'+draggable.data('draggable-id')).addClass('element_selected');
			droppable.find('.element_'+draggable.data('draggable-id')+' input').removeAttr('disabled');
		}
	});
});

