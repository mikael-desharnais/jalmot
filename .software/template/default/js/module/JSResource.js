Resource=function(){
	this.configuration;
	this.language;
	this.getConfiguration=function(){
		return this.configuration;
	};
	this.setConfiguration=function(configuration){
		this.configuration=configuration;
	};
	this.getLanguage=function(){
		return this.language;
	};
	this.setLanguage=function(language){
		this.language=language;
	};
};
Configuration = function(){
	this.values=new Object();
	this.getValue=function(key){
		return this.values[key];
	};
	this.setValue=function(key,value){
		this.values[key]=value;
	};
};
Language = function(id,name){
	this.id = id;
	this.name = name;
	this.translations=new Object();
	this.getTranslation=function(key){
		return this.translations[key];
	};
	this.setTranslation=function(key,value){
		this.translations[key]=value;
	};
};