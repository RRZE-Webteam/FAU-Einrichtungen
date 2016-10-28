/* 
 * JS Config for jQuery Carousel
 * Details see: https://dev7studios.com/caroufredsel-old/configuration.php 
 */

/*
 * Breakpoints:
    $breakXS:   320px;
    $breakSM:   480px;
    $breakMD:   767px;
    $breakLG:   979px;
 */


// Logo-Slider
jQuery(document).ready(function($) {	
    	var windowWidth = window.screen.width < window.outerWidth ? window.screen.width : window.outerWidth;
    	var isMobile = windowWidth < 321;
	var itemsvisible = 5;
	var barwidth;
	var varresponsive = true;
	
	if($('.logos-menu').length > 0) {
		if( ! isMobile) {
		    if (windowWidth > 979) {
			itemsvisible = 6;
			barwidth = 960;
		    } else if (windowWidth > 767) {
			itemsvisible = 5;
			barwidth = 740;
		    } else if (windowWidth > 480) {
			itemsvisible = 3;
			barwidth = '100%';
		    } else if (windowWidth > 320) {
			itemsvisible = 2;
			barwidth = '100%';
		    }
		    
		    
			$('.logos-menu').carouFredSel({
				responsive: varresponsive,
				width: barwidth,
				height: 110,
				scroll: 1,
				align: "center",
				padding:25,
				items: {
					width: 140,
					height: 110,
					visible: itemsvisible,
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
		
	}
});