/**
 * Interface Elements for jQuery
 * Easing formulas
 *
 * http://interface.eyecon.ro
 *
 * Copyright (c) 2006 Stefan Petre
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 */
 
/**
 * Starting with jQuery 1.1  the fx function accepts easing formulas that can be used with .animation() and most of FX plugins from Interface. The object can be extended to accept new easing formulas
 */
 
 jQuery.extend({	
	/**
	 *
	 * @param Integer p period step in animation
	 * @param Integer n current time
	 * @param Mixed firstNum begin value
	 * @param Mixed delta change in
	 * @param Integer duration duration
	 */
	easing :  {
		linear: function(p, n, firstNum, delta, duration) {
			return ((-Math.cos(p*Math.PI)/2) + 0.5) * delta + firstNum;
		},
		
		easein: function(p, n, firstNum, delta, duration) {
			return delta*(n/=duration)*n*n + firstNum;
		},
		
		easeout: function(p, n, firstNum, delta, duration) {
			return -delta * ((n=n/duration-1)*n*n*n - 1) + firstNum;
		},
		
		easeboth: function(p, n, firstNum, delta, duration) {
			if ((n/=duration/2) < 1)
				return delta/2*n*n*n*n + firstNum;
				return -delta/2 * ((n-=2)*n*n*n - 2) + firstNum;
		},
		
		bounceout: function(p, n, firstNum, delta, duration) {
			if ((n/=duration) < (1/2.75)) {
				return delta*(7.5625*n*n) + firstNum;
			} else if (n < (2/2.75)) {
				return delta*(7.5625*(n-=(1.5/2.75))*n + .75) + firstNum;
			} else if (n < (2.5/2.75)) {
				return delta*(7.5625*(n-=(2.25/2.75))*n + .9375) + firstNum;
			} else {
				return delta*(7.5625*(n-=(2.625/2.75))*n + .984375) + firstNum;
			}
		},
		
		bouncein: function(p, n, firstNum, delta, duration) {
			if (jQuery.easing.bounceout)
				return delta - jQuery.easing.bounceout (p, duration - n, 0, delta, duration) + firstNum;
			return firstNum + delta;
		},
		
		bounceboth: function(p, n, firstNum, delta, duration) {
			if (jQuery.easing.bouncein && jQuery.easing.bounceout)
				if (n < duration/2)
					return jQuery.easing.bouncein(p, n*2, 0, delta, duration) * .5 + firstNum;
				return jQuery.easing.bounceout(p, n*2-duration, 0, delta, duration) * .5 + delta*.5 + firstNum; 
			return firstNum + delta;
		},
		
		elasticin: function(p, n, firstNum, delta, duration) {
			var a, s;
   			if (n == 0)
   				return firstNum;
   			if ((n/=duration)==1)
   				return firstNum+delta;
   			a = delta * 0.3;
   			p=duration*.3;
			if (a < Math.abs(delta)) {
				a=delta;
				s=p/4;
			} else { 
				s = p/(2*Math.PI) * Math.asin (delta/a);
			}
			return -(a*Math.pow(2,10*(n-=1)) * Math.sin( (n*duration-s)*(2*Math.PI)/p )) + firstNum; 
		},
		
		elasticout:function(p, n, firstNum, delta, duration) {
			var a, s;
			if (n==0)
				return firstNum;
			if ((n/=duration/2)==2)
				return firstNum + delta;
   			a = delta * 0.3;
   			p=duration*.3;
			if (a < Math.abs(delta)){
				a = delta;
				s=p/4;
			} else { 
				s = p/(2*Math.PI) * Math.asin (delta/a);
			}
			return a*Math.pow(2,-10*n) * Math.sin( (n*duration-s)*(2*Math.PI)/p ) + delta + firstNum;
		},
		
		elasticboth: function(p, n, firstNum, delta, duration) {
			var a, s;
			if (n==0)
				return firstNum;
			if ((n/=duration/2)==2)
				return firstNum + delta;
   			a = delta * 0.3;
   			p=duration*.3;
			if (a < Math.abs(delta)){
				a = delta;
				s=p/4;
			} else { 
				s = p/(2*Math.PI) * Math.asin (delta/a);
			}
			if (n < 1) {
				return -.5*(a*Math.pow(2,10*(n-=1)) * Math.sin( (n*duration-s)*(2*Math.PI)/p )) + firstNum;
			}
			return a*Math.pow(2,-10*(n-=1)) * Math.sin( (n*duration-s)*(2*Math.PI)/p )*.5 + delta + firstNum; 
		}
	}
});