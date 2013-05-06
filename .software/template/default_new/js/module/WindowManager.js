jQuery(document).ready(function(){
});

Window = function(){
	this.config;
	this.content;
	this.url;
	this.wrapper = jQuery('<div class="windowPanel"></div>');
	this.html = jQuery('<div class="windowContent window_frame"></div>');
	this.closeButton = jQuery('<a href="#" class="closeButton"></a>');
	this.reloadButton = jQuery('<a href="#" class="updateButton"></a>');
	this.html.data('object',this);
	this.html.data('ReloadManager',this);
	this.html.addClass('reload-change-listener');
	var parent = this;
	this.lockScroll=false;
	this.interval;
	
	jQuery(window).resize(function(){
		parent.wrapper.outerHeight(jQuery(window).height());
		parent.wrapper.outerWidth(jQuery(window).width()-80);
	});

	this.closeButton.click(function(){
		parent.close();
	});
	this.reloadButton.click(function(){
		parent.reload();
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
		jQuery('.windowHeight').outerHeight(jQuery(window).height()+'px');
		this.wrapper.outerWidth(jQuery(window).width()-80);
		this.wrapper.append(this.html);
		parent.url.scrollTop=0;
		this.wrapper.scroll(function(){
			if (!parent.lockScroll){
				parent.url.scrollTop = jQuery(this).scrollTop();
			}
		});
		this.wrapper.append(this.closeButton);
		this.wrapper.append(this.reloadButton);
		jQuery('.windowContainer').append(this.wrapper);
		this.html.append(this.content);
		jQuery('body').trigger('htmlAppending',this.html);
	}
	this.reload=function(){
		var parent=this;
		parent.lockScroll=true;
		var fetcher=new AjaxHTMLFetcher();
		fetcher.setURL(this.url);
		fetcher.setCallBack(function(content){
			parent.html.html(content);
			jQuery('body').trigger('htmlAppending',parent.html);
			parent.interval = setInterval(function(){parent.setScroll()},300);
			parent.lockScroll=false;
		});
		fetcher.fetch();
		fetcher.integrate();
	};
	this.setScroll=function(){
		this.wrapper.scrollTop(this.url.scrollTop);
		if (this.url.scrollTop==this.wrapper.scrollTop()){
			clearInterval(this.interval);
		}
		
	}
}
Window.currentZIndex = 5;

WebFontConfig = {
	    google: { families: [ 'Montaga::latin' ] }
	  };
(function() {
  var wf = document.createElement('script');
  wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
    '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
  wf.type = 'text/javascript';
  wf.async = 'true';
  var s = document.getElementsByTagName('script')[0];
  s.parentNode.insertBefore(wf, s);
})();
