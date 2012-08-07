jQuery(document).ready(function(){
	jQuery(".draggableTarget .element .delete").live('click',function(event){
		event.preventDefault();
		jQuery(this).closest('.element').removeClass('element_selected');
		jQuery(this).closest('.element').find('input').attr("disabled","disabled");
		return false;
	});
	jQuery('body').bind('draggablestop',function(event,draggable,droppable){
		droppable.find('.element_'+draggable.attr('draggable-id')).addClass('element_selected');
		droppable.find('.element_'+draggable.attr('draggable-id')+' input').removeAttr('disabled');
		}
	);
});