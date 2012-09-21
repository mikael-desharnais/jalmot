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
	this.currentId = Window.currentId++;
	this.windowManager;
	
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
		this.jqueryElement=jQuery('<div class="frameElement"><a href="" class="close"></a><div class="content"></div></div>');
		this.jqueryElement.find('.close').click(function(event){
			event.preventDefault();
			parent.close();
			return false;
		});

		this.htmlElement=this.jqueryElement.find('.content');
		this.htmlElement.append(this.configuration.content);
		this.htmlElement.data('object',this);
		this.htmlElement.addClass('reload-change-listener');
		this.htmlElement.data('ReloadManager',this);
		jQuery('body').trigger('htmlAppending',this.htmlElement);
		this.jqueryElement.object=this;
		window.windowManager.append(this);

	}
	this.getHTMLElement=function(){
		this.htmlElement;
	};
	this.reload=function(){
		var parent=this;
		var fetcher=new AjaxHTMLFetcher();
		fetcher.setURL(this.url);
		fetcher.setCallBack(function(content){
			parent.htmlElement.html(content);
			jQuery('body').trigger('htmlAppending',parent.htmlElement);
		});
		fetcher.fetch();
		fetcher.integrate();
	};
	this.close=function(){
		this.jqueryElement.remove();
	};
}

Window.currentId=0;

WindowManager = function(htmlElement){
	
	this.modes=new Array(0,0,2,3,3,4);
	
	this.htmlElement = htmlElement;
	this.windowList = new Array();
	
	this.mode = 4;
	
	this.htmlElement.addClass('windowManagerMode'+this.mode);
	
	this.htmlElement.find('.activeFrame').removeClass('activeFrame');
	for (var i=1;i<=this.modes[this.mode];i++){
		this.htmlElement.find('.frame'+i).addClass('activeFrame');
	}
	
	this.append=function(wind){
		this.windowList.push(wind);
		wind.windowManager = this;
		var countActiveFrames = this.htmlElement.find('.activeFrame').size();
		this.htmlElement.find('.frame'+((wind.currentId%countActiveFrames)+1)).append(wind.jqueryElement);
	};
};
jQuery(document).ready(function(){
	window.windowManager = new WindowManager(jQuery('.windowManager'));
});