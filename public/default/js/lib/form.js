// JavaScript Document
var MessageForm = {
	
	formid: "",
	handler: "",
	validateHandler: "",
	clear: true,
	
	//hier vielleicht automatisieren, dass form die richtige id hat -> so dass answer zugeordnet werden kann
	init:function(form, handler, clear, validate){
    	MessageForm.handler = handler;
		MessageForm.validateHandler = validate;
	  	MessageForm.hideErrors();
		MessageForm.clear = clear;
		//var feed = $$('.send .input');
		form.invoke('observe', 'click', MessageForm.validate);
		var formup = form.invoke('up', 'form');
		//formup.invoke('observe','submit', MessageForm.validate);
		
		formup.invoke('observe','keydown', MessageForm.validateKeyInput);
	},
	
	validateKeyInput:function(ev){
		if(ev.keyCode == 13){
			if(ev.element().type != "button"){
				ev.preventDefault();
			}
		}
	},
	
	hideErrors:function(){
    	var errorspans = $$('.error');
    	errorspans.invoke('hide');
  	},
	
	validate:function(ev){
    	
    	MessageForm.hideErrors();
    	
		var formelm = ev.element().up('form');
		
		MessageForm.formid = MessageForm.getID(formelm, "contact");
		$('answer' + MessageForm.formid).innerHTML = "Pr&uuml;fe Angaben ...";
		
		var checklist = MessageForm.checkForm(formelm);
		
		if(MessageForm.validateHandler != null){
			var checklist2 = MessageForm.validateHandler(formelm);
			checklist = checklist.concat(checklist2);
		}
				
    	if(checklist.length == 0){
      		var Input = formelm.serialize({hash:true});
			
      		if(MessageForm.clear){
				MessageForm.clearForm(formelm);
			}
  			
  			$('answer' + MessageForm.formid).innerHTML = "Schicke Nachricht ... ";
  			MessageForm.prepareRequest("send/", "post", MessageForm.handler, Input);
    	}else{
      		for(var i = 0;i < checklist.length; i++){
        		var errorspan = checklist[i].next('span');
        		errorspan.show();
      		}
			$('answer' + MessageForm.formid).innerHTML = "Bitte &uuml;berpr&uuml;fe deine Angaben!";
    	}
	},
	
	getID:function(elm, string){
		var spanelm = elm.id;
		//var begin = spanelm.indexOf("showfeed") + 1;
		var formid = spanelm.substring(string.length, spanelm.length);
		
		return formid;
	},
	
	checkForm:function(formelm) {
	   
  	    var check = true;
    	var checklist = new Array();
      	var elm = formelm.getElements();
      	for(i = 0; i < elm.length; i++) {
        	if(elm[i].name!= "send" ){
				switch(elm[i].readAttribute("elm")){
					//hier mit eval()?
                	case "name":  check = MessageForm.checkForLetters(elm[i].value);
                  		break;
                	case "text":  check = MessageForm.checkForText(elm[i].value);
                  		break;
                	case "email": check = MessageForm.checkForEmail(elm[i].value);
                  		break;
                	case "number": check = MessageForm.checkForNumber(elm[i].value);
                  		break;
					case "digital": check = MessageForm.checkForDigital(elm[i].value);
                  		break;
            	}
            
            	if(!check){
              		checklist.push(elm[i]);
              		check = true;
            	}
        	}
  		}
  		return checklist;
  	},
  
  	checkForLetters:function(value){
      	var stringpattern = /^[a-zA-ZöÖüÜäÄß\s-]+$/;
      	check = stringpattern.test(value);
		return check;
  	},
  
  	checkForText:function(value){
      	return value != "";
  	},
  	
	checkForDigital:function(value){
		//var stringpattern = /^[0-9]+$/;
		var stringpattern = /^[1-9][0-9]*$/;
      	check = stringpattern.test(value);
		
      	return check;
	},
	
  	checkForNumber:function(value){
		var stringpattern = /^0[0-9]+[\/]*[0-9]+$/;
		// 0+[0-9];
      	var check = stringpattern.test(value);
		if(!check){
			var stringpattern2 = /^[\+]*[0-9]+[\/]*[0-9]+$/;
			check = stringpattern2.test(value);
		}
		
      	return check;
  	},
	
  	checkForEmail:function(value){
      	return /^[a-z0-9_+.-]+\@([a-z0-9-]+\.)+[a-z0-9]{2,4}$/i.test( value );  
  	},
  
  	clearForm:function(formelm) {
	  var elm = formelm.getElements();
      	for(i = 0; i < elm.length; i++) {
        	if(elm[i].name!= "send" && elm[i].type != "hidden"){
            	elm[i].value = "";
        	}
  		}
  	},
	
	prepareRequest: function(myurl, mymethod, myfunc, myparams){
		var myoptions = {
			method: mymethod,
			parameters: myparams,
			onSuccess: myfunc
		};
		
		var myRequest = new Ajax.Request(myurl, myoptions);
	}
}