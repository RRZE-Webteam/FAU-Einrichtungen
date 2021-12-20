<?php
/**
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.1
 */

global $defaultoptions, $default_fau_orga_data, $fau_used_svgs;

load_theme_textdomain( 'fau', get_template_directory() . '/languages' );
require_once( get_template_directory() . '/functions/template-functions.php' );

require_once( get_template_directory() . '/functions/defaults.php' );
require_once( get_template_directory() . '/functions/constants.php' );
require_once( get_template_directory() . '/functions/sanitizer.php' );
require_once( get_template_directory() . '/functions/customizer.php');


$options = fau_initoptions();
$is_sidebar_active = false;

require_once( get_template_directory() . '/functions/plugin-support.php' );
require_once( get_template_directory() . '/functions/helper-functions.php' );
require_once( get_template_directory() . '/functions/embeddings.php');

require_once( get_template_directory() . '/functions/shortcodes.php');

require_once( get_template_directory() . '/functions/menu.php');
require_once( get_template_directory() . '/functions/custom-fields.php' );
require_once( get_template_directory() . '/functions/posttype_imagelink.php' );
require_once( get_template_directory() . '/functions/widgets.php' );
require_once( get_template_directory() . '/functions/gallery.php' );

// require_once( get_template_directory() . '/functions/gutenberg.php');
// deaktiviert wegen Newsletter-Plugin
// brauchen noch ein andere Lösung

require_once( get_template_directory() . '/functions/svglib.php');

require_once( get_template_directory() . '/functions/deprecated.php');

// Filter-Hooks
require_once( get_template_directory() . '/functions/filters.php');


function fau_setup() {
	global $defaultoptions;
	 
	if ( ! isset( $content_width ) ) $content_width = $defaultoptions['content-width'];
	
	    
	add_editor_style( array( 'css/fau-theme-editor-style.css' ) );
	add_theme_support('html5');
	add_theme_support('caption');
	add_theme_support('title-tag');
	add_theme_support('automatic-feed-links');

	
	fau_register_menus();
	    // Register Menus
	fau_create_socialmedia_menu();
	    // Checkup Social Media Menu
	
	
	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
		
	
	/* Image Sizes for Slider, Name: hero - 1260:350, true */
	add_image_size( 'hero', $defaultoptions['slider-image-width'], $defaultoptions['slider-image-height'], $defaultoptions['slider-image-crop']);	
	
	/* Banner fuer Startseiten, Name: herobanner -  1260:182, true */
	add_image_size( 'herobanner', $defaultoptions['default_startseite-bannerbild-image_width'], $defaultoptions['default_startseite-bannerbild-image_height'], $defaultoptions['default_startseite-bannerbild-image_crop']);	

	
	/* RWD-Bildauflösung: 480x240. , 2:1 Proportion. No Crop */
	add_image_size( 'rwd-480-2-1', $defaultoptions[ 'default_rwdimage_2-1_width'], $defaultoptions['default_rwdimage_2-1_height'], $defaultoptions['default_rwdimage_2-1_crop']);
	    
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size($defaultoptions[ 'default_rwdimage_2-1_width'], $defaultoptions['default_rwdimage_2-1_height'], $defaultoptions['default_rwdimage_2-1_crop'] ); 


	/* RWD-Bildauflösung: 480x320. , 3:2 Proportion. No Crop */
	add_image_size( $defaultoptions['default_rwdimage_typname'], $defaultoptions['default_rwdimage_width'], $defaultoptions['default_rwdimage_height'], $defaultoptions['default_rwdimage_crop']);
	
	
	/* 
	 * Größen für Bildergalerien: 
	 */
	/* Images for gallerys - Name: gallery-full */
	add_image_size( 'gallery-full', $defaultoptions['default_gallery_full_width'], $defaultoptions['default_gallery_full_height'], $defaultoptions['default_gallery_full_crop']); // 940, 470, false
	


}
add_action( 'after_setup_theme', 'fau_setup' );


/*-----------------------------------------------------------------------------------*/
/* Set extra init values
/*-----------------------------------------------------------------------------------*/
function fau_custom_init() {
    /* Keine verwirrende Abfrage nach Kommentaren im Page-Editor */
    remove_post_type_support( 'page', 'comments' );

    /* 
     * ToDO: Remove this, once the Settings Plugin is capable to do this
    */
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
   
    remove_filter( 'the_content', 'wpautop' );

    // Declare Default Symbols from the start
    fau_register_svg_symbol("fau-logo-text", false);
   // fau_register_svg_symbol("fau-siegel", false);
    fau_register_svg_symbol("fau-logo", false);

    
}
add_action( 'init', 'fau_custom_init' );


/*-----------------------------------------------------------------------------------*/
/* Enqueues scripts and styles for front end.
/*-----------------------------------------------------------------------------------*/
function fau_register_scripts() {
    global $defaultoptions;
    
    $theme_data = wp_get_theme();
    $theme_version = $theme_data->Version;

    wp_register_style('fau-style',  get_stylesheet_uri(), array(), $theme_version);
	// Base Style
    wp_register_script('fau-scripts', $defaultoptions['src-scriptjs'], array('jquery'), $theme_version, true );
	// Base FAU Scripts
    wp_register_script('fau-js-heroslider', $defaultoptions['src-sliderjs'], array('jquery'), $theme_version, true );
	// Slider JS
	
}
add_action('init', 'fau_register_scripts');

