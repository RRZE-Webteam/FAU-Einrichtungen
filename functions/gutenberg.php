<?php
/**
* @package WordPress
* @subpackage FAU
* @since FAU 1.10
*/

/* 
 * Settings for Gutenberg Editor
 *  See also: 
 *   https://wordpress.org/gutenberg/handbook/reference/theme-support/
 *   https://themecoder.de/2018/09/20/gutenberg-farbpalette/
 *   https://www.elmastudio.de/wordpress-themes-fuer-gutenberg-vorbereiten/
 *   https://www.billerickson.net/getting-your-theme-ready-for-gutenberg/
 */

/*
 * Note: Maybe test if gutenberg is enabled first.
 *   $is_gutenberg_enabled = false;
 *   if(has_filter('is_gutenberg_enabled') {
 *       $is_gutenberg_enabled = apply_filters('is_gutenberg_enabled', false);
 *    }
 * with plugin https://gitlab.rrze.fau.de/rrze-webteam/rrze-writing/blob/master/RRZE/Writing/Editor/Editor.php
 */


/*-----------------------------------------------------------------------------------*/
/* We use our own color set in this theme and dont want autors to change text colors
/*-----------------------------------------------------------------------------------*/
function fau_gutenberg_settings() {
    
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
add_action( 'after_setup_theme', 'fau_gutenberg_settings' );

/*-----------------------------------------------------------------------------------*/
/* Activate scripts and style for backend use of Gutenberg
/*-----------------------------------------------------------------------------------*/
function fau_add_gutenberg_assets() {
	// Load the theme styles within Gutenberg.
	wp_enqueue_style( 'fau-gutenberg', get_theme_file_uri( '/css/fau-theme-gutenberg.css' ), false );
}
add_action( 'enqueue_block_editor_assets', 'fau_add_gutenberg_assets' );

/*-----------------------------------------------------------------------------------*/
/* Defined allowed core block types if theme is used in Gutenberg Block Editor
/*-----------------------------------------------------------------------------------*/
function fau_allowed_block_types( $allowed_block_types, $post ) {
    if ( ($post->post_type === 'post' ) || ( $post->post_type === 'page' )) {
        return array(
	    'core/paragraph',
	    'core/image',
	    'core/list',
	    'core/file',
	    'core/gallery',
	    'core/heading',
	    'core/html',
	    'core/quote',
	    'core/shortcode',
	    'core/table'
        );
    }
    return $allowed_block_types;
}

// add_filter( 'allowed_block_types', 'fau_allowed_block_types', 10, 2 );

/* 
 * TODO: 
 * Wir mussen das andersrum machen, da wir die Liste der erlaubten Typen nicht alle kennen: 
 * Es können durch Plugins andere hinzukommen, die wir bearbeitbar lassen wollen.
 * Daher andersUm
 * Array eingeben der Typen, die wir verbieten wollen.
 * Diese gegen eine Liste matchen, die alle Typen enthält.
 * Und von der Gesamatliste eben die verbotenenen Typen abziehen
 */