function RandomNumbers (seed) {
	this.seed = seed;
	this.intRand = function(low, high) {
		if(low > high){
			var temp = high;
			high = low;
			low = temp;
		}
		this.seed = (this.seed * 172790621 + 1) % ((1 << 31) - 1);
		newvalue = Math.abs(this.seed)%(high+1-low)+low;
		return newvalue;
	}
	this.setSeed = function(seed) {
		this.seed = seed;
	}
}

function City () {
	this.lowX = 0;
	this.highX = 0;
	this.lowY = 0;
	this.highY = 0;
	this.count = 0;
	this.city = new Array();
	this.lowCity = new Array();
	this.addBuilding = function(number, x, y) {
		if(this.count == 0){
			this.count++;
			this.lowX = x;
			this.highX = x;
			this.lowY = y;
			this.highY = y;
		} else {
			if(x < this.lowX) this.lowX = x;
			if(x > this.highX) this.highX = x;
			if(y < this.lowY) this.lowY = y;
			if(y > this.highY) this.highY = y;
		}
		if(this.city[x] instanceof Array){
			if(this.city[x][y] != null){
				return false;
			} else {
				this.city[x][y] = number;
				this.count++;
			}
		} else {
			this.city[x] = new Array();
			this.city[x][y] = number;
			this.count++;
		}
		return true;
	}
	this.toString = function(){
		var mystring = "";
		for(var i = this.lowX; i <= this.highX; i++){
			for(var j = this.lowY; j <= this.highY; j++){
				if(this.city[i] instanceof Array){
					if(this.city[i][j] != null){
						mystring += this.city[i][j]+"&nbsp;";
					} else {
						mystring += "&nbsp;&nbsp;";
					}
				} else {
					mystring += "&nbsp;&nbsp;";
				}
			}
			mystring += "<br/>";
		}
		return mystring;
	}
	this.calcLower = function(seed, size){
		this.lowCity = new Array();
		var rnd = new RandomNumbers(seed);
		var x = 0;
		var y = 0;
		for(var i = this.lowX; i <= this.highX; i += size){
			this.lowCity[x] = new Array();
			for(var j = this.lowY; j <= this.highY; j += size){
				var selector = new Array();
				for(var u = 0; u < size; u++){
					for(var v = 0; v < size; v++){
						if(this.city[i+u] instanceof Array){
							if(this.city[i+u][j+v] != null){
								selector[u*size+v] = this.city[i+u][j+v];
							} else {
								selector[u*size+v] = 0;
							}
						} else {
							selector[u*size+v] = 0;
						}
					}
				}
				
				this.lowCity[x][y] = selector[rnd.intRand(0,selector.length-1)];
				y++;
			}
			y = 0;
			x++;
		}
	}
	this.lowerToString = function(){
		var mystring = "";
		for(var i = 0; i < this.lowCity.length; i++){
			if(this.lowCity[i] instanceof Array){
				for(var j = 0; j < this.lowCity[i].length; j++){
					if(this.lowCity[i][j] != 0 && this.lowCity[i][j] != null){
						mystring += this.lowCity[i][j]+"";
					} else {
						mystring += "&nbsp;";
					}
				}
			} else {
				mystring = "Lower resolution City not calculated yet!";
				break;
			}
			mystring += "<br/>";
		}
		return mystring;
	}
}

function getSeed (code) {
	var myseed = 0;
	for(var i = 0; i < code.length; i++){
		myseed += parseInt(code.charAt(i),36);
	}
	return myseed;
}

function buildCity(innerCity, industry, urban, rural, seedcode, lowCitySize){
	var myseed = getSeed(seedcode);
	var rnd = new RandomNumbers(myseed);
	var innerRnd = new RandomNumbers(rnd.intRand(0,99));
	var indusRnd = new RandomNumbers(rnd.intRand(0,99));
	var urbanRnd = new RandomNumbers(rnd.intRand(0,99));
	var ruralRnd = new RandomNumbers(rnd.intRand(0,99));
	var city = new City();
	var indusXs = rnd.intRand(-6,6);
	var indusYs = rnd.intRand(-6,6);
	var indusX = indusXs;
	var indusY = indusYs;
	var cityX = 0;
	var cityY = 0;
	for(var i = 0; i < innerCity || i < industry; i++){
		if(i < innerCity){
			do{
				if(innerRnd.intRand(0, Math.abs(cityX)) > 10){
					if(cityX > 0){
						cityX += innerRnd.intRand(-1,0);
					} else {
						cityX += innerRnd.intRand(0,1);
					}
				} else {
					cityX += innerRnd.intRand(-1,1);
				}
				if(innerRnd.intRand(0, Math.abs(cityY)) > 10){
					if(cityY > 0){
						cityY += innerRnd.intRand(-1,0);
					} else {
						cityY += innerRnd.intRand(0,1);
					}
				} else {
					cityY += innerRnd.intRand(-1,1);
				}
			}while(!city.addBuilding(4, cityX, cityY));
		}
		if(i < industry){
			do{
				if(indusRnd.intRand(0, Math.abs(indusX-indusXs)) > 10){
					if(indusX-indusXs > 0){
						indusX += indusRnd.intRand(-1,0);
					} else {
						indusX += indusRnd.intRand(0,1);
					}
				} else {
					indusX += indusRnd.intRand(-1,1);
				}
				if(indusRnd.intRand(0, Math.abs(indusY-indusYs)) > 10){
					if(indusY-indusYs > 0){
						indusY += indusRnd.intRand(-1,0);
					} else {
						indusY += indusRnd.intRand(0,1);
					}
				} else {
					indusY += indusRnd.intRand(-1,1);
				}
			}while(!city.addBuilding(3, indusX, indusY));
		}
	}
	cityX = 0;
	cityY = 0;
	for(var i = 0; i < urban; i++){
		do{
			if(urbanRnd.intRand(0, Math.abs(cityX)) > 25){
				if(cityX > 0){
					cityX += urbanRnd.intRand(-2,0);
				} else {
					cityX += urbanRnd.intRand(0,2);
				}
			} else {
				cityX += urbanRnd.intRand(-2,2);
			}
			if(urbanRnd.intRand(0, Math.abs(cityY)) > 25){
				if(cityY > 0){
					cityY += urbanRnd.intRand(-2,0);
				} else {
					cityY += urbanRnd.intRand(0,2);
				}
			} else {
				cityY += urbanRnd.intRand(-2,2);
			}
		}while(!city.addBuilding(2, cityX, cityY));
		
	}
	cityX = 0;
	cityY = 0;
	for(var i = 0; i < rural; i++){
		do{
			if(ruralRnd.intRand(0, Math.abs(cityX)) > 30){
				if(cityX > 0){
					cityX += ruralRnd.intRand(-8,0);
				} else {
					cityX += ruralRnd.intRand(0,8);
				}
			} else {
				cityX += ruralRnd.intRand(-8,8);
			}
			if(ruralRnd.intRand(0, Math.abs(cityY)) > 30){
				if(cityY > 0){
					cityY += ruralRnd.intRand(-8,0);
				} else {
					cityY += ruralRnd.intRand(0,8);
				}
			} else {
				cityY += ruralRnd.intRand(-8,8);
			}
		}while(!city.addBuilding(1, cityX, cityY));
		
	}
	city.calcLower(myseed, lowCitySize);
	return city.lowCity;
}