// JavaScript Document

var Gallery = {
	
	index:0,
	
	init:function(){
		var img = $$('#textarea .gallery');
		img.invoke('observe', 'click', Gallery.fetchGallery);
	},
	
	fetchGallery:function(ev){
		//hier noch th_ entfernen
		var target = ev.element().src;
		var length = target.length;
		
		var urlend = target.indexOf("thumbs");
		var url = target.substring(0, urlend) + "images/";
		
		var begin = target.indexOf("th_") + 3;
		var bigimg = target.substring(begin, length);
		
		var gallery = '<div id="overlay" style=""><div id="gallery" ><img id="bigimg" src="" /></div></div>';

		ev.element().up().up().insert({before:gallery});
		$('bigimg').src= url + bigimg;
		
		$('gallery').insert({top:'<a href="javascript:"><span id="close">Schlie&szlig;en</span></a>'});
		$('close').up().observe('click', Gallery.closeGallery);
		
		if(Images){
			if(target.indexOf(Images.pre)){
				var idstrip = bigimg.substring(Images.pre.length, bigimg.length);
				var id = idstrip.substring(0, idstrip.length - 4);
				Gallery.index = id;
				
				$('close').up().insert({after: '<a href="javascript:"><span id="prev"> << vorheriges </span></a><a href="javascript:"> <span id="next"> n&auml;chstes >> </span></a>'});
				
				$('prev').up().observe('click', Gallery.nextImg);
				$('next').up().observe('click', Gallery.nextImg);
			}
		}
	},
	
	nextImg:function(ev){
		
		if(ev.element().id == "next"){
			Gallery.index++;
			if(Gallery.index > Images.end){
				Gallery.index = Images.begin;
			}
		}else{
			Gallery.index--;
			if(Gallery.index < Images.begin){
				Gallery.index = Images.end;	
			}
		}
		
		var bigimg = Images.pre + Gallery.index + ".jpg";
		
		var urlend = $('bigimg').src.indexOf("images");
		var url = $('bigimg').src.substring(0, urlend) + "images/gallery/images/";
		$('bigimg').src= url + bigimg;
	},
	
	closeGallery:function(ev){
		Element.remove($('overlay'));
	}
}