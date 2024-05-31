<?php
/**
* @package WordPress
* @subpackage FAU
* @since FAU 1.10
*/
use \RRZE\THEME\EINRICHTUNGEN\Debugging;

/*-----------------------------------------------------------------------------------*/
/* We use our own color set in this theme and dont want autors to change text colors
/*-----------------------------------------------------------------------------------*/
function fau_gutenberg_settings() {
    if (fau_blockeditor_is_active()) {
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

	if (fau_blockeditor_is_active()) {
		wp_enqueue_style( 'fau-gutenberg', get_theme_file_uri( '/css/fau-theme-gutenberg.css' ), false );
	}
}
// add_action( 'enqueue_block_editor_assets', 'fau_add_gutenberg_assets' );
// derzeit noch keine eigene CSS File mit Anpassungen...

/*-----------------------------------------------------------------------------------*/
/* Remove Block Style from frontend as long wie dont use it
/*-----------------------------------------------------------------------------------*/
function fau_deregister_blocklibrary_styles() {
	if (!fau_blockeditor_is_active()) {
		wp_dequeue_style( 'wp-block-library');
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wp-blocks-style' ); 
	}
}
add_action( 'wp_enqueue_scripts', 'fau_deregister_blocklibrary_styles', 100 );


/*-----------------------------------------------------------------------------------*/
/* Check if Block Editor is active.
/* Must only be used after plugins_loaded action is fired.
/*
/* @return bool
/*-----------------------------------------------------------------------------------*/
function fau_blockeditor_is_active() {    
    global $is_gutenberg_enabled;
    $is_gutenberg_enabled = false;

    
    if (has_filter('is_gutenberg_enabled')) {
        $is_gutenberg_enabled = apply_filters('is_gutenberg_enabled', false);
        if ($is_gutenberg_enabled) {
            return true;
        }
        // No public function yet.
//    } elseif (class_exists('RRZE\Settings\Main')) {
//        if (RRZE\Settings\Main::isClassicEditorEnabled()) {
//            Debugging::log("Info",  "RRZE\Settings\Writing says no");
//            $is_gutenberg_enabled = false;
//        } else {
//              Debugging::log("Info",  "RRZE\Settings\Writing says yes");
//            $is_gutenberg_enabled = true;
//        }
  
        
    } elseif ( fau_is_classic_editor_plugin_active() ) {
        $editor_option       = get_option( 'classic-editor-replace' );
        $block_editor_active = array( 'no-replace', 'block' );
        $is_gutenberg_enabled = in_array( $editor_option, $block_editor_active, true );       
          
    } elseif (fau_is_newsletter_plugin_active()) {
        $is_gutenberg_enabled = true;
                       
    } else {
        
        $rrze_settings_option       = get_option( 'rrze_settings' );
        if (isset($rrze_settings_option->writing)) {
             if (isset($rrze_settings_option->writing->enable_classic_editor)) {
                if ($rrze_settings_option->writing->enable_classic_editor === 1) {
                    $is_gutenberg_enabled = false;
                } else {
                     $is_gutenberg_enabled = true;
                }
            } elseif (isset($rrze_settings_option->writing->enable_block_editor)) {
                if ($rrze_settings_option->writing->enable_block_editor == 1) {
                    $is_gutenberg_enabled = true;
                }
            }
        } else {
        
        
            $editor_option       = get_option( 'classic-editor-replace' );
            $block_editor_active = array( 'no-replace', 'block' );
            if (in_array( $editor_option, $block_editor_active )) {
                 $is_gutenberg_enabled = true;
            }
        
        }
        
    }

    if ($is_gutenberg_enabled ) {
         add_filter( 'is_gutenberg_enabled', 'fau_set_filter_gutenberg_state' );
    }
    return $is_gutenberg_enabled;
}

/*-----------------------------------------------------------------------------------*/
/* Set is_gutenberg_enabled filter if not avaible
/*-----------------------------------------------------------------------------------*/
function fau_set_filter_gutenberg_state( $value ) {
    global $is_gutenberg_enabled;
    $is_gutenberg_enabled = true;
    
    return $is_gutenberg_enabled;
}
/*-----------------------------------------------------------------------------------*/
/* Check if Classic Editor plugin is active.
/*
/* @return bool
/*-----------------------------------------------------------------------------------*/
function fau_is_classic_editor_plugin_active() {
	
    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    if ( is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
        return true;
    }

    return false;
}

/*-----------------------------------------------------------------------------------*/
/* Check if our Block Editor based Newsletter Plugin is active
/*-----------------------------------------------------------------------------------*/
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
// add_action( 'init', 'fau_custom_image_blocks' );





/*-----------------------------------------------------------------------------------*/
/* This is the end of the code as we know it
/*-----------------------------------------------------------------------------------*/
