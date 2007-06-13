/**
 * Interface Elements for jQuery
 * Autoscroller
 * 
 * http://interface.eyecon.ro
 * 
 * Copyright (c) 2006 Stefan Petre
 * Dual licensed under the MIT (MIT-LICENSE.txt) 
 * and GPL (GPL-LICENSE.txt) licenses.
 *   
 *
 */

/**
 * Utility object that helps to make custom autoscrollers.
 * 
 * @example
 *		$('div.dragMe').Draggable(
 *			{
 *				onStart : function()
 *				{
 *					$.iAutoscroller.start(this, document.getElementsByTagName('body'));
 *				},
 *				onStop : function()
 *				{
 *					$.iAutoscroller.stop();
 *				}
 *			}
 *		);
 *
 * @description Utility object that helps to make custom autoscrollers
 * @type jQuery
 * @cat Plugins/Interface
 * @author Stefan Petre
 */

jQuery.iAutoscroller = {
	timer: null,
	elToScroll: null,
	elsToScroll: null,
	step: 10,
	/**
	 * This is called to start autoscrolling
	 * @param DOMElement el the element used as reference
	 * @param Array els collection of elements to scroll
	 * @param Integer step the pixels scroll on each step
	 * @param Integer interval miliseconds between each step
	 */
	start: function(el, els, step, interval)
	{
		jQuery.iAutoscroller.elToScroll = el;
		jQuery.iAutoscroller.elsToScroll = els;
		jQuery.iAutoscroller.step = parseInt(step)||10;
		jQuery.iAutoscroller.timer = window.setInterval(jQuery.iAutoscroller.doScroll, parseInt(interval)||40);
	},
	
	//private function
	doScroll : function()
	{
		for (i=0;i<jQuery.iAutoscroller.elsToScroll.length; i++) {
				if(!jQuery.iAutoscroller.elsToScroll[i].parentData) {
					jQuery.iAutoscroller.elsToScroll[i].parentData = jQuery.extend(
						jQuery.iUtil.getPositionLite(jQuery.iAutoscroller.elsToScroll[i]),
						jQuery.iUtil.getSizeLite(jQuery.iAutoscroller.elsToScroll[i]),
						jQuery.iUtil.getScroll(jQuery.iAutoscroller.elsToScroll[i])
					);
				} else {
					jQuery.iAutoscroller.elsToScroll[i].parentData.t = jQuery.iAutoscroller.elsToScroll[i].scrollTop;
					jQuery.iAutoscroller.elsToScroll[i].parentData.l = jQuery.iAutoscroller.elsToScroll[i].scrollLeft;
				}
				
				if (jQuery.iAutoscroller.elToScroll.dragCfg && jQuery.iAutoscroller.elToScroll.dragCfg.init == true) {
					elementData = {
						x : jQuery.iAutoscroller.elToScroll.dragCfg.nx,
						y : jQuery.iAutoscroller.elToScroll.dragCfg.ny,
						wb : jQuery.iAutoscroller.elToScroll.dragCfg.oC.wb,
						hb : jQuery.iAutoscroller.elToScroll.dragCfg.oC.hb
					};
				} else {
					elementData = jQuery.extend(
						jQuery.iUtil.getPositionLite(jQuery.iAutoscroller.elToScroll),
						jQuery.iUtil.getSizeLite(jQuery.iAutoscroller.elToScroll)
					);
				}
				if (
					jQuery.iAutoscroller.elsToScroll[i].parentData.t > 0
					 && 
					jQuery.iAutoscroller.elsToScroll[i].parentData.y + jQuery.iAutoscroller.elsToScroll[i].parentData.t > elementData.y) {
					jQuery.iAutoscroller.elsToScroll[i].scrollTop -= jQuery.iAutoscroller.step;
				} else if (jQuery.iAutoscroller.elsToScroll[i].parentData.t <= jQuery.iAutoscroller.elsToScroll[i].parentData.h && jQuery.iAutoscroller.elsToScroll[i].parentData.t + jQuery.iAutoscroller.elsToScroll[i].parentData.hb < elementData.y + elementData.hb) {
					jQuery.iAutoscroller.elsToScroll[i].scrollTop += jQuery.iAutoscroller.step;
				}
				if (jQuery.iAutoscroller.elsToScroll[i].parentData.l > 0 && jQuery.iAutoscroller.elsToScroll[i].parentData.x + jQuery.iAutoscroller.elsToScroll[i].parentData.l > elementData.x) {
					jQuery.iAutoscroller.elsToScroll[i].scrollLeft -= jQuery.iAutoscroller.step;
				} else if (jQuery.iAutoscroller.elsToScroll[i].parentData.l <= jQuery.iAutoscroller.elsToScroll[i].parentData.wh && jQuery.iAutoscroller.elsToScroll[i].parentData.l + jQuery.iAutoscroller.elsToScroll[i].parentData.wb < elementData.x + elementData.wb) {
					jQuery.iAutoscroller.elsToScroll[i].scrollLeft += jQuery.iAutoscroller.step;
				}
		}
	},
	/**
	 * This is called to stop autoscrolling
	 */
	stop: function()
	{
		window.clearInterval(jQuery.iAutoscroller.timer);
		jQuery.iAutoscroller.elToScroll = null;
		jQuery.iAutoscroller.elsToScroll = null;
		for (i in jQuery.iAutoscroller.elsToScroll) {
			jQuery.iAutoscroller.elsToScroll[i].parentData = null;
		}
	}
};