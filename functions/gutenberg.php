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
 *   https://wordpress.stackexchange.com/questions/320653/how-to-detect-the-usage-of-gutenberg
 */



/*-----------------------------------------------------------------------------------*/
/* We use our own color set in this theme and dont want autors to change text colors
/*-----------------------------------------------------------------------------------*/
function fau_gutenberg_settings() {
    global $is_gutenberg_enabled;

	$is_gutenberg_enabled = fau_blockeditor_is_active();
	
	if ($is_gutenberg_enabled) {
		return;
	}
	
	// Disable color palette.
	add_theme_support( 'editor-color-palette' );

	// Disable color picker.
	add_theme_support( 'disable-custom-colors' );
	
	// Dont allow font sizes of gutenberg
	// https://wordpress.org/gutenberg/handbook/extensibility/theme-support/#block-font-sizes
	add_theme_support('disable-custom-font-sizes');
	
	// allow responsive embedded content
	add_theme_support( 'responsive-embeds' );

    // Remove Gutenbergs Userstyle and SVGs Duotone injections from 5.9.2
    remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
    remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
    
}
add_action( 'after_setup_theme', 'fau_gutenberg_settings' );

/*-----------------------------------------------------------------------------------*/
/* Activate scripts and style for backend use of Gutenberg
/*-----------------------------------------------------------------------------------*/
function fau_add_gutenberg_assets() {
	// Load the theme styles within Gutenberg.
	global $is_gutenberg_enabled;

	if ($is_gutenberg_enabled) {
		wp_enqueue_style( 'fau-gutenberg', get_theme_file_uri( '/css/fau-theme-gutenberg.css' ), false );
	}
}
// add_action( 'enqueue_block_editor_assets', 'fau_add_gutenberg_assets' );

/*-----------------------------------------------------------------------------------*/
/* Remove Block Style from frontend as long wie dont use it
/*-----------------------------------------------------------------------------------*/
function fau_deregister_blocklibrary_styles() {
	global $is_gutenberg_enabled;

	if (!$is_gutenberg_enabled) {
		wp_dequeue_style( 'wp-block-library');
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wp-blocks-style' ); 
	}
}
add_action( 'wp_enqueue_scripts', 'fau_deregister_blocklibrary_styles', 100 );


/*
 * Note: Maybe test if gutenberg is enabled first.
 *   $is_gutenberg_enabled = false;
 *   if(has_filter('is_gutenberg_enabled') {
 *       $is_gutenberg_enabled = apply_filters('is_gutenberg_enabled', false);
 *    }
 * with plugin https://gitlab.rrze.fau.de/rrze-webteam/rrze-writing/blob/master/RRZE/Writing/Editor/Editor.php
 */

/**
 * Check if Block Editor is active.
 * Must only be used after plugins_loaded action is fired.
 *
 * @return bool
 */
function fau_blockeditor_is_active() {
    // Gutenberg plugin is installed and activated.
	$gutenberg = ! ( false === has_filter( 'replace_editor', 'gutenberg_init' ) );

    // Block editor since 5.0.
    $block_editor = version_compare( $GLOBALS['wp_version'], '5.0.0', '>' );

    if ( ! $gutenberg && ! $block_editor ) {
        return false;
    }

    if ( fau_is_classic_editor_plugin_active() ) {
        $editor_option       = get_option( 'classic-editor-replace' );
        $block_editor_active = array( 'no-replace', 'block' );
	    return in_array( $editor_option, $block_editor_active, true );
    }
    if (fau_is_newsletter_plugin_active()) {
	return true;
    }
    return false;
}

/**
 * Check if Classic Editor plugin is active.
 *
 * @return bool
 */
function fau_is_classic_editor_plugin_active() {
	
    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    if ( is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
        return true;
    }

    return false;
}

/*
 * Check if our Block Editor based Newsletter Plugin is active
 */
function fau_is_newsletter_plugin_active() {
    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    if ( is_plugin_active( 'rrze-newsletter/rrze-newsletter.php' ) ) {
        return true;
    }

    return false;
}



/*-----------------------------------------------------------------------------------*/
/* Outside-box image post block
/*-----------------------------------------------------------------------------------*/
function fau_custom_image_blocks() {
    wp_register_script(
        'my-custom-blocks',
        get_template_directory_uri() . '/js/fau-costum-image-block.min.js',
        array( 'wp-blocks', 'wp-editor' ),
        true
    );
    register_block_type( 'my-blocks/full-width-image', array(
        'editor_script' => 'my-custom-blocks',
    ) );
}
add_action( 'init', 'fau_custom_image_blocks' );


/*-----------------------------------------------------------------------------------*/
/* This is the end of the code as we know it
/*-----------------------------------------------------------------------------------*/



/*-----------------------------------------------------------------------------------*/
/* Defined allowed core block types if theme is used in Gutenberg Block Editor
/*-----------------------------------------------------------------------------------*/
/* 
 * TODO: 
 * Wir mussen das andersrum machen, da wir die Liste der erlaubten Typen nicht alle kennen: 
 * Es können durch Plugins andere hinzukommen, die wir bearbeitbar lassen wollen.
 * Daher andersUm
 * Array eingeben der Typen, die wir verbieten wollen.
 * Diese gegen eine Liste matchen, die alle Typen enthält.
 * Und von der Gesamatliste eben die verbotenenen Typen abziehen

function fau_allowed_block_types( $allowed_block_types, $post ) {
	global $is_gutenberg_enabled;

	if ($is_gutenberg_enabled) {
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
}

// add_filter( 'allowed_block_types', 'fau_allowed_block_types', 10, 2 );
 
 */