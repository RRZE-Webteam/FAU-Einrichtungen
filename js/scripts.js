jQuery(document).ready(function($) {	
	// This browser supports JS
	$('html').removeClass('no-js').addClass('js');

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
		$('#nav').toggleClass('toggled');
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
	var isMobile = windowWidth < 767;
	var isTouch = (('ontouchstart' in window) || (navigator.msMaxTouchPoints > 0));

	
	// Move sidebar on mobile devices to the bottom
	if(isMobile) {
		var sidebar = $('.sidebar-inline').html();
		$('.sidebar-inline').remove();
		$('#content .container').append(sidebar);
	}
	
	// Touch navigation
	if(isTouch)
	{
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
	
	// Fix for very large navigation (text-zoomed)
	if( ! isMobile )
	{
		$('#nav > li').hover(function() {
			var top = 0;

			if($('body').hasClass('nav-fixed'))
			{
				top = $(this).offset().top + $(this).height() - $(window).scrollTop() + 10;
			}
			else
			{
				top = $(this).offset().top + $(this).height();
			}

			var offset = 11;
			if($('body').hasClass('nav-fixed')) { offset += 0;}
			$('.nav-flyout').css({'top': top-offset});
		})
	}
	
	// Responsive tables
	$("#content table").wrap('<div class="table-wrapper" />').wrap('<div class="scrollable" />');
	
	// Scroll spy on desktop
	if( ! isMobile )
	{
		$(window).scroll(function () {
			if ($(window).scrollTop() > 30) {
				$('body').addClass('nav-scrolled');
			} else {
				$('body').removeClass('nav-scrolled');
			}

			if ($(window).scrollTop() > 60) {
				$('body').addClass('nav-fixed');
			} else {
				$('body').removeClass('nav-fixed');
			}
			
			if ($(window).scrollTop() > 200) {
				$('.top-link').fadeIn();
			} else {
				$('.top-link').fadeOut();
			}
		});
	}
	
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

}
);