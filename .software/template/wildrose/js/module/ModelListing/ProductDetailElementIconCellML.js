jQuery(document).ready(function(){
	jQuery(".ProductDetailElementIcon a").live('click',function(event){
		event.preventDefault();
		return false;
	});
	jQuery(".ProductDetailElementIcon.ProductDetailCategory a").live('click',function(event){
		event.preventDefault();
		var reloader = jQuery(this).closest('.reload-change-listener').data('object');
		reloader.url.params='id[idProductDetailCategory]='+jQuery(this).data('idproductdetailcategory');
		reloader.reload();
		return false;
	});

	jQuery('body').bind('draggablestop',function(event,draggable,droppable,originalDraggable){
		if (jQuery(droppable).hasClass('ProductDetailCategory')&&(jQuery(droppable).data('target-type')==draggable.data('draggable-type'))){
			var fetcher=new AjaxHTMLFetcher();
			fetcher.setURL(new URL(Ressource.getConfiguration().getValue('AliasName')+'model_editor/ProductDetailCategory/save/','id[idProductDetailCategory]='+originalDraggable.data('idproductdetailcategory')+'&idParentProductDetailCategory='+droppable.data('idproductdetailcategory')));
			fetcher.setCallBack(function(htmlCont){
				ReloadManager.propagateChangeEvent(originalDraggable);
				draggable.remove();
			});
			fetcher.fetch();
			fetcher.integrate();
			
		}
	});

});

