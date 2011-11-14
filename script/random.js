function RandomNumbers (seed, low, high) {
	this.seed = seed;
	this.low = low;
	this.high = high+1;
	this.intRand = function() {
		this.seed = (this.seed * 172790621 + 1) % ((1 << 31) - 1);
		newvalue = Math.abs(this.seed)%(this.high-this.low)+this.low;
		return newvalue;
	}
	this.setSeed = function(seed) {
		this.seed = seed;
	}
}