/**
 * Interface Elements for jQuery
 * Slideshow
 * 
 * http://interface.eyecon.ro
 * 
 * Copyright (c) 2006 Stefan Petre
 * Dual licensed under the MIT (MIT-LICENSE.txt) 
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 */


/**
 * Creates an image slideshow. The slideshow can autoplay slides, each image can have caption, navigation links: next, prev, each slide. A page may have more then one slideshow, eachone working independently. Each slide can be bookmarked. The source images can be defined by JavaScript in slideshow options or by HTML placing images inside container.
 *
 * 
 * 
 * @name Slideshow
 * @description Creates an image slideshow. The slideshow can autoplay slides, each image can have caption, navigation links: next, prev, each slide. A page may have more then one slideshow, eachone working independently. Each slide can be bookmarked. The source images can be defined by JavaScript in slideshow options or by HTML placing images inside container.
 * @param Hash hash A hash of parameters
 * @option String container container ID
 * @option String loader path to loading indicator image
 * @option String linksPosition (optional) images links position ['top'|'bottom'|null]
 * @option String linksClass (optional) images links cssClass
 * @option String linksSeparator (optional) images links separator
 * @option Integer fadeDuration fade animation duration in miliseconds
 * @option String activeLinkClass (optional) active image link CSS class
 * @option String nextslideClass (optional) next image CSS class
 * @option String prevslideClass (optional) previous image CSS class
 * @option String captionPosition (optional) image caption position ['top'|'bottom'|null]
 * @option String captionClass (optional) image caption CSS class
 * @option String autoplay (optional) seconds to wait untill next images is displayed. This option will make the slideshow to autoplay.
 * @option String random (optional) if slideshow autoplayes the images can be randomized
 * @option Array images (optional) array of hash with keys 'src' (path to image) and 'cation' (image caption) for images
 *
 * @type jQuery
 * @cat Plugins/Interface
 * @author Stefan Petre
 */
