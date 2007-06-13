/**
 * Interface Elements for jQuery
 * Accordion
 * 
 * http://interface.eyecon.ro
 * 
 * Copyright (c) 2006 Stefan Petre
 * Dual licensed under the MIT (MIT-LICENSE.txt) 
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 */

/**
 * Create an accordion from a HTML structure
 *
 * @example $('#myAccordion').Accordion(
 *				{
 *					headerSelector	: 'dt',
 *					panelSelector	: 'dd',
 *					activeClass		: 'myAccordionActive',
 *					hoverClass		: 'myAccordionHover',
 *					panelHeight		: 200,
 *					speed			: 300
 *				}
 *			);
 * @desc Converts definition list with id 'myAccordion' into an accordion width dt tags as headers and dd tags as panels
 * 
 * @name Accordion
 * @description Create an accordion from a HTML structure
 * @param Hash hash A hash of parameters
 * @option Integer panelHeight the pannels' height
 * @option String headerSelector selector for header elements
 * @option String panelSelector selector for panel elements
 * @option String activeClass (optional) CSS Class for active header
 * @option String hoverClass (optional) CSS Class for hovered header
 * @option Function onShow (optional) callback called whenever an pannel gets active
 * @option Function onHide (optional) callback called whenever an pannel gets incative
 * @option Function onClick (optional) callback called just before an panel gets active
 * @option Mixed speed (optional) animation speed, integer for miliseconds, string ['slow' | 'normal' | 'fast']
 * @option Integer crrentPanel (otional) the active panel on initialisation
 *
 * @type jQuery
 * @cat Plugins/Interface
 * @author Stefan Petre
 */
jQuery.iAccordion = {
	build : function(options)
	{
		return this.each(
			function()
			{
				if (!options.headerSelector || !options.panelSelector)
					return;
				var el = this;
				el.accordionCfg = {
					panelHeight			: options.panelHeight||300,
					headerSelector		: options.headerSelector,
					panelSelector		: options.panelSelector,
					activeClass			: options.activeClass||'fakeAccordionClass',
					hoverClass			: options.hoverClass||'fakeAccordionClass',
					onShow				: options.onShow && typeof options.onShow == 'function' ? options.onShow : false,
					onHide				: options.onShow && typeof options.onHide == 'function' ? options.onHide : false,
					onClick				: options.onClick && typeof options.onClick == 'function' ? options.onClick : false,
					headers				: jQuery(options.headerSelector, this),
					panels				: jQuery(options.panelSelector, this),
					speed				: options.speed||400,
					currentPanel		: options.currentPanel||0
				};
				el.accordionCfg.panels
					.hide()
					.css('height', '1px')
					.eq(0)
					.css(
						{
							height: el.accordionCfg.panelHeight + 'px',
							display: 'block'
						}
					)
					.end();
					
				el.accordionCfg.headers
				.each(
					function(nr)
					{
						this.accordionPos = nr;
					}
				)
				.hover(
					function()
					{
						jQuery(this).addClass(el.accordionCfg.hoverClass);
					},
					function()
					{
						jQuery(this).removeClass(el.accordionCfg.hoverClass);
					}
				)
				.bind(
					'click',
					function(e)
					{
						if (el.accordionCfg.currentPanel == this.accordionPos)
							return;
						el.accordionCfg.headers
							.eq(el.accordionCfg.currentPanel)
							.removeClass(el.accordionCfg.activeClass)
							.end()
							.eq(this.accordionPos)
							.addClass(el.accordionCfg.activeClass)
							.end();
						el.accordionCfg.panels
						.eq(el.accordionCfg.currentPanel)
							.animate(
								{height:0},
								el.accordionCfg.speed,
								function()
								{
									this.style.display = 'none';
									if (el.accordionCfg.onHide) {
										el.accordionCfg.onHide.apply(el, [this]);
									}
								}
							)
						.end()
						.eq(this.accordionPos)
							.show()
							.animate (
								{height:el.accordionCfg.panelHeight},
								el.accordionCfg.speed,
								function()
								{
									this.style.display = 'block';
									if (el.accordionCfg.onShow) {
										el.accordionCfg.onShow.apply(el, [this]);
									}
								}
							)
						.end();
						
						if (el.accordionCfg.onClick) {
							el.accordionCfg.onClick.apply(
								el, 
								[
									this, 
									el.accordionCfg.panels.get(this.accordionPos),
									el.accordionCfg.headers.get(el.accordionCfg.currentPanel),
									el.accordionCfg.panels.get(el.accordionCfg.currentPanel)
								]
							);
						}
						el.accordionCfg.currentPanel = this.accordionPos;
					}
				)
				.eq(0)
				.addClass(el.accordionCfg.activeClass)
				.end();
				jQuery(this)
					.css('height', jQuery(this).css('height'))
					.css('overflow', 'hidden');
			}
		);
	}
};

jQuery.fn.Accordion = jQuery.iAccordion.build;