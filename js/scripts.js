jQuery(document).ready(function($) {	
	// This browser supports JS
	$('html').removeClass('no-js').addClass('js');

	var breakMD = 767;
	var breakSM = 480;
	var breakLG = 979;
	var wpAdminBarHeight = 46;
	var wpAdminBarHeightMD = 32;
	var metaBar = 42;
	
	
	// Smooth scrolling for anchor-links (excluding accordion-toggles)
	$('a[href*=#]:not([href=#]):not(.accordion-toggle):not(.accordion-tabs-nav-toggle)').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top - 185
					}, 1000);
					return false;
				}
			}
		});

	// Fancybox for lightboxes
	$('a.lightbox').fancybox({ helpers: { title: { type: 'outside'}}});
	
	// Hover-Intent for navigation
	$('#nav').hoverIntent({
		over: function() {$(this).addClass('focus')},
		out: function() {$(this).removeClass('focus')},
		selector: 'li',
		timeout: 150,
		interval: 20
	});
	
	// Keyboard-navigation, remove and set focus class on focus-change
	$('a').not($('#nav > li div a')).focus(function() {
		$('#nav > li').removeClass('focus');
	});
	
	$('#nav > li > a').focus(function() {
		$('#nav > li').removeClass('focus');
		$(this).parents('li').addClass('focus');
	});
	
	$('#meta-nav > li > a').focus(function() {
		$('#meta-nav > li').removeClass('focus');
		$(this).parents('li').addClass('focus');
	});
	
	$('.mlp_language_box ul li a').focus(function() {
		$(this).parents('ul').addClass('focus');
	});
	
	// Mobile navigation toggle
	$('#nav-toggle').bind('click', function(event) {
		event.preventDefault();
		$('body').toggleClass('menu-toggled');
	});
	
	// Set jumplinks
	$('.jumplinks a').bind('click', function(event) {
		event.preventDefault();
		
		var target = $(this).data('target');
		var firstchild = $(this).data('firstchild');
		
		if(firstchild == 1) {
			$(target).eq(0).focus();
		}
		else {
			$(target).focus();
		}
	});
	

	

	
	// Assistant tabs
	$('.assistant-tabs-nav a').bind('click', function(event) {
		event.preventDefault();
		var pane = $(this).attr('href');
		$(this).parents('ul').find('a').removeClass('active');
		$(this).addClass('active');
		$(this).parents('.assistant-tabs').find('.assistant-tab-pane').removeClass('assistant-tab-pane-active');
		$(pane).addClass('assistant-tab-pane-active');
	});
	
	// Keyboard navigation for assistant tabs
	$('.assistant-tabs-nav a').keydown('click', function(event) {
		if(event.keyCode == 32) 
		{
			var pane = $(this).attr('href');
			$(this).parents('ul').find('a').removeClass('active');
			$(this).addClass('active');
			$(this).parents('.assistant-tabs').find('.assistant-tab-pane').removeClass('assistant-tab-pane-active');
			$(pane).addClass('assistant-tab-pane-active');
		}
	});
	
	// Accordions
	$('.accordion-toggle').bind('click', function(event) {
		event.preventDefault();
		var accordion = $(this).attr('href');
		$(this).closest('.accordion').find('.accordion-toggle').not($(this)).removeClass('active');
		$(this).closest('.accordion').find('.accordion-body').not(accordion).slideUp();
		$(this).toggleClass('active');
		$(accordion).slideToggle();
	});
	
	// Keyboard navigation for accordions
	$('.accordion-toggle').keydown(function(event) {
		if(event.keyCode == 32)
		{
			var accordion = $(this).attr('href');
			$(this).closest('.accordion').find('.accordion-toggle').not($(this)).removeClass('active');
			$(this).closest('.accordion').find('.accordion-body').not(accordion).slideUp();
			$(this).toggleClass('active');
			$(accordion).slideToggle();
		}
	});


	function openAnchorAccordion() {
	    if (window.location.hash) {
		var identifier = window.location.hash.split('_')[0];
		var keynum = window.location.hash.split('_')[1];
		if (identifier == '#collapse') {
		    if ($.isNumeric(keynum)) {
			var $findid = 'collapse_'+ keynum;
			var $target = $('body').find('#'+ $findid);  
			$target.find('.accordion-toggle').addClass('active');
			$target.find('.accordion-body').slideUp();
			$target.toggleClass('active');
			$target.slideToggle();
			var $scrolloffset = $target.offset().top - 220;	
			 $('html,body').animate({scrollTop: $scrolloffset},'slow');
		    }
		   
		 }
	    }
	}
	openAnchorAccordion();



	// AJAX for studienangebot-database
	$('#studienangebot *').change(function() {
		// Show loading spinner
		$('#loading').fadeIn(300);
		
		// Get results and replace content
		$.get($(this).parents('form').attr('action'), $(this).parents('form').serialize(), function(data) {
			$('#studienangebot-result').replaceWith($(data).find('#studienangebot-result'));
			$('#loading').fadeOut(300);
		});
	});
	
	// Set environmental parameters
	var windowWidth = window.screen.width < window.outerWidth ? window.screen.width : window.outerWidth;
	var isMobile = function() { 
		var windowWidth = window.screen.width < window.outerWidth ? window.screen.width : window.outerWidth;
		return windowWidth < breakMD;
	    };
	var isTouch = (('ontouchstart' in window) || (navigator.msMaxTouchPoints > 0));


	// Move sidebar on mobile devices to the bottom
	if(isMobile()) {
		var sidebar = $('.sidebar-inline').html();
		$('.sidebar-inline').remove();
		$('#content .container').append(sidebar);
	}
	
	// Touch navigation
	if(isTouch) {
		$('#nav > li > a').click(function() {		
			if($(this).hasClass('clicked-once'))
			{
				return true;
			}
			else
			{
				$('#nav > li > a').removeClass('clicked-once');
				$(this).addClass('clicked-once');
				return false;
			}

		});
	}
	
	
	// Responsive tables
	$("#content table").wrap('<div class="table-wrapper" />').wrap('<div class="scrollable" />');
	

	// Make #header fixed once scrolled down behind meta or on small screens
	function fixedHeader() {
	    if ($(window).scrollTop() > 20) {
		$('body').addClass('nav-scrolled');
	    } else {
		$('body').removeClass('nav-scrolled');
	    }
	    if ($(window).scrollTop() > 200) {
		$('.top-link').fadeIn();
	    } else {
		$('.top-link').fadeOut();
	    }
	    
	    
	    var topoffset = 0;
	    
	    if (isMobile()) {
		if ($('body').hasClass('admin-bar')) {
		    topoffset = wpAdminBarHeight;
		}
	    } else {
		
		
		
		
		if ($('body').hasClass('admin-bar')) {		    
		    topoffset = metaBar +wpAdminBarHeightMD;
		} else {
		    topoffset = metaBar;
		}	
	    }
	
	    var windowWidth = window.screen.width < window.outerWidth ? window.screen.width : window.outerWidth;
	    if (windowWidth > breakLG) {
		 $('body').addClass('nav-fixed');
	    } else {
		if ($(window).scrollTop() > topoffset) {
		    $('body').addClass('nav-fixed');
		} else {
		    $('body').removeClass('nav-fixed');
		}
	    }

	};
	
	fixedHeader();
	
	$(window).scroll(function () {
	    fixedHeader();
	});
	
	// Equalize image gallery grid heights
	function equalize() {
		var height = 0;
		
		$('.image-gallery-grid li').each(function() {
			// var imageHeight = $(this).find('img').innerHeight();
			// if(imageHeight < 92) imageHeight = 92;
			var imageHeight = 120;
			var captionHeight = $(this).find('.caption').innerHeight();
			
			if((imageHeight + captionHeight) > height) {
				height = imageHeight + captionHeight;
			}
		});
		
		$('.image-gallery-grid li').css({
			'height': height+'px'
		});
	}
	equalize();
	
	$(window).resize(function() {
		equalize();
	});
	
	
	// Add toggle icons to organigram
	$('.organigram .has-sub').each(function() {
		$(this).prepend('<span class="toggle-icon"></span>');
		$(this).children('ul').hide();
	});
	
	$('.organigram .has-sub .toggle-icon').bind('click', function(event) {
		event.preventDefault();
		
		$(this).closest('.has-sub').toggleClass('active');
		$(this).closest('.has-sub').children('ul').slideToggle();
	});

	//Add JS-enabled class to body
	$('body').addClass('js-enabled');


	// Off-canvas navigation
	var navContainer = $('<div id="off-canvas">');
	var nav = $('#nav').clone();
	var navCloseLabel = $('<a id="off-canvas-close" href="#"><span>Menü schließen</span> <i class="fa fa-times"></i></a>')

	if ($('html').attr('lang') !== 'de-DE') {
		$('span', navCloseLabel).text('Close menu');
	}

	navCloseLabel.appendTo(navContainer);
	nav.appendTo(navContainer);
	navContainer.appendTo('body');
	$('<div id="off-canvas-overlay">').appendTo('body');

	nav.on('click', '.menu-item.level1', function(e) {
		if ($(e.target).parent().hasClass('level1')) {
			e.preventDefault();
			if (!$(this).hasClass('focus')) {
				$('#off-canvas .menu-item.level1').removeClass('focus');
			}
			$(this).toggleClass('focus');
		}
	});

	$('#off-canvas-overlay, #off-canvas-close').on('click', function(e) {
		e.preventDefault();
		$('body').removeClass('menu-toggled')
	});


	//Update responsive positioning of some elements
	$('body').addClass('responsive-large');
	if ($('body').hasClass('page-template-page-start')) {
		$('#hero > .container').addClass('hero-navigation');
		$('.logos-menu span').addClass('logos-menu-logo');
		$('#content > .container').eq(0).append('<div class="responsive-logos-container" />');
	}

	var updateResponsivePositioning = function() {
		var width = $(window).width();
		var body = $('body');
		var heroNavigation = $('.hero-navigation');
		var header = $('#header');
		var metaNav = $('#meta-nav');
		var subNav = $('#subnav').parent();
		var logos = $('.logos-menu-logo');
		if (width > breakMD && !body.hasClass('responsive-large')) {
			body.addClass('responsive-large');
			heroNavigation.appendTo('#hero');
			header.insertAfter('#meta');
			metaNav.appendTo('#meta .container .pull-left');
			subNav.prependTo('#content .row:first');
			logos.appendTo('.logos-menu');
		} else if (width <= breakMD && body.hasClass('responsive-large')) {
			body.removeClass('responsive-large');
			heroNavigation.prependTo('#footer');
			header.prependTo('body');
			metaNav.appendTo('#off-canvas');
		//	subNav.appendTo('#content .row');
		subNav.appendTo(navContainer);
			logos.appendTo('.responsive-logos-container');
			
		}
	};

	updateResponsivePositioning();

	$(window).on('resize', function() {
		updateResponsivePositioning();
	});

}
);