jQuery.islideshow = {
	slideshows: [],
	gonext : function()
	{
		this.blur();
		slideshow = this.parentNode;
		id = jQuery.attr(slideshow, 'id');
		if (jQuery.islideshow.slideshows[id] != null) {
			window.clearInterval(jQuery.islideshow.slideshows[id]);
		}
		slide = slideshow.ss.currentslide + 1;
		if (slideshow.ss.images.length < slide) {
			slide = 1;
		}
		images = jQuery('img', slideshow.ss.holder);
		slideshow.ss.currentslide = slide;
		if (images.size() > 0) {
			images.fadeOut(
				slideshow.ss.fadeDuration,
				jQuery.islideshow.showImage
			);
		}
	},
	goprev : function()
	{
		this.blur();
		slideshow = this.parentNode;
		id = jQuery.attr(slideshow, 'id');
		if (jQuery.islideshow.slideshows[id] != null) {
			window.clearInterval(jQuery.islideshow.slideshows[id]);
		}
		slide = slideshow.ss.currentslide - 1;
		images = jQuery('img', slideshow.ss.holder);
		if (slide < 1) {
			slide = slideshow.ss.images.length ;
		}
		slideshow.ss.currentslide = slide;
		if (images.size() > 0) {
			images.fadeOut(
				slideshow.ss.fadeDuration,
				jQuery.islideshow.showImage
			);
		}
	},
	timer : function (c)
	{
		slideshow = document.getElementById(c);
		if (slideshow.ss.random) {
			slide = slideshow.ss.currentslide;
			while(slide == slideshow.ss.currentslide) {
				slide = 1 + parseInt(Math.random() * slideshow.ss.images.length);
			}
		} else {
			slide = slideshow.ss.currentslide + 1;
			if (slideshow.ss.images.length < slide) {
				slide = 1;
			}
		}
		images = jQuery('img', slideshow.ss.holder);
		slideshow.ss.currentslide = slide;
		if (images.size() > 0) {
			images.fadeOut(
				slideshow.ss.fadeDuration,
				jQuery.islideshow.showImage
			);
		}
	},
	go : function(o)
	{
		var slideshow;
		if (o && o.constructor == Object) {
			if (o.loader) {
				slideshow = document.getElementById(o.loader.slideshow);
				url = window.location.href.split("#");
				o.loader.onload = null;
				if (url.length == 2) {
					slide = parseInt(url[1]);
					show = url[1].replace(slide,'');
					if (jQuery.attr(slideshow,'id') != show) {
						slide = 1;
					}
				} else {
					slide = 1;
				}
			}
			if(o.link) {
				o.link.blur();
				slideshow = o.link.parentNode.parentNode;
				id = jQuery.attr(slideshow, 'id');
				if (jQuery.islideshow.slideshows[id] != null) {
					window.clearInterval(jQuery.islideshow.slideshows[id]);
				}
				url = o.link.href.split("#");
				slide = parseInt(url[1]);
				show = url[1].replace(slide,'');
				if (jQuery.attr(slideshow,'id') != show) {
					slide = 1;
				}
			}
			if (slideshow.ss.images.length < slide || slide < 1) {
				slide = 1;
			}
			slideshow.ss.currentslide = slide;
			slidePos = jQuery.iUtil.getSize(slideshow);
			slidePad = jQuery.iUtil.getPadding(slideshow);
			slideBor = jQuery.iUtil.getBorder(slideshow);
			if (slideshow.ss.prevslide) {
				slideshow.ss.prevslide.o.css('display', 'none');
			}
			if (slideshow.ss.nextslide) {
				slideshow.ss.nextslide.o.css('display', 'none');
			}
			
			//center loader
			if (slideshow.ss.loader) {
				y = parseInt(slidePad.t) + parseInt(slideBor.t);
				if (slideshow.ss.slideslinks) {
					if (slideshow.ss.slideslinks.linksPosition == 'top') {
						y += slideshow.ss.slideslinks.dimm.hb;
					} else {
						slidePos.h -= slideshow.ss.slideslinks.dimm.hb;
					}
				}
				if (slideshow.ss.slideCaption) {
					if (slideshow.ss.slideCaption && slideshow.ss.slideCaption.captionPosition == 'top') {
						y += slideshow.ss.slideCaption.dimm.hb;
					} else {
						slidePos.h -= slideshow.ss.slideCaption.dimm.hb;
					}
				}
				if (!slideshow.ss.loaderWidth) {
					slideshow.ss.loaderHeight = o.loader ? o.loader.height : (parseInt(slideshow.ss.loader.css('height'))||0);
					slideshow.ss.loaderWidth = o.loader ? o.loader.width : (parseInt(slideshow.ss.loader.css('width'))||0);
				}
				
				slideshow.ss.loader.css('top', y + (slidePos.h - slideshow.ss.loaderHeight)/2 + 'px');
				slideshow.ss.loader.css('left', (slidePos.wb - slideshow.ss.loaderWidth)/2 + 'px');
				slideshow.ss.loader.css('display', 'block');
			}
			
			//getimage
			images = jQuery('img', slideshow.ss.holder);
			if (images.size() > 0) {
				images.fadeOut(
					slideshow.ss.fadeDuration,
					jQuery.islideshow.showImage
				);
			} else {
				lnk = jQuery('a', slideshow.ss.slideslinks.o).get(slide-1);
				jQuery(lnk).addClass(slideshow.ss.slideslinks.activeLinkClass);
				var img = new Image();
				img.slideshow = jQuery.attr(slideshow,'id');
				img.slide = slide-1;
				img.src = slideshow.ss.images[slideshow.ss.currentslide-1].src ;
				if (img.complete) {
					img.onload = null;
					jQuery.islideshow.display.apply(img);
				} else {
					img.onload = jQuery.islideshow.display;
				}
				//slideshow.ss.holder.html('<img src="' + slideshow.ss.images[slide-1].src + '" />');
				if (slideshow.ss.slideCaption) {
					slideshow.ss.slideCaption.o.html(slideshow.ss.images[slide-1].caption);
				}
				//jQuery('img', slideshow.ss.holder).bind('load',jQuery.slideshowDisplay);
			}
		}
	},
	showImage : function()
	{
		slideshow = this.parentNode.parentNode;
		slideshow.ss.holder.css('display','none');
		if (slideshow.ss.slideslinks.activeLinkClass) {
			lnk = jQuery('a', slideshow.ss.slideslinks.o).removeClass(slideshow.ss.slideslinks.activeLinkClass).get(slideshow.ss.currentslide - 1);
			jQuery(lnk).addClass(slideshow.ss.slideslinks.activeLinkClass);
		}
		//slideshow.ss.holder.html('<img src="' + slideshow.ss.images[slideshow.ss.currentslide - 1].src + '" />');
		
		var img = new Image();
		img.slideshow = jQuery.attr(slideshow,'id');
		img.slide = slideshow.ss.currentslide - 1;
		img.src = slideshow.ss.images[slideshow.ss.currentslide - 1].src ;
		if (img.complete) {
			img.onload = null;
			jQuery.islideshow.display.apply(img);
		} else {
			img.onload = jQuery.islideshow.display;
		}
		if (slideshow.ss.slideCaption) {
			slideshow.ss.slideCaption.o.html(slideshow.ss.images[slideshow.ss.currentslide-1].caption);
		}
		//jQuery('img', slideshow.ss.holder).bind('load',jQuery.slideshowDisplay);
	},
	display : function ()
	{
		slideshow = document.getElementById(this.slideshow);
		if (slideshow.ss.prevslide) {
			slideshow.ss.prevslide.o.css('display', 'none');
		}
		if (slideshow.ss.nextslide) {
			slideshow.ss.nextslide.o.css('display', 'none');
		}
		slidePos = jQuery.iUtil.getSize(slideshow);
		y = 0;
		if (slideshow.ss.slideslinks) {
			if (slideshow.ss.slideslinks.linksPosition == 'top') {
				y += slideshow.ss.slideslinks.dimm.hb;
			} else {
				slidePos.h -= slideshow.ss.slideslinks.dimm.hb;
			}
		}
		if (slideshow.ss.slideCaption) {
			if (slideshow.ss.slideCaption && slideshow.ss.slideCaption.captionPosition == 'top') {
				y += slideshow.ss.slideCaption.dimm.hb;
			} else {
				slidePos.h -= slideshow.ss.slideCaption.dimm.hb;
			}
		}
		par = jQuery('.slideshowHolder', slideshow);
		y = y + (slidePos.h - this.height)/2 ;
		x = (slidePos.wb - this.width)/2;
		slideshow.ss.holder.css('top', y + 'px').css('left', x + 'px').html('<img src="' + this.src + '" />');
		slideshow.ss.holder.fadeIn(slideshow.ss.fadeDuration);
		nextslide = slideshow.ss.currentslide + 1;
		if (nextslide > slideshow.ss.images.length) {
			nextslide = 1;
		}
		prevslide = slideshow.ss.currentslide - 1;
		if (prevslide < 1) {
			prevslide = slideshow.ss.images.length;
		}
		slideshow.ss.nextslide.o
				.css('display','block')
				.css('top', y + 'px')
				.css('left', x + 2 * this.width/3 + 'px')
				.css('width', this.width/3 + 'px')
				.css('height', this.height + 'px')
				.attr('title', slideshow.ss.images[nextslide-1].caption);
		slideshow.ss.nextslide.o.get(0).href = '#' + nextslide + jQuery.attr(slideshow, 'id');
		slideshow.ss.prevslide.o
				.css('display','block')
				.css('top', y + 'px')
				.css('left', x + 'px')
				.css('width', this.width/3 + 'px')
				.css('height', this.height + 'px')
				.attr('title', slideshow.ss.images[prevslide-1].caption);
		slideshow.ss.prevslide.o.get(0).href = '#' + prevslide + jQuery.attr(slideshow, 'id');
	},
	build : function(o)
	{
		if (!o || !o.container || jQuery.islideshow.slideshows[o.container])
			return;
		var container = jQuery('#' + o.container);
		var el = container.get(0);
		
		if (el.style.position != 'absolute' && el.style.position != 'relative') {
			el.style.position = 'relative';
		}
		el.style.overflow = 'hidden';
		if (container.size() == 0)
			return;
		el.ss = {};
		
		el.ss.images = o.images ? o.images : [];
		el.ss.random = o.random && o.random == true || false;
		imgs = el.getElementsByTagName('IMG');
		for(i = 0; i< imgs.length; i++) {
			indic = el.ss.images.length;
			el.ss.images[indic] = {src:imgs[i].src, caption:imgs[i].title||imgs[i].alt||''};
		}
		
		if (el.ss.images.length == 0) {
			return;
		}
		
		el.ss.oP = jQuery.extend(
				jQuery.iUtil.getPosition(el),
				jQuery.iUtil.getSize(el)
			);
		el.ss.oPad = jQuery.iUtil.getPadding(el);
		el.ss.oBor = jQuery.iUtil.getBorder(el);
		t = parseInt(el.ss.oPad.t) + parseInt(el.ss.oBor.t);
		b = parseInt(el.ss.oPad.b) + parseInt(el.ss.oBor.b);
		jQuery('img', el).remove();
		el.ss.fadeDuration = o.fadeDuration ? o.fadeDuration : 500;
		if (o.linksPosition || o.linksClass || o.activeLinkClass) {
			el.ss.slideslinks = {};
			container.append('<div class="slideshowLinks"></div>');
			el.ss.slideslinks.o = jQuery('.slideshowLinks', el);
			if (o.linksClass) {
				el.ss.slideslinks.linksClass = o.linksClass;
				el.ss.slideslinks.o.addClass(o.linksClass);
			}
			if (o.activeLinkClass) {
				el.ss.slideslinks.activeLinkClass = o.activeLinkClass;
			}
			el.ss.slideslinks.o.css('position','absolute').css('width', el.ss.oP.w + 'px');
			if (o.linksPosition && o.linksPosition == 'top') {
				el.ss.slideslinks.linksPosition = 'top';
				el.ss.slideslinks.o.css('top',t + 'px');
			} else {
				el.ss.slideslinks.linksPosition = 'bottom';
				el.ss.slideslinks.o.css('bottom',b + 'px');
			}
			el.ss.slideslinks.linksSeparator = o.linksSeparator ? o.linksSeparator : ' ';
			for (var i=0; i<el.ss.images.length; i++) {
				indic = parseInt(i) + 1;
				el.ss.slideslinks.o.append('<a href="#' + indic + o.container + '" class="slideshowLink" title="' + el.ss.images[i].caption + '">' + indic + '</a>' + (indic != el.ss.images.length ? el.ss.slideslinks.linksSeparator : ''));
			}
			jQuery('a', el.ss.slideslinks.o).bind(
				'click',
				function()
				{
					jQuery.islideshow.go({link:this})
				}
			);
			el.ss.slideslinks.dimm = jQuery.iUtil.getSize(el.ss.slideslinks.o.get(0));
		}
		if (o.captionPosition || o.captionClass) {
			el.ss.slideCaption = {};
			container.append('<div class="slideshowCaption">&nbsp;</div>');
			el.ss.slideCaption.o = jQuery('.slideshowCaption', el);
			if (o.captionClass) {
				el.ss.slideCaption.captionClass = o.captionClass;
				el.ss.slideCaption.o.addClass(o.captionClass);
			}
			el.ss.slideCaption.o.css('position','absolute').css('width', el.ss.oP.w + 'px');
			if (o.captionPosition&& o.captionPosition == 'top') {
				el.ss.slideCaption.captionPosition = 'top';
				el.ss.slideCaption.o.css('top', (el.ss.slideslinks && el.ss.slideslinks.linksPosition == 'top' ? el.ss.slideslinks.dimm.hb + t : t) + 'px');
			} else {
				el.ss.slideCaption.captionPosition = 'bottom';
				el.ss.slideCaption.o.css('bottom', (el.ss.slideslinks && el.ss.slideslinks.linksPosition == 'bottom' ? el.ss.slideslinks.dimm.hb + b : b) + 'px');
			}
			el.ss.slideCaption.dimm = jQuery.iUtil.getSize(el.ss.slideCaption.o.get(0));
		}
		
		if (o.nextslideClass) {
			el.ss.nextslide = {nextslideClass:o.nextslideClass};
			container.append('<a href="#2' + o.container + '" class="slideshowNextSlide">&nbsp;</a>');
			el.ss.nextslide.o = jQuery('.slideshowNextSlide', el);
			el.ss.nextslide.o.css('position', 'absolute').css('display', 'none').css('overflow','hidden').css('fontSize', '30px').addClass(el.ss.nextslide.nextslideClass);
			el.ss.nextslide.o.bind('click', jQuery.islideshow.gonext);
		}
		if (o.prevslideClass) {
			el.ss.prevslide= {prevslideClass:o.prevslideClass};
			container.append('<a href="#0' + o.container + '" class="slideshowPrevslide">&nbsp;</a>');
			el.ss.prevslide.o = jQuery('.slideshowPrevslide', el);
			el.ss.prevslide.o.css('position', 'absolute').css('display', 'none').css('overflow','hidden').css('fontSize', '30px').addClass(el.ss.prevslide.prevslideClass);
			el.ss.prevslide.o.bind('click', jQuery.islideshow.goprev);
		}
		
		container.prepend('<div class="slideshowHolder"></div>');
		el.ss.holder = jQuery('.slideshowHolder', el);
		el.ss.holder.css('position','absolute').css('top','0px').css('left','0px').css('display', 'none');
		if (o.loader) {
			container.prepend('<div class="slideshowLoader" style="display: none;"><img src="' + o.loader + '" /></div>');
			el.ss.loader = jQuery('.slideshowLoader', el);
			el.ss.loader.css('position', 'absolute');
			var img = new Image();
			img.slideshow = o.container;
			img.src = o.loader;
			if (img.complete) {
				img.onload = null;
				jQuery.islideshow.go({loader:img});
			} else {
				img.onload = function()
				{
					jQuery.islideshow.go({loader:this});
				};
			}
		} else {
			jQuery.islideshow.go({container:el});
		}
		
		if(o.autoplay) {
			time = parseInt(o.autoplay) * 1000;
		}
		jQuery.islideshow.slideshows[o.container] = o.autoplay ? window.setInterval('jQuery.islideshow.timer(\'' + o.container + '\')', time) : null;
	}
};
jQuery.slideshow = jQuery.islideshow.build;