<?php
/**
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.1
 */

global $defaultoptions, $default_fau_orga_data, $fau_used_svgs;
global $is_gutenberg_enabled;

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



require_once( get_template_directory() . '/functions/svglib.php');

require_once( get_template_directory() . '/functions/deprecated.php');

// Filter-Hooks
require_once( get_template_directory() . '/functions/filters.php');

/*-----------------------------------------------------------------------------------*/
/* Setup theme
/*-----------------------------------------------------------------------------------*/
function fau_setup() {
	global $defaultoptions;

	if (!isset( $content_width ) ) {
            $content_width = $defaultoptions['content-width'];
        }


	add_theme_support('html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ));
	add_theme_support('caption');
	add_theme_support('title-tag');
	add_theme_support('automatic-feed-links');


	fau_register_menus();
	    // Register Menus
	fau_create_socialmedia_menu();
	    // Checkup Social Media Menu

	
	global $is_gutenberg_enabled;
	if (has_filter('is_gutenberg_enabled')) {
		$is_gutenberg_enabled = apply_filters('is_gutenberg_enabled', false);
	} else {
	    $is_gutenberg_enabled = fau_is_newsletter_plugin_active();
	}

}
add_action( 'after_setup_theme', 'fau_setup' );

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
/* Set image sizes
/*-----------------------------------------------------------------------------------*/
function fau_set_image_sizes() {
    global $defaultoptions;
     /* 
	 * Notice: Default Image Siztes dont neet to be defined:
	 * Reserved Image Size Names:
	 *  thumb = alias for thumbnail
	 *  thumbnail, default 150px x 150px max
	 *  post-thumbnail, 
	 *  medium, default 300px x 300px max
	 *  medium_large, default 768px x 0px max
	 *  large, default 1024px x 1024px max
	 *  full, unmodified
	 */
    foreach ($defaultoptions['default_image_sizes'] as $size => $value) {	
	switch ($size) {
	    case '_post_thumbnail':
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size($value[ 'width'], $value['height'], $value['crop']);
		break;
	    case '_thumb': 
	    case '_thumbnail': 
		// default values; tu nichts (derzeit)
		break;
	    default:
		add_image_size( $size, $value[ 'width'], $value['height'], $value['crop']);
		break;
	}
    } 
}
add_action( 'after_setup_theme', 'fau_set_image_sizes' );

/*-----------------------------------------------------------------------------------*/
/* Set extra init values
/*-----------------------------------------------------------------------------------*/
function fau_custom_init() {
    /* Keine verwirrende Abfrage nach Kommentaren im Page-Editor */
    remove_post_type_support( 'page', 'comments' );

    /*
     * Remove emojis
    */
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );



    remove_filter( 'the_content', 'wpautop' );
	// no auto p


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
	// Global Style. Notice: Its used also for print, so dont limit it to screen output!
    wp_register_style('fau-style-print', get_stylesheet_directory_uri() . '/print.css', array(), $theme_version, 'print' );
	// Base style for print
    wp_register_script('fau-scripts', $defaultoptions['src-scriptjs'], array('jquery'), $theme_version, true );
	// Base FAU scripts
    wp_register_script('fau-js-heroslider', $defaultoptions['src-sliderjs'], array('jquery'), $theme_version, true );
	// Slider JS
    wp_register_script('fau-js-printlinks', $defaultoptions['src-printlinks'], [], $theme_version, true );
	// Print links js

}
add_action('init', 'fau_register_scripts');

/*-----------------------------------------------------------------------------------*/
/* Activate base scripts
/*-----------------------------------------------------------------------------------*/
function fau_basescripts_styles() {
    wp_enqueue_style( 'fau-style');
    wp_enqueue_style( 'fau-style-print' );
    wp_enqueue_script( 'fau-js-printlinks' );
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
    $prefetchDomains = array();
    $mydomain = parse_url(get_home_url());
    $scheme = $mydomain['scheme'];
    
    $prefetchDomains[] = $mydomain['host'];
    // add own domain

     // check if own domain is a subdomain. if so, we also add the main domain above
    $host = explode('.', $mydomain['host']);
    if (($host) && (count($host)>1)) {
        $prefetchDomains[] = $scheme.'://'.$host[count($host)-2].'.'.$host[count($host)-1];
	
	 // check for third level
	if (count($host)>2) {
	    $prefetchDomains[] = $scheme.'://'.$host[count($host)-3].'.'.$host[count($host)-2].'.'.$host[count($host)-1];
	}
    }
   

    $prefetchDomains = array_unique($prefetchDomains);
    $result = '';

    foreach ($prefetchDomains as $domain) {
        $domain = esc_url($domain);
        $result .= '<link rel="dns-prefetch" href="' . $domain . '" crossorigin>'."\n";
	
	//  $result .= '<link rel="preconnect" href="' . $domain . '" crossorigin>'."\n";
	// We do not make a preconnect to all domains:
	// The preconnect hint is best used for only the most critical connections. 
	// For the others, just use <link rel="dns-prefetch"> to save time on 
	// the first step — the DNS lookup.
	// see also: https://developer.mozilla.org/en-US/docs/Web/Performance/dns-prefetch
    }

    echo $result;
}
add_action('wp_head', 'fau_dns_prefetch', 10);


