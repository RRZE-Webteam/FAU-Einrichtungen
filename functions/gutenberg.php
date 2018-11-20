<?php

/* 
 * Settings for Gutenberg Editor
 *  See also: 
 *   https://wordpress.org/gutenberg/handbook/reference/theme-support/
 *   https://themecoder.de/2018/09/20/gutenberg-farbpalette/
 *   https://www.elmastudio.de/wordpress-themes-fuer-gutenberg-vorbereiten/
 *   https://www.billerickson.net/getting-your-theme-ready-for-gutenberg/
 */


/* 
 * We use our own color set in this theme and dont want autors to change text colors
 */
function FAU_Einrichtungen_gutenberg_settings() {
    
	// Disable color palette.
	add_theme_support( 'editor-color-palette' );

	// Disable color picker.
	add_theme_support( 'disable-custom-colors' );
	
	// Dont allow font sizes of gutenberg
	// https://wordpress.org/gutenberg/handbook/extensibility/theme-support/#block-font-sizes
	add_theme_support('disable-custom-font-sizes');
	

	// allow responsive embedded content
	add_theme_support( 'responsive-embeds' );

		
		
}
add_action( 'after_setup_theme', 'FAU_Einrichtungen_gutenberg_settings' );


