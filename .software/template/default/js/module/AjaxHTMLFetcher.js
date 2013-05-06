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
	this.status;
	this.message;
	this.params;
	this.beforeCall;
	this.continueCall=true;
	this.hider = jQuery('.loadHider');

	
	this.setURL=function(url){
		this.url=url;
	};
	this.setCallBack=function(callback){
		this.callback=callback;
	};
	this.setBeforeCall=function(beforeCall){
		this.beforeCall=beforeCall;
	};
	this.fetch=function(){
		var parent=this;
		if (typeof this.beforeCall != "undefined"){
			this.beforeCall();
		}
		if (!this.continueCall){
			return;
		}
		
		if (typeof this.hider.data('counter') == 'undefined'){
			this.hider.data('counter',0);
		}
		this.hider.data('counter',this.hider.data('counter')+1);
		this.hider.addClass('loadHiderVisible');

		jQuery.post(parent.url.address,
					parent.url.toQueryString(),
					function(data){
						var json = null;
						try {
							json=jQuery.parseJSON(data);
						} catch (e) {
							parent.message = 'Error Parsing JSON';
							parent.html = data;
							parent.status = 0;
						}
						if (json!=null){
							parent.css=json.css;
							parent.js=json.js;
							parent.html=json.html;
							parent.message=json.message;
							parent.status=json.status;
							parent.params=json.params;
						}
						parent.integrate();
					});

	};
	this.integrate=function(){
		if (this.continueCall){
			var parent=this;
			this.integrateCSS(0,function(){
				parent.integrateJS(0,function(){
					parent.hider.data('counter',parent.hider.data('counter')-1);
					if (parent.hider.data('counter')<=0){
						parent.hider.removeClass('loadHiderVisible');
						parent.hider.data('counter',0);
					}
					parent.integrateHTML();
				});
			});
		}
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
			var script=jQuery('<link rel="stylesheet" href="'+this.css[counter]+'?_='+(Math.random()*10000000000000000)+'" type="text/css">');
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
			jQuery.getScript(this.js[counter]).done(function(){
				parent.integrateJS(counter+1,callback);
			}).fail(function(jqxhr, settings, exception){
				parent.integrateJS(counter+1,callback);
			});
			JSLoadedFiles.push(this.js[counter]);
		}else {
			this.integrateJS(counter+1,callback);
		}

	};
	this.integrateHTML=function(){
		jQuery('body').trigger('AjaxHTMLFetcherStatus',this);
		if (this.status==0){
			this.html = '<h1>Une erreur s\'est produite : '+this.message+'</h1><pre>'+this.html+'</pre>';
		}
		if (this.status!=404){
			this.callback(this.html);
		}
	};
}
function URL(address,params){
	this.address=address;
	this.params=params;
	this.addParams=function(params){
		for(var i in params){
			var key = i;
			var value = params[i]
			if (i.slice(-2)=='[]'){
				key = i.slice(0,-2);
				value = (typeof this.params[key] != "undefined"?this.params[key]:new Array());
				value.push(params[i]);
			}
			this.params[key]=value;
		}
	}
	this.addSerializedArrayParams=function(params){
		for(var i in params){
			var modifiedArray = new Array();
			modifiedArray[params[i].name]=params[i].value;
			this.addParams(modifiedArray);
		}
		
	}
	this.toQueryString=function(){
		return jQuery.param(this.params);
	}
};


jQuery('.AjaxHTMLFetcher').live('click',function(event){
	event.preventDefault();
	var fetcher=new AjaxHTMLFetcher();
	fetcher.setURL(AjaxHTMLFetcher.fetchers[jQuery(this).attr('id')].url);
	fetcher.setCallBack(AjaxHTMLFetcher.fetchers[jQuery(this).attr('id')].callback);
	fetcher.setBeforeCall(AjaxHTMLFetcher.fetchers[jQuery(this).attr('id')].beforeCall);
	fetcher.fetch();
	return false;
});
