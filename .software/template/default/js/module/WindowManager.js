jQuery(document).ready(function(){
});

Window = function(){
	this.config;
	this.content;
	this.url;
	this.wrapper = jQuery('<div class="windowPanel"></div>');
	this.html = jQuery('<div class="windowContent window_frame"></div>');
	this.closeButton = jQuery('<a href="#" class="closeButton icon-remove"></a>');
	this.reloadButton = jQuery('<a href="#" class="updateButton icon-repeat"></a>');
	this.html.data('object',this);
	this.html.data('ReloadManager',this);
	this.html.addClass('reload-change-listener');
	var parent = this;
	this.lockScroll=false;
	this.interval;

	this.status=false;
	this.requireReload=false;
	this.zIndex = Window.currentZIndex++;

	Window.instances.push(this);

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
		Window.instances.splice(Window.instances.indexOf(this), 1);
		delete Window.instances[this.zIndex];
		this.wrapper.remove();
		delete this;
		Window.activateWindow();
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
	this.setActive = function(status){
		this.status = status;
		//console.log(this.status);
		//console.log(this.requireReload);
		if (this.status&&this.requireReload){
			this.internalReload();
		}
	}
	this.display = function(){
		var parent = this;
		this.wrapper.css('zIndex',Window.currentZIndex++);
		this.wrapper.outerHeight(jQuery(window).height()+'px');
		jQuery('.windowHeight').outerHeight(jQuery(window).height()+'px');
		this.wrapper.outerWidth(jQuery(window).width()-290);
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
		if (this.status){
			this.internalReload();
		}else {
			this.requireReload = true;
		}
	};
	this.internalReload=function(){
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
		this.requireReload=false;
	};
	this.setScroll=function(){
		this.wrapper.scrollTop(this.url.scrollTop);
		if (this.url.scrollTop==this.wrapper.scrollTop()){
			clearInterval(this.interval);
		}

	}

	Window.setActiveWindow(this);
}
Window.instances = new Array();
Window.currentZIndex = 5;
Window.activeWindow = null;
Window.activateWindow=function(){
	if (Window.instances.length==0){
		Window.setActiveWindow(null);
	}else {
		Window.setActiveWindow(Window.instances[Window.instances.length-1]);
	}
}
Window.setActiveWindow=function(windowToSet){
	if (Window.activeWindow!=null){
		Window.activeWindow.setActive(false);
	}
	Window.activeWindow = windowToSet;
	if (windowToSet!=null){
		windowToSet.setActive(true);
	}
}
