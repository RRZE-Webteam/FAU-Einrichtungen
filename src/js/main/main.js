jQuery(document).ready(function ($) {
        var $html = $('html');
        var $body = $('body');

        // This browser supports JS
        $html.removeClass('no-js').addClass('js');
        // Add JS-enabled class to body
        $body.addClass('js-enabled responsive-large');

        var sliderFade = $body.hasClass('slider-fade');
        var sliderAutostart = $body.hasClass('slider-autoplay');
        var sliderAdaptiveHeight = $body.hasClass('slider-adaptiveHeight');
        var forceClick = $body.hasClass('mainnav-forceclick');
        var hasLogo = !$body.hasClass('nologo');
        var swapLogo = !$body.hasClass('md-showsitelogo');
        var $openflyover = false;
        var breakMD = 768;


        //  Slider
        var autostart = !!sliderAutostart;
        var pauseOnHovervar = autostart
        var showdots = false;
        var fadeit = !!sliderFade;
        var adaptiveHeight = !!sliderAdaptiveHeight;
        var autoplaySpeedval = 7000;
        var sliderNextHTML = '<button type="button" class="slick-next">Next</button>';
        var sliderPrevHTML = '<button type="button" class="slick-prev">Vor</button>';
        var stopSliderHTML = 'Stop Animation';
        var startSliderHTML = 'Start Animation';

        if ($('html').attr('lang') == 'de-DE') {
            sliderNextHTML = '<button type="button" class="slick-next">Weiter</button>';
            sliderPrevHTML = '<button type="button" class="slick-prev">Vor</button>';
            stopSliderHTML = 'Animation stoppen';
            startSliderHTML = 'Animation starten';
        }

        if ($.fn.slick) {
            $('.featured-slider').slick({
                dots: showdots,
                slidesToShow: 1,
                autoplay: autostart,
                cssEase: 'ease',
                draggable: true,
                pauseOnHover: pauseOnHovervar,
                pauseOnFocus: pauseOnHovervar,
                infinite: true,
                adaptiveHeight: adaptiveHeight,
                fade: fadeit,
                autoplaySpeed: autoplaySpeedval,
                nextArrow: sliderNextHTML,
                prevArrow: sliderPrevHTML,
                // mobileFirst: true,
                appendArrows: '.slider-controls'
            });

            $('.slick-startstop').on('click', function () {
                if ($('.slick-startstop').hasClass("stopped")) {
                    $('.featured-slider').slick('slickPlay');
                    $('.slick-startstop').removeClass('stopped');
                    $('.slick-startstop').html(stopSliderHTML);
                } else {
                    $('.featured-slider').slick('slickPause');
                    $('.slick-startstop').addClass('stopped');
                    $('.slick-startstop').html(startSliderHTML);
                }
            })
        }

        // Fancybox for lightboxes
        $('a.lightbox').fancybox({ helpers: { title: { type: 'outside' } } });

        // Assistant tabs
        $('.assistant-tabs-nav a').bind('click', function (event) {
            event.preventDefault();
            var pane = $(this).attr('href');
            $(this).parents('ul').find('a').removeClass('active');
            $(this).addClass('active');
            $(this).parents('.assistant-tabs').find('.assistant-tab-pane').removeClass('assistant-tab-pane-active');
            $(pane).addClass('assistant-tab-pane-active');
        });

        // Keyboard navigation for assistant tabs
        $('.assistant-tabs-nav a').keydown('click', function (event) {
            if (event.keyCode == 32) {
                var pane = $(this).attr('href');
                $(this).parents('ul').find('a').removeClass('active');
                $(this).addClass('active');
                $(this).parents('.assistant-tabs').find('.assistant-tab-pane').removeClass('assistant-tab-pane-active');
                $(pane).addClass('assistant-tab-pane-active');
            }
        });

        // Set environmental parameters
        var windowWidth = window.screen.width < window.outerWidth ? window.screen.width : window.outerWidth;
        var isMobile = function () {
            var windowWidth = window.screen.width < window.outerWidth ? window.screen.width : window.outerWidth;
            return windowWidth < breakMD;
        };
        var isTouch = (('ontouchstart' in window) || (navigator.msMaxTouchPoints > 0));

        // Responsive tables
        $("#content table").wrap('<div class="table-wrapper" />').wrap('<div class="scrollable" />');

        // Make #header fixed once scrolled down behind meta or on small screens
        var fixedHeader = function () {
            if ($(window).scrollTop() > 1) {
                if (!$body.hasClass("nav-scrolled")) {
                    $body.addClass('nav-scrolled');
                }
            } else {
                if ($body.hasClass("nav-scrolled")) {
                    $body.removeClass('nav-scrolled');
                }
            }
            if ($(window).scrollTop() > 200) {
                if (!$body.hasClass("toplink-faded")) {
                    $('.top-link').fadeIn();
                    $body.addClass('toplink-faded');
                }
            } else {
                if ($body.hasClass("toplink-faded")) {
                    $('.top-link').fadeOut();
                    $body.removeClass('toplink-faded');
                }
            }
            if ($(window).scrollTop() > 400) {
                if (!$body.hasClass("breakpoint-header")) {
                    $body.addClass('breakpoint-header');
                }
            } else {
                if ($body.hasClass("breakpoint-header")) {
                    $body.removeClass('breakpoint-header');
                }
            }

        };

        fixedHeader();

        $(window).scroll(fixedHeader); // TODO: Automatically debounced via JQuery?


	// Add custom var with height for fixed header
	var setHeaderHeight = function() {
	  document.documentElement.removeAttribute('style');
	  var headerHeight = document.querySelector('#headerwrapper').scrollHeight + 30;
	  
	  document.documentElement.style.setProperty('--js-fixed-header-height', `${headerHeight}px`);
	};

	 setHeaderHeight();

	
	
	
        // Add toggle icons to organigram
        $('.organigram .has-sub').each(function () {
            $(this).prepend('<span class="toggle-icon"></span>');
            $(this).children('ul').hide();
        });

        $('.organigram .has-sub .toggle-icon').bind('click', function (event) {
            event.preventDefault();

            $(this).closest('.has-sub').toggleClass('active');
            $(this).closest('.has-sub').children('ul').slideToggle();
        });


        // Handling touch devices and laptops with touch window:
        $('#nav > .nav > li > a').on('touchstart ontouchstart', function (e) {
            if ($(this).parent().hasClass("has-sub")) {
                if ($(this).hasClass('clicked-once')) {
                    $openflyover = false;
                    return true;
                } else {
                    $('#nav > .nav > li > a').removeClass('clicked-once');
                    $('#nav > .nav > li').removeClass('focus');
                    $(this).addClass('clicked-once');
                    $(this).parent('li').addClass('focus');
                    e.preventDefault();
                    $openflyover = true;
                    return false;
                }
            } else {
                return true;
            }
        });

        // Swap out the main navigation link against a button and create a JavaScript enabled backdrop
        $('#mainnav-toggle').each(function () {
            var $toggleButton = $('<button id="mainnav-toggle" type="button" aria-controls="nav" aria-haspopup="true" aria-expanded="false"/>')
                .append(this.innerHTML)
                .click(function () {
                    this._isExpanded = !this._isExpanded;
                    $(this).attr('aria-expanded', this._isExpanded ? 'true' : 'false');
                    $('html, body').animate({ scrollTop: '0px' }, 0);
                    $body.toggleClass('nav-toggled', this._isExpanded);

                    $body.removeClass('meta-links-toggled', this._isExpanded);
                    $metaNavButton = $('.meta-links-trigger-button');
                    $metaNavButton[0]._isExpanded = false;
                    $metaNavButton.find('.meta-links-trigger-text').text($metaNavButton.attr('data-label-open'));
                    $metaNavButton.attr('aria-expanded', false);
                });
            $(this).replaceWith($toggleButton);
        });

        // Swap out the meta navigation link against a button and create a JavaScript enabled backdrop
        $('.meta-links-trigger-open').each(function () {
            var $toggleButton = $('<button class="meta-links-trigger meta-links-trigger-button" type="button" aria-controls="meta-menu" aria-haspopup="true" aria-expanded="false"/>')
                .attr('data-label-open', $(this).find('.meta-links-trigger-text').text())
                .attr('data-label-close', $(this).next('.meta-links-trigger-close').find('.meta-links-trigger-text').text())
                .append('<span class="meta-links-trigger-text">' + $(this).find('.meta-links-trigger-text').text() + '</span>')
                .append($(this).find('.meta-links-trigger-icon').addClass('meta-links-trigger-icon-open'))
                .append($(this).next('.meta-links-trigger-close').find('.meta-links-trigger-icon').addClass('meta-links-trigger-icon-close'))
                .click(function () {
                    this._isExpanded = !this._isExpanded;
                    $(this).attr('aria-expanded', this._isExpanded ? 'true' : 'false');
                    $('html, body').animate({ scrollTop: '0px' }, 0);
                    $(this).find('.meta-links-trigger-text').text(this._isExpanded ? $(this).attr('data-label-close') : $(this).attr('data-label-open'));
                    $body.toggleClass('meta-links-toggled', this._isExpanded);

                    $body.removeClass('nav-toggled', this._isExpanded);
                    $mainNavButton = $('#mainnav-toggle');
                    $mainNavButton[0]._isExpanded = false;
                    $mainNavButton.attr('aria-expanded', false);
                });
            $(this).replaceWith($toggleButton);
        });
        $('.meta-links-trigger-close').remove();

        // Add backdrop that closes all menus
        $backdrop = $('<div id="menu-backdrop" aria-hidden="true"/>').click(function () {
            $body.removeClass('meta-links-toggled', this._isExpanded);
            $metaNavButton = $('.meta-links-trigger-button');
            $metaNavButton[0]._isExpanded = false;
            $metaNavButton.find('.meta-links-trigger-text').text($metaNavButton.attr('data-label-open'));
            $metaNavButton.attr('aria-expanded', false);

            $body.removeClass('nav-toggled', this._isExpanded);
            $mainNavButton = $('#mainnav-toggle');
            $mainNavButton[0]._isExpanded = false;
            $mainNavButton.attr('aria-expanded', false);
        });
        $('.metalinks').after($backdrop);

        // Create and inject alternative toggle buttons for submenus
        var $topLevelFlyouts = $('.nav > .has-sub > a + .nav-flyout');
        var toggleFlyout = function () {
            var toggle = this || null;
            var isExpanded = false;
            $('.nav > .has-sub > [type=button]').each(function (i, btn) {
                btn._isExpanded = (toggle === btn) ? !btn._isExpanded : false;
                $(btn).attr('aria-expanded', btn._isExpanded ? 'true' : 'false');
                isExpanded = isExpanded || btn._isExpanded;
                btn.nextElementSibling.scrollTop = 0;
            });
            $html.toggleClass('flyout-scrolling', isExpanded);
        };
        $topLevelFlyouts.each(function (index, topLevelFlyout) {
            var uniqueId = '_' + Math.random().toString(36).substr(2, 9);
            topLevelFlyout.$_link = $(topLevelFlyout.previousSibling);
            topLevelFlyout.$_button = $('<button type="button" aria-controls="' + uniqueId + '" aria-haspopup="true" aria-expanded="false" aria-hidden="true"/>')
                .text(topLevelFlyout.$_link.text())
                .click(toggleFlyout);
            topLevelFlyout.$_button.addClass(topLevelFlyout.$_link.attr('class'));
            $(topLevelFlyout).before(topLevelFlyout.$_button).attr('id', uniqueId);
        });

        /**
         * Enable / disable the flyout toggle buttons
         *
         * @param {Boolean} openOnClick Use a click to open the flyout menus
         */
        var updateToggleState = function (openOnClick) {
            $topLevelFlyouts.each(function (index, topLevelFlyout) {
                topLevelFlyout.$_link[openOnClick ? 'hide' : 'show']().attr('aria-hidden', openOnClick ? 'true' : 'false');
                topLevelFlyout.$_button[openOnClick ? 'show' : 'hide']().attr('aria-hidden', openOnClick ? 'false' : 'true');
            });
        };



        // Find the sidebar navigation (if present)
        var sidebarNavigation = $('.sidebar-subnav');
        sidebarNavigation = sidebarNavigation.length ? sidebarNavigation[0] : null;
        if (sidebarNavigation) {
            sidebarNavigation._origParentNode = sidebarNavigation.parentNode;
        }

        /**
         * Move the sidebar navigation
         *
         * @param {Boolean} sidebar True sidebar
         */
        var moveSidebarNavigation = function (sidebar) {
            if (sidebarNavigation) {
                if (sidebar) {
                    sidebarNavigation._origParentNode.insertBefore(sidebarNavigation, sidebarNavigation._origParentNode.firstChild);
                } else {
                    document.getElementById('nav-wrapper').appendChild(sidebarNavigation);
                }
            }
        };

        var mobileState = null;
        var updateResponsivePositioning = function () {
            var newMobileState = (window.innerWidth < breakMD);
            if (newMobileState !== mobileState) {
                mobileState = newMobileState;

                // If a logo is shown
                if (hasLogo && swapLogo) {
                    // Mobile view
                    if (newMobileState) {
                        if (!$body.hasClass('visiblelogo')) {
                            $body.addClass('visiblelogo');
                            var logoalt = $body.hasClass('nologo') ? '' : $('.branding p.sitetitle img').attr('alt');
                            $('.branding p.sitetitle img').after('<span class="visiblelogo">' + logoalt + '</span>');
                        }
                        // Desktop view
                    } else if ($body.hasClass('visiblelogo')) {
                        $body.removeClass('visiblelogo');
                        $('.visiblelogo').remove();
                    }
                }

                $body[mobileState ? 'removeClass' : 'addClass']('responsive-large')[mobileState ? 'addClass' : 'removeClass']('ismobile');

                // Enable / disable the toggle buttons
                updateToggleState(forceClick || mobileState);

                // Close all flyouts
                toggleFlyout();

                // Move the sidebar & meta navigations
                moveSidebarNavigation(!mobileState);
            }
        };

        /*
        * Add class if page is scrolled down
        */
        // The debounce function receives our function as a parameter
        const debounce = (fn) => {
            // This holds the requestAnimationFrame reference, so we can cancel it if we wish
            let frame;
            // The debounce function returns a new function that can receive a variable number of arguments
            return (...params) => {
                // If the frame variable has been defined, clear it now, and queue for next frame
                if (frame) {
                    cancelAnimationFrame(frame);
                }
                // Queue our function call for the next frame
                frame = requestAnimationFrame(() => {
                    // Call our function and pass any params we received
                    fn(...params);
                });
            }
        };
        // Reads out the scroll position and stores it in the data attribute
        // so we can use it in our stylesheets
        const storeScroll = () => {
            document.body.dataset.scroll = window.scrollY;
        }
        // Listen for new scroll events, here we debounce our `storeScroll` function
        document.addEventListener('scroll', debounce(storeScroll), { passive: true });
        // Update scroll position for first time
        storeScroll();

        $(window).on('resize', updateResponsivePositioning);
        updateResponsivePositioning();

        // Tablesorter
        $('.sorttable').tablesorter();

        // Create link list for printing
        window.addEventListener('beforeprint', function () {
            printlinks('main a[href]:not([href^="javascript:"]),aside a[href]:not([href^="javascript:"])');
        });

        // Footnotes for YouTube videos etc.
        function iframeFootnotes() {
            const iframes = document.querySelectorAll('iframe');
            iframes.forEach(iframe => {
                // Create footnote
                let footnote = document.createElement('div');
                footnote.setAttribute('class', 'tw-iframe-footnote');
                footnote.innerHTML = '<strong>Video:</strong> ' + iframe.src;
                iframe.parentNode.insertBefore(footnote, iframe.nextSibling);

                // Try to get image source
                let imageSource = null;
                switch (true) {
                    case (iframe.src.indexOf('youtube.com/embed/') != -1):
                        const substringStart = iframe.src.lastIndexOf('/') + 1;
                        const substringEnd = iframe.src.indexOf('?');
                        const substring = iframe.src.substring(substringStart, substringEnd);
                        imageSource = 'https://img.youtube.com/vi/' + substring + '/maxresdefault.jpg';
                        break;
                }

                // Create image if image source was set
                if (imageSource) {
                    let image = document.createElement('img');
                    image.setAttribute('alt', '');
                    image.setAttribute('class', 'tw-iframe-image');
                    image.setAttribute('src', imageSource);
                    iframe.parentNode.insertBefore(image, iframe.nextSibling);
                }
            });
        }

        iframeFootnotes();
    }
);

