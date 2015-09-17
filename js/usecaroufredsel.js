/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


// Logo-Slider
jQuery(document).ready(function($) {	
    	var windowWidth = window.screen.width < window.outerWidth ? window.screen.width : window.outerWidth;
    	var isMobile = windowWidth < 767;

	if($('.logos-menu').length > 0) {
		if( ! isMobile)
		{
			$('.logos-menu').carouFredSel({
				responsive: true,
				width: '100%',
				height: 110,
				scroll: 1,
				padding: 20,
				items: {
					width: 140,
					height: 110,
					visible: 6
				},
				prev: {
					button: '#logos-menu-prev',
					key: 'left'
				},
				next: {
					button: '#logos-menu-next',
					key: 'next'
				},
				auto: {
					button: '#logos-menu-playpause'
				}
			});
		}
		else
		{
			$('.logos-menu').carouFredSel({
				responsive: true,
				width: '100%',
				height: 110,
				scroll: 1,
				padding: 20,
				items: {
					width: 140,
					height: 110,
					visible: 2
				}
			});
		}
	}
});