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

function fau_editor_settings() {
    if (fau_blockeditor_is_active()) {
        // we handle the Block Editor settings with the hook
        // enqueue_block_editor_assets
        return;
    }

    // Disable color palette.
    add_theme_support('editor-color-palette');

    // Disable color picker.
    add_theme_support('disable-custom-colors');

    // Dont allow font sizes of gutenberg
    // https://wordpress.org/gutenberg/handbook/extensibility/theme-support/#block-font-sizes
    add_theme_support('disable-custom-font-sizes');

    // allow responsive embedded content
    add_theme_support('responsive-embeds');

    // Remove Gutenbergs Userstyle and SVGs Duotone injections from 5.9.2
    remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
    remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
 
    
}
add_action('after_setup_theme', 'fau_editor_settings');

/*-----------------------------------------------------------------------------------*/
/* Activate scripts and style for backend use of Gutenberg
/*-----------------------------------------------------------------------------------*/
function fau_add_gutenberg_assets() {
    // Load the theme styles within Gutenberg.
    global $is_gutenberg_enabled;
    global $defaultoptions;

  //  if (fau_blockeditor_is_active()) {

        // Add support for editor styles.
        add_theme_support('editor-styles');

        // Enqueue the editor stylesheet.
        add_editor_style('css/fau-theme-blockeditor.css');

        $theme_data = wp_get_theme();
        $theme_version = $theme_data->Version;

        // Enqueue the JS needed for deregistering Block Variations
        wp_enqueue_script(
            'fau-blockeditor-styles-unregister',
            $defaultoptions['src-blockstyleregisterjs'],
            array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
            $theme_version,
            true
        );
 //   }
}
add_action('enqueue_block_editor_assets', 'fau_add_gutenberg_assets');

/*-----------------------------------------------------------------------------------*/
/* Activate scripts and style for backend use of Classic Editor
/*-----------------------------------------------------------------------------------*/
function fau_add_classic_editor_assets() {
  //  if (fau_blockeditor_is_active()) {
        // we handle the Block Editor settings with the hook
        // enqueue_block_editor_assets
  //      return;
  //  }

    // check if the classic editor is active
    if (fau_is_classic_editor_plugin_active()) {
         // Add support for editor styles.
        add_theme_support('editor-styles');
        // Enqueue the classic editor stylesheet.
        add_editor_style('css/fau-theme-classiceditor.css');
    }
}
add_action('admin_init', 'fau_add_classic_editor_assets');

/*-----------------------------------------------------------------------------------*/
/* Remove Block Style from frontend as long wie dont use it
/*-----------------------------------------------------------------------------------*/
function fau_deregister_blocklibrary_styles() {
    if (!fau_blockeditor_is_active()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wp-blocks-style');
    }
}
add_action('wp_enqueue_scripts', 'fau_deregister_blocklibrary_styles', 100);


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
    } elseif (fau_is_classic_editor_plugin_active()) {
        $editor_option       = get_option('classic-editor-replace');
        $block_editor_active = array('no-replace', 'block');
        $is_gutenberg_enabled = in_array($editor_option, $block_editor_active, true);
    } elseif (fau_is_newsletter_plugin_active()) {
        $is_gutenberg_enabled = true;
    } else {

        $rrze_settings_option       = get_option('rrze_settings');
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

            $editor_option       = get_option('classic-editor-replace');
            $block_editor_active = array('no-replace', 'block');
            if (in_array($editor_option, $block_editor_active)) {
                $is_gutenberg_enabled = true;
            }
        }
    }

    if ($is_gutenberg_enabled) {
        add_filter('is_gutenberg_enabled', 'fau_set_filter_gutenberg_state');
    }
    return $is_gutenberg_enabled;
}

/*-----------------------------------------------------------------------------------*/
/* Set is_gutenberg_enabled filter if not avaible
/*-----------------------------------------------------------------------------------*/
function fau_set_filter_gutenberg_state($value) {
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

    if (! function_exists('is_plugin_active')) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    if (is_plugin_active('classic-editor/classic-editor.php')) {
        return true;
    }

    return false;
}

/*-----------------------------------------------------------------------------------*/
/* Check if our Block Editor based Newsletter Plugin is active
/*-----------------------------------------------------------------------------------*/
function fau_is_newsletter_plugin_active() {
    if (! function_exists('is_plugin_active')) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    if (is_plugin_active('rrze-newsletter/rrze-newsletter.php')) {
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
        array('wp-blocks', 'wp-editor'),
        true
    );
    register_block_type('my-blocks/full-width-image', array(
        'editor_script' => 'my-custom-blocks',
    ));
}
// add_action( 'init', 'fau_custom_image_blocks' );

/*-----------------------------------------------------------------------------------*/
/* This is the end of the code as we know it
/*-----------------------------------------------------------------------------------*/
