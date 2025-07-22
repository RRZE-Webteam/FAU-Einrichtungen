/* 
 * Copyright (C) 2025 unrz59
 *
 * JS for use of the [organigram] shortcode
 * Moved from main.js into own file due to planed remove from theme 
 */

(function ($) {
    $(function () {
	// Add toggle icons to organigram
	$(".organigram .has-sub").each(function () {
	    $(this).prepend('<span class="toggle-icon"></span>');
	    $(this).children("ul").hide();
	});

       $(".organigram .has-sub .toggle-icon").on("click", function (event) {
            event.preventDefault();
            $(this).closest(".has-sub").toggleClass("active");
            $(this).closest(".has-sub").children("ul").slideToggle();
        });
       
    });
}(jQuery));


