jQuery(document).ready(function(){
});

Window = function(){
	this.config;
	this.content;
	this.url;
	this.wrapper = jQuery('<div class="windowPanel"></div>');
	this.html = jQuery('<div class="windowContent window_frame"></div>');
	this.closeButton = jQuery('<a href="#" class="closeButton"></a>');
	this.html.data('object',this);
	this.html.data('ReloadManager',this);
	this.html.addClass('reload-change-listener');
	var parent = this;
	
	this.wrapper.scroll(function(){
		console.log(parent.wrapper.scrollTop());
	});
	
	jQuery(window).resize(function(){
		parent.wrapper.outerHeight(jQuery(window).height());
	});
	
	this.closeButton.click(function(){
		parent.close();
	});
	this.close = function(){
		this.wrapper.remove();
	};
	
	this.setConfiguration = function(config){
		this.config = config;
	}
	this.setContent = function(content){
		this.content = content;
	}
	this.setURL = function(url){
		this.url = url;
	}
	this.display = function(){
		var parent = this;
		this.wrapper.css('zIndex',Window.currentZIndex++);
		this.wrapper.outerHeight(jQuery(window).height()+'px');
		this.wrapper.append(this.html);
		this.wrapper.append(this.closeButton);
		jQuery('.windowContainer').append(this.wrapper);
		jQuery('body').trigger('htmlAppending',this.html);
		this.html.append(this.content);
	}
	this.reload=function(){
		var parent=this;
		var fetcher=new AjaxHTMLFetcher();
		fetcher.setURL(this.url);
		fetcher.setCallBack(function(content){
			parent.html.html(content);
			jQuery('body').trigger('htmlAppending',parent.html);
		});
		fetcher.fetch();
		fetcher.integrate();
	};
}
Window.currentZIndex = 5;
