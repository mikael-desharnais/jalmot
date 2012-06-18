jQuery(document).ready(function(){
	jQuery('.field-validator-target').live('before_submit',function(event,result_return){
		result_return.result=window.FieldValidator.getDescriptor(jQuery(this).data('field-validator')).isValid(jQuery(this),true);
	});
});


// Rules

Rule = function(target){
	this.target=target;
	this.confParams;
	
	this.setConfParams=function(params){
		this.confParams=params;
	};
	this.isValid=function(form,updateDisplay){
		return false;
	};
	this.updateCSS=function(isValid,elements){
		if (!isValid){
			elements.parent().addClass('error');
			elements.parent().removeClass('success');
		}else if (!elements.parent().hasClass('error')) {
			elements.parent().removeClass('error');
			elements.parent().addClass('success');
		}
	};
	this.updateText=function(isValid,elements){
		var helpContent=elements.parent().find('.help-inline');
		if (helpContent.size()==0){
			helpContent=jQuery('<div class="help-inline"></div>');
			elements.parent().append(helpContent);
		}
		if (!isValid){
			helpContent.html(helpContent.html()+"  "+this.replaceParams(this.getText(elements)));
		}
		
	};
	this.getText=function(elements){
		return "Text not Choosen, please Define text for Rule : "+this.constructor.name;
	};
	this.replaceParams=function(text){
		for (var i in this.confParams){
			text=text.replace('%'+i+'%',this.confParams[i]);
		}
		return text;
	}
}
Rule.readFromJSON = function(json){
	var classname=json.class;
	var rule = new window[classname](json.target);
	rule.setConfParams(json.confParams);
	return rule;
};

Switcher = function(target){
	Rule.call(this,target);
	this.cases=new Array();
	this.addCase=function(caseE){
		this.cases.push(caseE);
	}
}
Case = function(){
	
};
Case.readFromJSON = function(json){
	var caseE = new window[json.class]();
	caseE.setConfParams(json.confParams);
	for(var i in json.rules){
		if (typeof json.rules[i].cases !='undefined'){
			caseE.addRule(Switcher.readFromJSON(json.rules[i]));
		}else {
			caseE.addRule(Rule.readFromJSON(json.rules[i]));
		}
	}
	return caseE;
}

Switcher.readFromJSON = function(json){
	var toReturn = Rule.readFromJSON(json);
	for(var i in json.cases){
		toReturn.addCase(Case.readFromJSON(json.cases[i]));
	}
	return toReturn;
}

SimpleValueSwitcherFV = function(target){
	Switcher.call(this,target);
	
	this.isValid=function(form,updateDisplay){
		var toReturn=true;
		var value= jQuery(form).find('[name='+this.target+']').attr('value');
		for(var i in this.cases){
			if (this.cases[i].corresponds(value)){
				var result = this.cases[i].isValid(form,updateDisplay);
				toReturn=toReturn&&result;
			}
		}
		return toReturn;
	}
}
SimpleCheckedValueSwitcherFV = function(target){
	Switcher.call(this,target);
	
	this.isValid=function(form,updateDisplay){
		var toReturn=true;
		var value= jQuery(form).find('[name='+this.target+']:checked').attr('value');
		for(var i in this.cases){
			if (this.cases[i].corresponds(value)){
				var result = this.cases[i].isValid(form,updateDisplay);
				toReturn=toReturn&&result;
			}
		}
		return toReturn;
	}
}

TesterFV = function(){
	this.confParams;
	
	this.setConfParams=function(params){
		this.confParams=params;
	};
	this.rules=new Array();
	
	this.addRule=function(rule){
		this.rules.push(rule);
	};
}

EqualTesterFV = function(){
	TesterFV.call(this);
	this.corresponds=function(value){
		return value==this.confParams['value'];
	};
	this.isValid = function(form,updateDisplay){
		var toReturn=true;
		for(var i in this.rules){
			var result=this.rules[i].isValid(form,updateDisplay);
			toReturn=result&&toReturn;
		}
		return toReturn;
	};
}