/*-----------------------------------------------------------------------------------*/
/* Change default header
/*-----------------------------------------------------------------------------------*/
function fau_addmetatags() {
    $output = '';
   // $output .= '<meta http-equiv="Content-Type" content="text/html; charset='.get_bloginfo('charset').'">'."\n";
   // $output .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">'."\n";

    $output .= fau_get_rel_alternate();
	// get alternate urls for the website if avaible
    
    $googleverification = get_theme_mod('google-site-verification');
    if ((isset( $googleverification )) && ( !fau_empty($googleverification) )) {
        $output .= '<meta name="google-site-verification" content="'.$googleverification.'">'."\n";
	// if we set the Google Site Verification in the customizer, we add the html meta tag here 
    }



    $title = sanitize_text_field(get_bloginfo( 'name' ));
    $output .= '<link rel="alternate" type="application/rss+xml" title="'.$title.' - RSS 2.0 Feed" href="'.get_bloginfo( 'rss2_url').'">'."\n";
       	// Adds RSS feed links to <head> for posts and comments.
	// add_theme_support( 'automatic-feed-links' );
	// Will post both: feed and comment feed; To use only main rss feed, i have to add it manually in head
    
    
    
    echo $output;
    
    
    
}
add_action('wp_head', 'fau_addmetatags',1);

/*-----------------------------------------------------------------------------------*/
/* create favicon metas 
/*-----------------------------------------------------------------------------------*/
function fau_create_meta_favicon() {
    global $defaultoptions;
    $output = '';
    if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {    

	    $output .=  '<link rel="shortcut icon" href="'.get_fau_template_uri().'/img/socialmedia/favicon.ico">'."\n";
	    $output .=  '<link rel="apple-touch-icon" sizes="180x180" href="'.get_fau_template_uri().'/img/socialmedia/favicon-apple-touch.png">'."\n";
	    $output .=  '<link rel="icon" type="image/png" sizes="180x180" href="'.get_fau_template_uri().'/img/socialmedia/favicon-180x180.png">'."\n";
	    $output .=  '<link rel="icon" type="image/svg+xml" href="'.get_fau_template_uri().'/img/socialmedia/favicon.svg" sizes="any">'."\n";
	    $output .=  '<link rel="mask-icon" type="image/svg+xml" href="'.get_fau_template_uri().'/img/socialmedia/favicon-mask.svg" color="'.$defaultoptions['default-social-media-color'].'">'."\n";
	    $output .=  '<meta name="msapplication-TileColor" content="'.$defaultoptions['default-social-media-color'].'">'."\n";
	    $output .=  '<meta name="msapplication-TileImage" content="'.get_fau_template_uri().'/img/socialmedia/favicon-180x180.png">'."\n";

	    $output .=  '<meta name="theme-color" content="'.$defaultoptions['default-social-media-color'].'">'."\n";  
    }
     echo $output;

}
add_action('wp_head', 'fau_create_meta_favicon');
    // add favicon for frontend
add_action('admin_head', 'fau_create_meta_favicon');
    // add favicon for backend

/*-----------------------------------------------------------------------------------*/
/*  Add FAU Jobs advertisement
/*-----------------------------------------------------------------------------------*/
function fau_addmjobsad() {
    global $defaultoptions;

    if ($defaultoptions['default-sourcecode-notice']) {
	echo '<!-- '.$defaultoptions['default-sourcecode-notice-text'].' -->'."\n";
    }
}
add_action('wp_head', 'fau_addmjobsad',10);
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
/* Load Comment Functions
/*-----------------------------------------------------------------------------------*/

// Comment handling
require get_template_directory() . '/functions/comments.php';

// Block Editor handling
require_once( get_template_directory() . '/functions/gutenberg.php');


/*-----------------------------------------------------------------------------------*/
/* This is the end of the code as we know it
/*-----------------------------------------------------------------------------------*/