/*-----------------------------------------------------------------------------------*/
/* Activate base scripts
/*-----------------------------------------------------------------------------------*/
function fau_basescripts_styles() {
    wp_enqueue_style( 'fau-style');	
    wp_enqueue_script( 'fau-scripts');

}
add_action( 'wp_enqueue_scripts', 'fau_basescripts_styles' );

/*-----------------------------------------------------------------------------------*/
/* Activate scripts depending on use
/*-----------------------------------------------------------------------------------*/
function fau_enqueuefootercripts() {
    global $usejslibs;
     
     if ((isset($usejslibs['heroslider']) && ($usejslibs['heroslider'] == true))) {
	 // wird bei Startseite Slider und auch bei gallerien verwendet
	 wp_enqueue_script('fau-js-heroslider');
     }	   
}
add_action( 'wp_footer', 'fau_enqueuefootercripts' );

/*-----------------------------------------------------------------------------------*/
/* Activate scripts and style for backend use
/*-----------------------------------------------------------------------------------*/
function fau_admin_header_style() {
    global $defaultoptions;
    wp_register_style( 'themeadminstyle', get_fau_template_uri().'/css/fau-theme-admin.css' );	   
    wp_enqueue_style( 'themeadminstyle' );	
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-datepicker');
    
    $theme_data = wp_get_theme();
    $theme_version = $theme_data->Version;
    wp_register_script('themeadminscripts', $defaultoptions['src-adminjs'], array('jquery'),$theme_version);    
    wp_enqueue_script('themeadminscripts');	   
}
add_action( 'admin_enqueue_scripts', 'fau_admin_header_style' );



/*-----------------------------------------------------------------------------------*/
/* Remove type-String from link-reference to follow W3C Validator
/*-----------------------------------------------------------------------------------*/
function fau_remove_type_attr($tag, $handle) {
    return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
}
add_filter('style_loader_tag', 'fau_remove_type_attr', 10, 2);
add_filter('script_loader_tag', 'fau_remove_type_attr', 10, 2);

/*-----------------------------------------------------------------------------------*/
/* Change default header
/*-----------------------------------------------------------------------------------*/
function fau_addmetatags() {
    global $defaultoptions;
    $output = '';
   // $output .= '<meta http-equiv="Content-Type" content="text/html; charset='.get_bloginfo('charset').'">'."\n";
   // $output .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">'."\n";    

    $output .= fau_get_rel_alternate();
    
    $googleverification = get_theme_mod('google-site-verification');
    if ((isset( $googleverification )) && ( !fau_empty($googleverification) )) {
        $output .= '	<meta name="google-site-verification" content="'.$googleverification.'">'."\n";
    }

    if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {    
	    $output .=  '   <link rel="shortcut icon" href="'.get_fau_template_uri().'/img/socialmedia/favicon.ico">'."\n";
	    $output .=  '   <link rel="apple-touch-icon" sizes="180x180" href="'.get_fau_template_uri().'/img/socialmedia/favicon-180x180.png">'."\n";
	//    $output .=  '   <link rel="icon" type="image/png" sizes="32x32" href="'.get_fau_template_uri().'/img/socialmedia/favicon-32x32.png">'."\n";
	    $output .=  '   <link rel="icon" type="image/png" sizes="180x180" href="'.get_fau_template_uri().'/img/socialmedia/favicon-180x180.png">'."\n";
	    $output .=  '   <link rel="icon" type="image/svg+xml" href="'.get_fau_template_uri().'/img/socialmedia/favicon.svg" sizes="any">'."\n";
	    $output .=  '   <link rel="mask-icon" type="image/svg+xml" href="'.get_fau_template_uri().'/img/socialmedia/favicon-mask.svg" color="'.$defaultoptions['default-social-media-color'].'">'."\n";
	    $output .=  '   <meta name="msapplication-TileColor" content="'.$defaultoptions['default-social-media-color'].'">'."\n";
	    $output .=  '   <meta name="msapplication-TileImage" content="'.get_fau_template_uri().'/img/socialmedia/favicon-180x180.png">'."\n";
	    $output .=  '   <meta name="theme-color" content="'.$defaultoptions['default-social-media-color'].'">'."\n";
  
    }
    
    	// Adds RSS feed links to <head> for posts and comments.
	// add_theme_support( 'automatic-feed-links' );
	// Will post both: feed and comment feed; To use only main rss feed, i have to add it manually in head
    
    $title = sanitize_text_field(get_bloginfo( 'name' ));
    $output .= '    <link rel="alternate" type="application/rss+xml" title="'.$title.' - RSS 2.0 Feed" href="'.get_bloginfo( 'rss2_url').'">'."\n";
    
    if ($defaultoptions['default-sourcecode-notice']) {
	$output .= '	<!-- '.$defaultoptions['default-sourcecode-notice-text'].' -->'."\n";
    }
    
    
    echo $output;
}
add_action('wp_head', 'fau_addmetatags',1);


