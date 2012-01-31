var hide = true;
var sy = 0;
var scroll = false;
var cy = 0;


$(function(){
	update();
	
	$("body").load(function(){
		update();
	});
	
	$(window).resize(function(){
		update();
	});
	
	$("#canvas").bind("touchend",function(){
		$("#canvas").click();
	});
	
	$("#canvas").click(function(){
		$("#overlay").hide();
	});
	
	/*$(window).bind("touchstart",function(){
		hide = false;
	});
	
	$(window).bind("touchend",function(){
		hide = true;
		setTimeout("update()",1000);
	});*/
	
	$(".button").bind("touchstart",function(){
		$(this).css({
			'background' : '-webkit-gradient(linear, left top, left bottom, from(rgba(102,102,204,0.8)), to(rgba(51,51,153,0.6)))',
			'background' : '-moz-linear-gradient(top,  rgba(102,102,204,0.8),  rgba(51,51,153,0.6))',
			'background' : '-o-linear-gradient(top , rgba(102,102,204,0.8), rgba(51,51,153,0.6))', 
			'border-top-color' : '#000033',
			'border-right-color' : '#CCCCFF',
			'border-bottom-color' : '#CCCCFF',
			'border-left-color' : '#000033'
		});
	});
	
	$(".button").bind("touchend",function(){
		$(this).css({
			'background' : '-webkit-gradient(linear, left top, left bottom, from(rgba(51,51,153,0.8)), to(rgba(102,102,204,,0.6)))',
			'background' : '-moz-linear-gradient(top,  rgba(51,51,153,0.8),  rgba(102,102,204,,0.6))',
			'background' : '-o-linear-gradient(top , rgba(51,51,153,0.8), rgba(102,102,204,,0.6))',
			'border-top-color' : '#CCCCFF',
			'border-right-color' : '#000033',
			'border-bottom-color' : '#000033',
			'border-left-color' : '#CCCCFF'
		});
	});
	
	$(".scrolling, .scrolling *").addEventListener("touchstart",function(event){
		sy = event.touches[0].pageY;
		cy = $(".scrolling").scrollTop();
		scroll = true;
	}, false);
	
	$(".scrolling, .scrolling *").addEventListener("touchmove",function(event){
		if(scroll){
			var dy = sy-event.touches[0].pageY;
			$(".scrolling").scrollTop(cy + dy);
		}
	});

	$("*").addEventListener("touchend",function(event){
		scroll = false;
	});
	
	$(".scrolling, .scrolling *").bind("mousedown",function(event){
		sy = event.pageY;
		cy = $(".scrolling").scrollTop();
		scroll = true;
	});
	
	$(".scrolling, .scrolling *").bind("mousemove",function(event){
		if(scroll){
			var dy = sy-event.pageY;
			$(".scrolling").scrollTop(cy + dy);
		}
	});
	
	$("*").bind("mouseup",function(event){
		scroll = false;
	});
	
});


function update(){
	var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
	var height = (window.innerHeight > 0) ? window.innerHeight : screen.height;
	$("#canvas").attr("width", width);
	$("#canvas").attr("height", height);
	$("#overlay").css("width", width - 60);
	$("#overlay").css("height", height - 120);
	$("#overlay").css("top", 60);
	$("#overlay").css("left", 30);
	
	$("#statistics").css("height", height);
	//$("#overlay").show();
	
	/*var difference = Math.abs(width-height);
	var third = parseInt((difference-6)/3);
	var remainder = difference%3;
	if(width > height){ //landscape
		$("#landscape").css("height",height + "px");
		$("#landscape").css("width",$("#landscape").css("height"));
		$("#buttons .button").css("width",third + "px");
		$("#buttons .button").css("height","50px");
		$("#buttons #centerBtn").css("width", (third+remainder) + "px");
		$("#menu").css("height", height + "px");
	} else { //portrait
		$("#landscape").css("width",width + "px");
		$("#landscape").css("height",$("#landscape").css("width"));
		$("#buttons .button").css("height",third + "px");
		$("#buttons .button").css("width","50px");
		$("#buttons #centerBtn").css("height", (third+remainder) + "px");
		$("#menu").css("height", difference + "px");
		
	}
	$("#info").html("Window dimensions: " + width + ", " + height + "<br/>Landscape dimensions: " + $("#landscape").css("width") + ", " + $("#landscape").css("height"));
	*/
	if(hide){
		window.scrollTo(0, 1);
	}
}


