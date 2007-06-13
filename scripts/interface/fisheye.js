/**
 * Interface Elements for jQuery
 * Fisheye menu
 * 
 * http://interface.eyecon.ro
 * 
 * Copyright (c) 2006 Stefan Petre
 * Dual licensed under the MIT (MIT-LICENSE.txt) 
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 */

/**
 * Build a Fisheye menu from a list of links
 *
 * @name Fisheye
 * @description Build a Fisheye menu from a list of links
 * @param Hash hash A hash of parameters
 * @option String items items selection
 * @option String container container element
 * @option Integer itemWidth the minimum width for each item
 * @option Integer maxWidth the maximum width for each item
 * @option String itemsText selection of element that contains the text for each item
 * @option Integer proximity the distance from element that make item to interact
 * @option String valign vertical alignment
 * @option String halign horizontal alignment
 *
 * @type jQuery
 * @cat Plugins/Interface
 * @author Stefan Petre
 */
jQuery.iFisheye = {
	
	build : function(options)
	{
	
		return this.each(
			function()
			{
				var el = this;
				el.fisheyeCfg = {
					items : jQuery(options.items, this),
					container: jQuery(options.container, this),
					pos : jQuery.iUtil.getPosition(this),
					itemWidth: options.itemWidth,
					itemsText: options.itemsText,
					proximity: options.proximity,
					valign: options.valign,
					halign: options.halign,
					maxWidth : options.maxWidth
				};
				jQuery.iFisheye.positionContainer(el, 0);
				jQuery(window).bind(
					'resize',
					function()
					{
						el.fisheyeCfg.pos = jQuery.iUtil.getPosition(el);
						jQuery.iFisheye.positionContainer(el, 0);
						jQuery.iFisheye.positionItems(el);
					}
				);
				jQuery.iFisheye.positionItems(el);
				el.fisheyeCfg.items
					.bind(
						'mouseover',
						function()
						{
							jQuery(el.fisheyeCfg.itemsText, this).get(0).style.display = 'block';
						}
					)
					.bind(
						'mouseout',
						function()
						{
							jQuery(el.fisheyeCfg.itemsText, this).get(0).style.display = 'none';
						}
					);
				jQuery(document).bind(
					'mousemove',
					function(e)
					{
						var pointer = jQuery.iUtil.getPointer(e);
						var toAdd = 0;
						if (el.fisheyeCfg.halign && el.fisheyeCfg.halign == 'center')
							var posx = pointer.x - el.fisheyeCfg.pos.x - (el.offsetWidth - el.fisheyeCfg.itemWidth * el.fisheyeCfg.items.size())/2 - el.fisheyeCfg.itemWidth/2;
						else if (el.fisheyeCfg.halign && el.fisheyeCfg.halign == 'right')
							var posx = pointer.x - el.fisheyeCfg.pos.x - el.offsetWidth + el.fisheyeCfg.itemWidth * el.fisheyeCfg.items.size();
						else 
							var posx = pointer.x - el.fisheyeCfg.pos.x;
						var posy = Math.pow(pointer.y - el.fisheyeCfg.pos.y - el.offsetHeight/2,2);
						el.fisheyeCfg.items.each(
							function(nr)
							{
								distance = Math.sqrt(
									Math.pow(posx - nr*el.fisheyeCfg.itemWidth, 2)
									+ posy
								);
								distance -= el.fisheyeCfg.itemWidth/2;
								
								distance = distance < 0 ? 0 : distance;
								distance = distance > el.fisheyeCfg.proximity ? el.fisheyeCfg.proximity : distance;
								distance = el.fisheyeCfg.proximity - distance;
								
								extraWidth = el.fisheyeCfg.maxWidth * distance/el.fisheyeCfg.proximity;
								
								this.style.width = el.fisheyeCfg.itemWidth + extraWidth + 'px';
								this.style.left = el.fisheyeCfg.itemWidth * nr + toAdd + 'px';
								toAdd += extraWidth;
							}
						);
						jQuery.iFisheye.positionContainer(el, toAdd);
					}
				);
			}
		)
	},
	
	positionContainer : function(el, toAdd)
	{
		if (el.fisheyeCfg.halign)
			if (el.fisheyeCfg.halign == 'center')
				el.fisheyeCfg.container.get(0).style.left = (el.offsetWidth - el.fisheyeCfg.itemWidth * el.fisheyeCfg.items.size())/2 - toAdd/2 + 'px';
			else if (el.fisheyeCfg.halign == 'left')
				el.fisheyeCfg.container.get(0).style.left =  - toAdd/el.fisheyeCfg.items.size() + 'px';
			else if (el.fisheyeCfg.halign == 'right')
				el.fisheyeCfg.container.get(0).style.left =  (el.offsetWidth - el.fisheyeCfg.itemWidth * el.fisheyeCfg.items.size()) - toAdd/2 + 'px';
		el.fisheyeCfg.container.get(0).style.width = el.fisheyeCfg.itemWidth * el.fisheyeCfg.items.size() + toAdd + 'px';
	},
	
	positionItems : function(el)
	{
		el.fisheyeCfg.items.each(
			function(nr)
			{
				this.style.width = el.fisheyeCfg.itemWidth + 'px';
				this.style.left = el.fisheyeCfg.itemWidth * nr + 'px';
			}
		);
	}
};

jQuery.fn.Fisheye = jQuery.iFisheye.build;