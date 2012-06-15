Ressource=function(){
	this.configuration;
	this.getConfiguration=function(){
		return this.configuration;
	};
	this.setConfiguration=function(configuration){
		this.configuration=configuration;
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
}