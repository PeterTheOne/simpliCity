var imgInnerCity = new Array();
imgInnerCity[0] = new Image();
imgInnerCity[0].src = "images/city/hochaus5.png";
imgInnerCity[1] = new Image();
imgInnerCity[1].src = "images/city/hochhaus.png";
imgInnerCity[2] = new Image();
imgInnerCity[2].src = "images/city/hochhaus2.png";
imgInnerCity[3] = new Image();
imgInnerCity[3].src = "images/city/hochhauss.png";
imgInnerCity[4] = new Image();
imgInnerCity[4].src = "images/city/hochhauss2.png";
imgInnerCity[5] = new Image();
imgInnerCity[5].src = "images/city/hochhauss3.png";

var imgIndustry = new Array();
imgIndustry[0] = new Image();
imgIndustry[0].src = "images/city/bueros.png";
imgIndustry[1] = new Image();
imgIndustry[1].src = "images/city/fabrik.png";
imgIndustry[2] = new Image();
imgIndustry[2].src = "images/city/mc.png";
imgIndustry[3] = new Image();
imgIndustry[3].src = "images/city/wohnblock.png";
imgIndustry[4] = new Image();
imgIndustry[4].src = "images/city/turm.png";

var imgUrban = new Array();
imgUrban[0] = new Image();
imgUrban[0].src = "images/city/church.png";
imgUrban[1] = new Image();
imgUrban[1].src = "images/city/krankenhaus.png";
imgUrban[2] = new Image();
imgUrban[2].src = "images/city/littlepark.png";
imgUrban[3] = new Image();
imgUrban[3].src = "images/city/littleshop.png";
imgUrban[4] = new Image();
imgUrban[4].src = "images/city/mehrfamilienhaus.png";
imgUrban[5] = new Image();
imgUrban[5].src = "images/city/mehrfamilienhaus2.png";

var imgRural = new Array();
imgRural[0] = new Image();
imgRural[0].src = "images/city/bauernhof.png";
imgRural[1] = new Image();
imgRural[1].src = "images/city/einfamilienhaus.png";
imgRural[2] = new Image();
imgRural[2].src = "images/city/einfamilienhaus2.png";
imgRural[3] = new Image();
imgRural[3].src = "images/city/friedhof.png";
imgRural[4] = new Image();
imgRural[4].src = "images/city/waeldchen.png";

var clouds = new Image();
clouds.src = "images/landscape/clouds.png";

var cloudsB = new Image();
cloudsB.src = "images/landscape/clouds2.png";

var grass = new Image();
grass.src = "images/landscape/grass.png";

function imagesReady(){
	for(var i = 0; i < imgInnerCity.length; i++){
		if(!imgInnerCity[i].complete) return false;
	}
	for(var i = 0; i < imgIndustry.length; i++){
		if(!imgIndustry[i].complete) return false;
	}
	for(var i = 0; i < imgUrban.length; i++){
		if(!imgUrban[i].complete) return false;
	}
	for(var i = 0; i < imgRural.length; i++){
		if(!imgRural[i].complete) return false;
	}
	
	if(!clouds.complete) return false;
	if(!cloudsB.complete) return false;
	if(!grass.complete) return false;
	
	return true;
}