/*-----------------------------------------------------------------------------------*/
/* Change default DNS prefetch
/*-----------------------------------------------------------------------------------*/
function fau_remove_default_dns_prefetch( $hints, $relation_type ) {
    if ( 'dns-prefetch' === $relation_type ) {
        return array_diff( wp_dependencies_unique_hosts(), $hints );
    }

    return $hints;
}
add_filter( 'wp_resource_hints', 'fau_remove_default_dns_prefetch', 10, 2 );

function fau_dns_prefetch() {
    // List of domains to set prefetching for
    $prefetchDomains = [ 'https://www.fau.de'  ];
    $mydomain = parse_url(get_home_url());
    $prefetchDomains[] = $mydomain['host'];
	
	
    $prefetchDomains = array_unique($prefetchDomains);
    $result = '';
 
    foreach ($prefetchDomains as $domain) {
        $domain = esc_url($domain);
        $result .= '	<link rel="dns-prefetch" href="' . $domain . '" crossorigin />'."\n";
        $result .= '	<link rel="preconnect" href="' . $domain . '" crossorigin />'."\n";
    }
 
    echo $result;
}
add_action('wp_head', 'fau_dns_prefetch', 10);


/*-----------------------------------------------------------------------------------*/
/*  Remove something out of the head
/*-----------------------------------------------------------------------------------*/
function fau_remove_unwanted_head_actions() {
	remove_action( 'wp_head', 'post_comments_feed_link ', 2 ); 
	    // Display the links to the general feeds: Post and Comment Feed

	remove_action( 'wp_head', 'wlwmanifest_link' ); 
	    // Display the link to the Windows Live Writer manifest file.
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); 
	    // remove prev link

	remove_action( 'wp_head', 'rest_output_link_wp_head');
	    // removes link <link rel='https://api.w.org/' ..> 
	
	remove_action( 'wp_head', 'rsd_link' ); 
	    // Display the link to the Really Simple Discovery service endpoint, EditURI link
	remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); 
	    // remove Display relational links for the posts adjacent to the current post.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links'         ); 
	    // remove oEmbed discovery links in the website 


	if (!is_user_logged_in()) {
	    // remove admin settings in footer if not logged in
	    remove_action('wp_footer', 'wp_admin_bar_render', 1000 );
	    remove_action('wp_head', '_admin_bar_bump_cb');
	    remove_action('wp_head', 'wp_admin_bar_header');
	}

}
add_action('wp_head', 'fau_remove_unwanted_head_actions', 0);

/*-----------------------------------------------------------------------------------*/
/*Custom Loo setup
/*-----------------------------------------------------------------------------------*/
function fau_custom_logo_setup() {
    global $defaultoptions;
    $defaults = array(
        'height'               => $defaultoptions['default_logo_height'],
        'width'                => $defaultoptions['default_logo_width'],
        'flex-height'          => true,
        'flex-width'           => true,
        'unlink-homepage-logo' => true, 
    );
 
    add_theme_support( 'custom-logo', $defaults );
}
add_action( 'after_setup_theme', 'fau_custom_logo_setup' );

/*-----------------------------------------------------------------------------------*/
/*  Returns language code, without subcode
/*-----------------------------------------------------------------------------------*/
function fau_get_language_main () {
    $charset = explode('-',get_bloginfo('language'))[0];
    return $charset;
}


/*-----------------------------------------------------------------------------------*/
/* Change WordPress default language attributes function to 
 * strip of region code parts. Not used yet /anymore
/*-----------------------------------------------------------------------------------*/
function fau_get_language_attributes ($doctype = 'html' ) {
    $attributes = array();
	
    if ( function_exists( 'is_rtl' ) && is_rtl() )
	    $attributes[] = 'dir="rtl"';
    
    if ( $langcode = fau_get_language_main() ) {
	    if ( get_option('html_type') == 'text/html' || $doctype == 'html' )
		    $attributes[] = "lang=\"$langcode\"";

	    if ( get_option('html_type') != 'text/html' || $doctype == 'xhtml' )
		    $attributes[] = "xml:lang=\"$langcode\"";
    }	
    $output = implode(' ', $attributes);
    return $output;
}



/*-----------------------------------------------------------------------------------*/
/* Load Comment Functions
/*-----------------------------------------------------------------------------------*/
require get_template_directory() . '/functions/comments.php';

/*-----------------------------------------------------------------------------------*/
/* Limit Number of Posts for embedded format
/*-----------------------------------------------------------------------------------*/
add_action( 'pre_get_posts', 'fau_embedded_posts' );
function fau_embedded_posts( $query ) {
    if (isset($_GET['format']) && $_GET['format'] == 'embedded') {
        $query->set( 'numberposts', 3 );
        $query->set( 'posts_per_page', 3 );
    }
}

/*-----------------------------------------------------------------------------------*/
/* This is the end of the code as we know it
/*-----------------------------------------------------------------------------------*/



