var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
var height = (window.innerHeight > 0) ? window.innerHeight : screen.height;

var seed = document.getElementById("venueID").innerHTML;
var innerCity = document.getElementById("innerCity").innerHTML;
var industry = document.getElementById("industry").innerHTML;
var urban = document.getElementById("urban").innerHTML;
var rural = document.getElementById("rural").innerHTML;

var canvas = document.getElementById("canvas");
canvas.setAttribute("width", width);
canvas.setAttribute("height", height);
var context = canvas.getContext('2d');

var dx = 20;
var dy = 10;
var offx = 0;
var offy = 0;

var city;

updateCity();

function updateCity(){
	city = buildCity(innerCity, industry, urban, rural, seed, 1);
}

draw(0);

canvas.ontouchmove = move;
canvas.onmousemove = move;
canvas.ontouchstart = down;
canvas.onmousedown = down;
canvas.ontouchend = up;
canvas.onmouseup = up;
document.ontouchend = up;
document.onmouseup = up;

var mx = 0;
var my = 0;
var down = false;

function down(e){
	down = true;
	var p;
	if (e.touches) {
		p = getCoords(e.touches[0]);
	} else {
		p = getCoords(e);
	}
	mx = p.x;
	my = p.y;
	return false;
}

function move(e){
	if(down){
		var p;
		if (e.touches) {
			p = getCoords(e.touches[0]);
		} else {
			p = getCoords(e);
		}
		offx += p.x - mx;
		offy += p.y - my;
		mx = p.x;
		my = p.y;
		
		draw(0);
	}
	return false;
}

function up(e){
	down = false;
	return false;
}

function getCoords(e) {
	if (e.offsetX) {
		// Works in Chrome / Safari (except on iPad/iPhone)
		return { x: e.offsetX, y: e.offsetY };
	}
	else if (e.layerX) {
		// Works in Firefox
		return { x: e.layerX, y: e.layerY };
	}
	else {
		// Works in Safari on iPad/iPhone
		return { x: e.pageX - canvas.offsetLeft, y: e.pageY - canvas.offsetTop };
	}
}

//var cFrame = 1;


function draw(cFrame){
	cFrame++;
	if(imagesReady()){
		context.beginPath();
		context.rect(0, 0, canvas.width, canvas.height);
		context.fillStyle = "#00FF00";
		context.fill();
		var cx = 0;
		var cy = 0;
		var sx = 0;
		var sy = 0;
		var rndInnerCity = new RandomNumbers(getSeed(seed));
		var rndIndustry = new RandomNumbers(getSeed(seed));
		var rndUrban = new RandomNumbers(getSeed(seed));
		var rndRural = new RandomNumbers(getSeed(seed));
		
		for(var i = 0; i <= canvas.width/grass.width+2; i++){
			for(var j = 0; j <= canvas.height/grass.height+2; j++){
				context.drawImage(grass, (-grass.width+i*(grass.width)+(offx%grass.width)),(-grass.height+j*(grass.height)+(offy%grass.height)));
			}
		}
		
		//var framecount = img[1].width/40;
		for(var i = 0; i < city.length; i++){
			for(var j = 0; j < city[i].length; j++){
				//if(city[i][j] != 0 && city[i][j] != null) context.drawImage(img[city[i][j]], 40*((cFrame+Math.pow(i, j))%framecount), 0, 40, img[city[i][j]].height, cx+offx, cy+offy-img[city[i][j]].height, img[city[i][j]].width/framecount, img[city[i][j]].height);
				var currentimg = null;
				if(city[i][j] == 1){
					currentimg = imgInnerCity[rndInnerCity.intRand(0,imgInnerCity.length-1)];
				} else if(city[i][j] == 2){
					currentimg = imgIndustry[rndIndustry.intRand(0,imgIndustry.length-1)];
				} else if(city[i][j] == 3){
					currentimg = imgUrban[rndUrban.intRand(0,imgUrban.length-1)];
				} else if(city[i][j] == 4){
					currentimg = imgRural[rndRural.intRand(0,imgRural.length-1)];
				}
				
				if(currentimg != null){
					context.drawImage(currentimg, cx+offx, cy+offy-currentimg.height, currentimg.width, currentimg.height);
				}
				cx += dx;
				cy += dy;
			}
			sx = sx-dx;
			sy = sy+dy;
			cx = sx;
			cy = sy;
		}
		
		var coffx = (offx*2+cFrame/2)%clouds.width;
		var coffy = (offy*2+cFrame/4)%clouds.height;
		for(var i = 0; i <= canvas.width/clouds.width+2; i++){
			for(var j = 0; j <= canvas.height/clouds.height+2; j++){
				context.drawImage(clouds, (-clouds.width+i*(clouds.width)+coffx),(-clouds.height+j*(clouds.height)+coffy));
			}
		}
		
		coffx = (offx*3+cFrame/4)%cloudsB.width;
		coffy = (offy*3+cFrame/2)%cloudsB.height;
		for(var i = 0; i <= canvas.width/cloudsB.width+2; i++){
			for(var j = 0; j <= canvas.height/cloudsB.height+2; j++){
				context.drawImage(cloudsB, (-cloudsB.width+i*(cloudsB.width)+coffx),(-cloudsB.height+j*(cloudsB.height)+coffy));
			}
		}
	} else {
		setTimeout("draw(0)",40);
	}
	//setTimeout("draw("+cFrame+")",40);
}