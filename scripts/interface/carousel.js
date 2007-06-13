/**
 * Interface Elements for jQuery
 * 3D Carousel
 * 
 * http://interface.eyecon.ro
 * 
 * Copyright (c) 2006 Stefan Petre
 * Dual licensed under the MIT (MIT-LICENSE.txt) 
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 */
/**
 * Created a 3D Carousel from a list of images, with reflections and animated by mouse position
 * 
 * @example window.onload = 
 *			function()
 *			{
 *				$('#carousel').Carousel(
 *					{
 *						itemWidth: 110,
 *						itemHeight: 62,
 *						itemMinWidth: 50,
 *						items: 'a',
 *						reflections: .5,
 *						rotationSpeed: 1.8
 *					}
 *				);
 *			}
 * HTML
 *			<div id="carousel">
 *				<a href="" title=""><img src="" width="100%" /></a>
 *				<a href="" title=""><img src="" width="100%" /></a>
 *				<a href="" title=""><img src="" width="100%" /></a>
 *				<a href="" title=""><img src="" width="100%" /></a>
 *				<a href="" title=""><img src="" width="100%" /></a>
 *			</div>
 * CSS
 *			#carousel
 *			{
 *				width: 700px;
 *				height: 150px;
 *				background-color: #111;
 *				position: absolute;
 *				top: 200px;
 *				left: 100px;
 *			}
 *			#carousel a
 *			{
 *				position: absolute;
 *				width: 110px;
 *			}
 *
 * @desc Creates a 3D carousel from all images inside div tag with id 'carousel'
 *
 *
 * @name 3D Carousel
 * @description Created a 3D Carousel from a list of images, with reflections and animated by mouse position
 * @param Hash hash A hash of parameters
 * @option String items items selection
 * @option Integer itemWidth the max width for each item
 * @option Integer itemHeight the max height for each item
 * @option Integer itemMinWidth the minimum width for each item, the height is automaticaly calculated to keep proportions
 * @option Float rotationSpeed the speed for rotation animation
 * @option Float reflectionSize the reflection size a fraction from items' height
 *
 * @type jQuery
 * @cat Plugins/Interface
 * @author Stefan Petre
 */
