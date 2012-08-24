AjaxHTMLFetcher.setFetcher=function(id,url,callback){
	if (AjaxHTMLFetcher.fetchers==undefined){
		AjaxHTMLFetcher.fetchers=new Object();
	}
	AjaxHTMLFetcher.fetchers[id]=new Object();
	AjaxHTMLFetcher.fetchers[id].url=url;
	AjaxHTMLFetcher.fetchers[id].callback=callback;
	return AjaxHTMLFetcher.fetchers[id];
};
function AjaxHTMLFetcher(){
	this.html;
	this.css=new Array();
	this.js=new Array();
	this.url;
	this.callback;

	this.setURL=function(url){
		this.url=url;
	};
	this.setCallBack=function(callback){
		this.callback=callback;
	};
	this.fetch=function(){
		var parent=this;
		jQuery.ajaxSetup({'async' : false});
		jQuery.post(this.url.address,
					this.url.params,
					function(data){
						json=jQuery.parseJSON(data);
						if (json!=null){
							parent.css=json.css;
							parent.js=json.js;
							parent.html=json.html;
						}
					});
	};
	this.integrate=function(){
		var parent=this;
		this.integrateCSS(0,function(){
			parent.integrateJS(0,function(){
				parent.integrateHTML();
			});
		});
	};
	this.integrateCSS=function(counter,callback){
		var parent=this;
		
		if (counter>=this.css.length){
			callback();
			return;
		}
		this.css[counter]=this.css[counter].replace('//','/');
		var found=false;
		for (var j in CSSLoadedFiles){
			CSSLoadedFiles[j]=CSSLoadedFiles[j].replace('//','/');
			var filename=CSSLoadedFiles[j];
			if (filename==this.css[counter]){
				found=true;
			}
		}
		if (!found){
			var script=jQuery('<link rel="stylesheet" href="'+this.css[counter]+'" type="text/css">');
			jQuery('head').append(script);
			CSSLoadedFiles.push(this.css[counter]);
		}
		this.integrateCSS(counter+1,callback);
	};
	this.integrateJS=function(counter,callback){
		var parent=this;
		if (counter>=this.js.length){
			callback();
			return;
		}
		this.js[counter]=this.js[counter].replace('//','/');
		var found=false;
		for (var j in JSLoadedFiles){
			JSLoadedFiles[j]=JSLoadedFiles[j].replace('//','/');
			var filename=JSLoadedFiles[j];
			if (filename==this.js[counter]){
				found=true;
			}
		}
		if (!found){
			jQuery.getScript(this.js[counter],function(){
				parent.integrateJS(counter+1,callback);
			});
			JSLoadedFiles.push(this.js[counter]);
		}else {
			this.integrateJS(counter+1,callback);
		}
	};
	this.integrateHTML=function(){
		this.callback(this.html);
	};
}
function URL(address,params){
	this.address=address;
	this.params=params;
};


jQuery('.AjaxHTMLFetcher').live('click',function(event){
	event.preventDefault();
	var fetcher=new AjaxHTMLFetcher();
	fetcher.setURL(AjaxHTMLFetcher.fetchers[jQuery(this).attr('id')].url);
	fetcher.setCallBack(AjaxHTMLFetcher.fetchers[jQuery(this).attr('id')].callback);
	fetcher.fetch();
	fetcher.integrate();
	return false;
});
