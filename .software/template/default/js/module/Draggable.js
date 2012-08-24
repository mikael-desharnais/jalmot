jQuery(document).ready(function(){
	jQuery('body').bind('htmlAppending',function(event,htmlElement){
		jQuery('.draggableInField').draggable( "destroy" );
		jQuery('.draggableInField').draggable({ helper: "clone" ,  zIndex: 2700 , appendTo: "body" });
		jQuery('.draggableTarget').droppable( "destroy" );
		jQuery('.draggableTarget').droppable({ 
			drop: function(event,ui){
				jQuery('body').trigger('draggablestop',new Array(ui.helper,jQuery(this)))},
			over : function(event,ui){
				ui.helper.trigger('draggableover',ui.helper);
			}
		
		});
	});
	jQuery('body').trigger('htmlAppending',jQuery('body'));
});