//SimpleLengthRule

SimpleLengthRuleFV = function(target){
	Rule.call(this,target);
	
	this.isValid=function(form,updateDisplay){
		var elements=jQuery(form).find('[name='+this.target+']');
		var length=elements.attr('value').length;
		var toReturn= ((typeof this.confParams['maxLength'] =='undefined' || length<=this.confParams['maxLength']) && (typeof this.confParams['minLength'] =='undefined' || length>=this.confParams['minLength']));
		if (updateDisplay){
			this.updateCSS(toReturn,elements);
			this.updateText(toReturn,elements);
		}
		return toReturn;
	};
	this.getText=function(elements){
		if (typeof this.confParams['maxLength'] =='undefined'){
			if (typeof this.confParams['minLength'] =='undefined'){
				return "There is no Condition";
			}
			return elements.data('field-name')+" length should be longer then %minLength%";
		}else {
			if (typeof this.confParams['minLength'] =='undefined'){
				return elements.data('field-name')+" length is limited to %maxLength%";
			}
		}
		return elements.data('field-name')+" length should be between %minLength% and %maxLength%";
	};
}

//SimpleIntegerRuleFV

SimpleIntegerRuleFV = function(target){
	Rule.call(this,target);
	
	this.isValid=function(form,updateDisplay){
		var elements=jQuery(form).find('[name='+this.target+']');
		regexp=/^[0-9]*$/g;
		var toReturn=regexp.test(elements.attr('value'));
		if (updateDisplay){
			this.updateCSS(toReturn,elements);
			this.updateText(toReturn,elements);
		}
		return toReturn;
	};
	this.getText=function(elements){
		return elements.data('field-name')+" should contain only numbers";
	};
}
// SimpleWordRuleFV

SimpleWordRuleFV = function(target){
	Rule.call(this,target);
	this.isValid=function(form,updateDisplay){
		var elements=jQuery(form).find('[name='+this.target+']');
		regexp=/^[a-zA-Z ]*$/g;
		var toReturn=regexp.test(elements.attr('value'));
		if (updateDisplay){
			this.updateCSS(toReturn,elements);
			this.updateText(toReturn,elements);
		}
		return toReturn;
	};
	this.getText=function(elements){
		return elements.data('field-name')+" should contain only letters and spaces";
	};
}


//FieldValidator
FieldValidator=function(){
	
};
FieldValidator.descriptors = new Array();
FieldValidator.addDescriptor=function(descriptor){
	this.descriptors[descriptor.getName()]=descriptor;
};
FieldValidator.getDescriptor=function(name){
	return this.descriptors[name];
};

//FieldVadidatorDescriptor

FieldValidatorDescriptor = function(name){
	this.name=name;
	this.rules=new Array();
	
	this.getName=function(){
		return this.name;
	};
	this.addRule=function(rule){
		this.rules.push(rule);
	};
	this.isValid=function(form,updateDisplay){
		if (updateDisplay){
			jQuery(form).find('.error').removeClass('error');
			jQuery(form).find('.success').removeClass('success');
			jQuery(form).find('.help-inline').html('');
		}
		var toReturn=true;
		for(var i in this.rules){
			var result=this.rules[i].isValid(form,updateDisplay);
			toReturn=result&&toReturn;
		}
		if (updateDisplay){
			if (jQuery('.error').size()>0){
				jQuery(form).closest('.window_frame').animate({ scrollTop: jQuery('.error').first().offset().top}, 2000);
			}

		}
		return toReturn;
	};
}

FieldValidatorDescriptor.readFromJSON = function(json){
	var classname=json.class;
	var fieldValidatorDescriptor = new window[classname](json.name);
	for(var i in json.rules){
		if (typeof json.rules[i].cases !='undefined'){
			fieldValidatorDescriptor.addRule(Switcher.readFromJSON(json.rules[i]));
		}else {
			fieldValidatorDescriptor.addRule(Rule.readFromJSON(json.rules[i]));
		}
	}
	return fieldValidatorDescriptor;
};