jQuery(document).ready(function(){
	jQuery('.context-menu-actionner').live('contextmenu',function(event){
		if (event.which==3){
			event.preventDefault();
			if (jQuery(this).data('context-menu')!='NoContextMenu'){
				ContextMenu.setCurrentContextMenu(ContextMenu.getContextMenu(jQuery(this).data('context-menu')));
				ContextMenu.getCurrentContextMenu().setCurrentActionner(jQuery(this));
				ContextMenu.getCurrentContextMenu().display(event.pageX,event.pageY);
			}
			return false;
		}
	});
	jQuery('body').mousedown(function(){
		var date = new Date();
		if (typeof(ContextMenu.getCurrentContextMenu())!='undefined'){
			ContextMenu.getCurrentContextMenu().remove();
		}
	});
});

ContextMenu=function(){
	
};
ContextMenu.contextMenus=new Array();
ContextMenu.currentContextMenu;
ContextMenu.setCurrentContextMenu=function(contextMenu){
	ContextMenu.currentContextMenu=contextMenu;
}
ContextMenu.getCurrentContextMenu=function(){
	return ContextMenu.currentContextMenu;
}
ContextMenu.addContextMenu=function(contextMenu){
	ContextMenu.contextMenus[contextMenu.getName()]=contextMenu;
}
ContextMenu.getContextMenu=function(contextMenuName){
	return ContextMenu.contextMenus[contextMenuName];
}
ContextMenu.readFromJSON=function(json){
	var classname=json.class;
	var contextMenuDescriptor = new window[classname](json.name);
	contextMenuDescriptor.confParams=json.confParams;
	for(var i in json.elements){
		eval('contextMenuDescriptor.addElement('+json.elements[i].class+'.readFromJSON(contextMenuDescriptor,json.elements[i]));');
	}
	return contextMenuDescriptor;
	
};

ContextMenuDescriptor = function(name){
	this.name=name;
	this.confParams;
	this.htmlElement;
	this.actionner;
	this.displayTime;
	this.elements=new Array();
	
	this.getName=function(){
		return this.name;
	};
	this.setCurrentActionner=function(actionner){
		this.actionner = actionner;
	};
	this.addElement=function(element){
		this.elements.push(element);
	};
	this.toHTML=function(x_position,y_position){
		if (this.htmlElement!=undefined){
			this.htmlElement.remove();
		}
		this.htmlElement = jQuery('<ul class="contextMenu"/>');
		for(var i in this.elements){
			this.htmlElement.append(this.elements[i].toHTML());
		}
	}
	this.display=function(x_position,y_position){
		this.toHTML();
		jQuery('body').append(this.htmlElement);
		this.htmlElement.css({top : y_position+'px', left : x_position+'px'}).fadeIn();
		this.displayTime = (new Date()).getTime();
	}
	this.remove=function(){
		var parent=this;
		setTimeout(function(){
			if (((new Date()).getTime()-parent.displayTime)>200){
				parent.htmlElement.remove();
			}
		},100);
	}
};

ElementCM = function(title){
	this.title = title;
	this.confParams;
	this.htmlElement;
	this.descriptor;
	this.getTitle=function(){
		return this.title;
	};

	this.toHTML=function(){
		this.htmlElement = jQuery('<li/>');
		this.actionner = jQuery('<a href="#">'+this.getTitle()+'</a>');
		var parent=this;
		this.actionner.click(function(event){
			event.preventDefault();
			parent.clicked();
			return false;
		});
        this.htmlElement.append(this.actionner);
        return this.htmlElement;
	};
};

ModelEditorElementCM=function(title){
	ElementCM.call(this,title);
	this.model;
	this.modelEditor;
	this.mode;
	this.actionner;
	this.clicked=function(){
		var parent = this;
		var fetcher=new AjaxHTMLFetcher();
		if (this.mode=="edit"){
			fetcher.setURL(new URL('/'+Ressource.getConfiguration().getValue('AliasName')+'model_editor/'+this.modelEditor+'/',this.descriptor.actionner.data('url-params')));
		}else if (this.mode=="create") {
			fetcher.setURL(new URL('/'+Ressource.getConfiguration().getValue('AliasName')+'model_editor/'+this.modelEditor+'/','source=create'));
		}else {
			fetcher.setURL(new URL('/'+Ressource.getConfiguration().getValue('AliasName')+'model_editor/'+this.modelEditor+'/delete/',this.descriptor.actionner.data('url-params')));
		}
		if (this.mode=="delete"){
			fetcher.setCallBack(function(htmlCont){
				ReloadManager.propagateChangeEvent(parent.descriptor.actionner);
			});
		}else {
			fetcher.setCallBack(function(htmlCont){
				var window = new Window();
				window.setConfiguration(this.windowConfiguration);
				window.setContent(htmlCont);
				window.setURL(this.url);
				window.display();
			});
		}
		fetcher.fetch();
		fetcher.integrate();
	};
};

SourceParamsModelEditorElementCM=function(title){
	ModelEditorElementCM.call(this,title);
	
	this.clicked=function(){
		var parent = this;
		var fetcher=new AjaxHTMLFetcher();
		if (this.mode=="edit"){
			fetcher.setURL(new URL('/'+Ressource.getConfiguration().getValue('AliasName')+'model_editor/'+this.modelEditor+'/',this.descriptor.actionner.data('url-params')));
		}else if (this.mode=="create") {
			fetcher.setURL(new URL('/'+Ressource.getConfiguration().getValue('AliasName')+'model_editor/'+this.modelEditor+'/','source=create&'+this.descriptor.actionner.closest('.window_panel').data('object').url.params));
		}else {
			fetcher.setURL(new URL('/'+Ressource.getConfiguration().getValue('AliasName')+'model_editor/'+this.modelEditor+'/delete/',this.descriptor.actionner.data('url-params')));
		}
		if (this.mode=="delete"){
			fetcher.setCallBack(function(htmlCont){
				ReloadManager.propagateChangeEvent(parent.descriptor.actionner);
			});
		}else {
			fetcher.setCallBack(function(htmlCont){
				var window = new Window();
				window.setConfiguration(this.windowConfiguration);
				window.setContent(htmlCont);
				window.setURL(this.url);
				window.display();
			});
		}
		fetcher.fetch();
		fetcher.integrate();
	};

	this.toHTML=function(){
		this.htmlElement = jQuery('<li/>');
		this.actionner = jQuery('<a href="#">'+this.getTitle()+'</a>');
		var parent=this;
		this.actionner.click(function(event){
			event.preventDefault();
			parent.clicked();
			return false;
		});
        this.htmlElement.append(this.actionner);
        return this.htmlElement;
	};
};

ModelEditorElementCM.readFromJSON=function(descriptor,json){
	var element = new ModelEditorElementCM(json.title);
	element.descriptor = descriptor;
	element.confParams=json.confParams;
	element.model = json.model;
	element.modelEditor = json.modelEditor;
	element.mode = json.mode;
	return element;
}

SourceParamsModelEditorElementCM.readFromJSON=function(descriptor,json){
	var element = new SourceParamsModelEditorElementCM(json.title);
	element.descriptor = descriptor;
	element.confParams=json.confParams;
	element.model = json.model;
	element.modelEditor = json.modelEditor;
	element.mode = json.mode;
	return element;
}