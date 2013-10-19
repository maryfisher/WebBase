// JavaScript Document
var Rollover = {
	
	r_over: "_r.",
	r_out: ".",
	imgformat: "jpg",
	
	init: function(invoke){
		var mainmenus = $$(invoke);
		mainmenus.invoke('observe', 'mouseover', Rollover.rollover);
		mainmenus.invoke('observe', 'mouseout', Rollover.rollover);
	},
	
	rollover:function(ev){
		
		var target = ev.element().src;
		var ro = target.indexOf("_r");
		
		//var end = (ro == -1) ? Rollover.r_over : Rollover.r_out;
		var end = (ev.type == "mouseover") ? Rollover.r_over : Rollover.r_out;
		var endlength = (ro != -1) ? Rollover.r_over.length : Rollover.r_out.length;
		
		var format = target.substring(target.length - 3, target.length);
		
		var length = target.length - endlength - format.length;
		var bigimg = target.substring(0, length);
		
		ev.element().src = bigimg + end + format;
	}
}