jQuery.iCarousel = {
	
	build : function(options)
	{
		return this.each(
			function()
			{
				var el = this;
				var increment = 2*Math.PI/360;
				var maxRotation = 2*Math.PI;
				if(jQuery(el).css('position') != 'relative' && jQuery(el).css('position') != 'absolute') {
					jQuery(el).css('position', 'relative');
				}
				el.carouselCfg = {
					items : jQuery(options.items, this),
					itemWidth : options.itemWidth,
					itemHeight : options.itemHeight,
					itemMinWidth : options.itemMinWidth,
					maxRotation : maxRotation,
					size : jQuery.iUtil.getSize(this),
					position : jQuery.iUtil.getPosition(this),
					start : Math.PI/2,
					rotationSpeed : options.rotationSpeed,
					reflectionSize : options.reflections,
					reflections : [],
					protectRotation : false,
					increment: 2*Math.PI/360
				};
				el.carouselCfg.radiusX = (el.carouselCfg.size.w - el.carouselCfg.itemWidth)/2;
				el.carouselCfg.radiusY =  (el.carouselCfg.size.h - el.carouselCfg.itemHeight - el.carouselCfg.itemHeight * el.carouselCfg.reflectionSize)/2;
				el.carouselCfg.step =  2*Math.PI/el.carouselCfg.items.size();
				el.carouselCfg.paddingX = el.carouselCfg.size.w/2;
				el.carouselCfg.paddingY = el.carouselCfg.size.h/2 - el.carouselCfg.itemHeight * el.carouselCfg.reflectionSize;
				var reflexions = document.createElement('div');
				jQuery(reflexions)
					.css(
						{
							position: 'absolute',
							zIndex: 1,
							top: 0,
							left: 0
						}
					);
				jQuery(el).append(reflexions);
				el.carouselCfg.items
					.each(
						function(nr)
						{
							image = jQuery('img', this).get(0);
							height = parseInt(el.carouselCfg.itemHeight*el.carouselCfg.reflectionSize);
							if (jQuery.browser.msie) {
								canvas = document.createElement('img');
								jQuery(canvas).css('position', 'absolute');
								canvas.src = image.src;				
								canvas.style.filter = 'flipv progid:DXImageTransform.Microsoft.Alpha(opacity=60, style=1, finishOpacity=0, startx=0, starty=0, finishx=0)';
					
							} else {
								canvas = document.createElement('canvas');
								if (canvas.getContext) {
									context = canvas.getContext("2d");
									canvas.style.position = 'absolute';
									canvas.style.height = height +'px';
									canvas.style.width = el.carouselCfg.itemWidth+'px';
									canvas.height = height;
									canvas.width = el.carouselCfg.itemWidth;
									context.save();
						
									context.translate(0,height);
									context.scale(1,-1);
									
									context.drawImage(
										image, 
										0, 
										0, 
										el.carouselCfg.itemWidth, 
										height
									);
					
									context.restore();
									
									context.globalCompositeOperation = "destination-out";
									var gradient = context.createLinearGradient(
										0, 
										0, 
										0, 
										height
									);
									
									gradient.addColorStop(1, "rgba(255, 255, 255, 1)");
									gradient.addColorStop(0, "rgba(255, 255, 255, 0.6)");
						
									context.fillStyle = gradient;
									if (navigator.appVersion.indexOf('WebKit') != -1) {
										context.fill();
									} else {
										context.fillRect(
											0, 
											0, 
											el.carouselCfg.itemWidth, 
											height
										);
									}
								}
							}
							
							el.carouselCfg.reflections[nr] = canvas;
							jQuery(reflexions).append(canvas);
						}
					)
					.bind(
						'mouseover',
						function(e)
						{
							el.carouselCfg.protectRotation = true;
							el.carouselCfg.speed = el.carouselCfg.increment*0.1 * el.carouselCfg.speed / Math.abs(el.carouselCfg.speed);
							return false;
						}
					)
					.bind(
						'mouseout',
						function(e)
						{
							el.carouselCfg.protectRotation = false;
							return false;
						}
					);
				jQuery.iCarousel.positionItems(el);
				el.carouselCfg.speed = el.carouselCfg.increment*0.2;
				el.carouselCfg.rotationTimer = window.setInterval(
					function()
					{
						el.carouselCfg.start += el.carouselCfg.speed;
						if (el.carouselCfg.start > maxRotation)
							el.carouselCfg.start = 0;
						jQuery.iCarousel.positionItems(el);
					},
					20
				);
				jQuery(el)
					.bind(
						'mouseout',
						function()
						{
							el.carouselCfg.speed = el.carouselCfg.increment*0.2 * el.carouselCfg.speed / Math.abs(el.carouselCfg.speed);
						}
					)
					.bind(
						'mousemove',
						function(e)
						{
							if (el.carouselCfg.protectRotation == false) {
								pointer = jQuery.iUtil.getPointer(e);
								mousex =  el.carouselCfg.size.w - pointer.x + el.carouselCfg.position.x;
								el.carouselCfg.speed = el.carouselCfg.rotationSpeed * el.carouselCfg.increment * (el.carouselCfg.size.w/2 - mousex) / (el.carouselCfg.size.w/2);
							}
						}
					);
			}
		);
	},

	positionItems : function(el)
	{
		el.carouselCfg.items.each(
			function (nr)
			{
				angle = el.carouselCfg.start+nr*el.carouselCfg.step;
				x = el.carouselCfg.radiusX*Math.cos(angle);
				y = el.carouselCfg.radiusY*Math.sin(angle) ;
				itemZIndex = parseInt(100*(el.carouselCfg.radiusY+y)/(2*el.carouselCfg.radiusY));
				parte = (el.carouselCfg.radiusY+y)/(2*el.carouselCfg.radiusY);
				
				width = parseInt((el.carouselCfg.itemWidth - el.carouselCfg.itemMinWidth) * parte + el.carouselCfg.itemMinWidth);
				height = parseInt(width * el.carouselCfg.itemHeight / el.carouselCfg.itemWidth);
				this.style.top = el.carouselCfg.paddingY + y - height/2 + "px";
	     		this.style.left = el.carouselCfg.paddingX + x - width/2 + "px";
	     		this.style.width = width + "px";
	     		this.style.height = height + "px";
	     		this.style.zIndex = itemZIndex;
				el.carouselCfg.reflections[nr].style.top = parseInt(el.carouselCfg.paddingY + y + height - 1 - height/2) + "px";
				el.carouselCfg.reflections[nr].style.left = parseInt(el.carouselCfg.paddingX + x - width/2) + "px";
				el.carouselCfg.reflections[nr].style.width = width + "px";
				el.carouselCfg.reflections[nr].style.height = parseInt(height * el.carouselCfg.reflectionSize) + "px";
			}
		);
	}
};
jQuery.fn.Carousel = jQuery.iCarousel.build;