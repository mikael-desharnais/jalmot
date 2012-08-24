Window=function(){
	this.configuration = {
			draggable: true,
			resizable: true,
			maximizable: true,
			minimizable: true,
			checkBoundary: true
			};
	this.jqueryElement;
	this.url;
	this.htmlElement;
	
	this.setConfiguration = function(config){
		for(var i in config){
			this.configuration[i]=config[i];
		}
	};
	this.setContent=function(htmlContent){
		this.configuration.content=htmlContent;
	};
	this.setURL=function(url){
		this.url=url;
	}
	this.display=function(){
		var parent=this;
		this.configuration.onShow=function(window){
			if (typeof parent.configuration.height =='undefined'){
				setTimeout(function(){
					var height=window.getFrame().height();
					window.getFrame().css('height','auto');
					var newHeight=Math.min(window.getFrame().height(),jQuery(document).height());
					var frameHeight=window.getContainer().height();
					var frameWidth=window.getContainer().width();
					window.getFrame().height(newHeight);
					window.resize(frameWidth,frameHeight+newHeight-height);
				},200);
				
			}
			window.setTitle(window.getContainer().find('h1').html());
			window.getContainer().find('h1').css('display','none');
		};

		if (jQuery.window.getAll().length==0){
			this.configuration.x=50;
			this.configuration.y=50;
		}else {
			var currentWindow=jQuery.window.getSelectedWindow();
			if (typeof(currentWindow)=='undefined'){
				currentWindow=jQuery.window.getAll()[0];
			}
			var center = currentWindow.object.htmlElement.offset().left+(currentWindow.object.htmlElement.width())/2;
			if (center>jQuery(window).width()/2){
				var nextLeft = 50;
			}else {
				var nextLeft = currentWindow.object.htmlElement.offset().left+currentWindow.object.htmlElement.width()+50
			}
			this.configuration.x=nextLeft;
			this.configuration.y=currentWindow.object.configuration.y;
			if (this.configuration.x==Window.formerPosition.x){
				this.configuration.x+=20;
			}
			if (this.configuration.y==Window.formerPosition.y){
				this.configuration.y+=20;
			}
			Window.formerPosition.y=this.configuration.y;
			Window.formerPosition.x=this.configuration.x;
			
		}
		
		this.jqueryElement=jQuery.window(this.configuration);
		this.htmlElement=this.jqueryElement.getContainer();
		this.htmlElement.data('object',this);
		this.htmlElement.addClass('reload-change-listener');
		this.htmlElement.data('ReloadManager',this);
		jQuery('body').trigger('htmlAppending',this.htmlElement);
		this.jqueryElement.object=this;

	}
	this.getHTMLElement=function(){
		this.htmlElement;
	};
	this.reload=function(){
		var parent=this;
		var fetcher=new AjaxHTMLFetcher();
		fetcher.setURL(this.url);
		fetcher.setCallBack(function(content){
			parent.jqueryElement.getFrame().html(content);
			parent.jqueryElement.setTitle(parent.jqueryElement.getContainer().find('h1').html());
			parent.jqueryElement.getContainer().find('h1').css('display','none');
			jQuery('body').trigger('htmlAppending',parent.htmlElement);
		});
		fetcher.fetch();
		fetcher.integrate();
	};
}

Window.formerPosition = new Object();
Window.formerPosition.x = -1;
Window.formerPosition.y = -1;
