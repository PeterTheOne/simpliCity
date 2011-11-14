var hide = true;

$(function(){
	update();
	
	$("body").load(function(){
		update();
	});
	
	$(window).resize(function(){
		update();
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
			'background-color' : '#6666CC',
			'border-top-color' : '#000033',
			'border-right-color' : '#CCCCFF',
			'border-bottom-color' : '#CCCCFF',
			'border-left-color' : '#000033'
		});
	});
	
	$(".button").bind("touchend",function(){
		$(this).css({
			'background-color' : '#333399',
			'border-top-color' : '#CCCCFF',
			'border-right-color' : '#000033',
			'border-bottom-color' : '#000033',
			'border-left-color' : '#CCCCFF'
		});
	});
	
});


function update(){
	var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
	var height = (window.innerHeight > 0) ? window.innerHeight : screen.height;
	$("#canvas").attr("width", width);
	$("#canvas").attr("height", height);
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
