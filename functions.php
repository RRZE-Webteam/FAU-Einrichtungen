<?php
/**
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.1
 */

load_theme_textdomain( 'fau', get_template_directory() . '/languages' );
require_once( get_template_directory() . '/functions/relative-urls.php');
require_once( get_template_directory() . '/functions/defaults.php' );
require_once( get_template_directory() . '/functions/constants.php' );
require_once( get_template_directory() . '/functions/sanitizer.php' );

$options = fau_initoptions();
require_once( get_template_directory() . '/functions/helper-functions.php' );
require_once( get_template_directory() . '/functions/template-functions.php' );
require_once( get_template_directory() . '/functions/theme-options.php' );     
require_once( get_template_directory() . '/functions/shortcodes.php');
require_once( get_template_directory() . '/functions/plugin-support.php' );
require_once( get_template_directory() . '/functions/menu.php');
require_once( get_template_directory() . '/functions/custom-fields.php' );
require_once( get_template_directory() . '/functions/posttype_imagelink.php' );
require_once( get_template_directory() . '/functions/posttype_ad.php' );
require_once( get_template_directory() . '/functions/widgets.php' );
require_once( get_template_directory() . '/functions/posttype-synonym.php');
require_once( get_template_directory() . '/functions/posttype-glossary.php');

function fau_setup() {
	global $options;
	

	if ( ! isset( $content_width ) ) $content_width = $options['content-width'];
	add_editor_style( array( 'css/editor-style.css' ) );
	add_theme_support( 'html5');
	add_theme_support('title-tag');

	
	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'meta', __( 'Meta-Navigation oben', 'fau' ) );
	register_nav_menu( 'meta-footer', __( 'Meta-Navigation unten', 'fau' ) );
	register_nav_menu( 'main-menu', __( 'Haupt-Navigation', 'fau' ) );
	
	if ($options['website_type']==-1) {
	    register_nav_menu( 'quicklinks-1', __( 'Startseite FAU Portal: Bühne Spalte 1', 'fau' ) );
	    register_nav_menu( 'quicklinks-2', __( 'Startseite FAU Portal: Bühne Spalte 2', 'fau' ) );
	    register_nav_menu( 'quicklinks-3', __( 'Startseite FAU Portal: Bühne Spalte 3', 'fau' ) );
	    register_nav_menu( 'quicklinks-4', __( 'Startseite FAU Portal: Bühne Spalte 4', 'fau' ) );
	} else {
	    register_nav_menu( 'quicklinks-3', __( 'Startseite Fakultät: Bühne Spalte 1', 'fau' ) );
	    register_nav_menu( 'quicklinks-4', __( 'Startseite Fakultät: Bühne Spalte 2', 'fau' ) );
	}
	register_nav_menu( 'error-1', __( 'Fehler- und Suchseite: Vorschlagmenu Spalte 1', 'fau' ) );
	register_nav_menu( 'error-2', __( 'Fehler- und Suchseite: Vorschlagmenu Spalte 2', 'fau' ) );
	register_nav_menu( 'error-3', __( 'Fehler- und Suchseite: Vorschlagmenu Spalte 3', 'fau' ) );
	register_nav_menu( 'error-4', __( 'Fehler- und Suchseite: Vorschlagmenu Spalte 4', 'fau' ) );
	
	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( $options['default_thumb_width'], $options['default_thumb_height'], $options['default_thumb_crop'] ); // 300:150:false
	
	/* Image Sizes for Slider, Name: hero */
	add_image_size( 'hero', $options['slider-image-width'], $options['slider-image-height'], $options['slider-image-crop']);	// 1260:350
	
	/* Banner fuer Startseiten */
	add_image_size( 'herobanner', $options['default_startseite-bannerbild-image_width'], $options['default_startseite-bannerbild-image_height'], $options['default_startseite-bannerbild-image_crop']);	// 1260:182
    
	/* Thumb for Posts in Lists - Name: post-thumb */
	add_image_size( 'post-thumb', $options['default_postthumb_width'], $options['default_postthumb_height'], $options['default_postthumb_crop']); // 3:2  220:147, false
	
	/* Thumb of Topevent in Sidebar - Name: topevent-thumb */
	add_image_size( 'topevent-thumb', $options['default_topevent_thumb_width'], $options['default_topevent_thumb_height'], $options['default_topevent_thumb_crop']); // 140:90, true
	
	/* Thumb for Image Menus in Content - Name: page-thumb */
	add_image_size( 'page-thumb', $options['default_submenuthumb_width'], $options['default_submenuthumb_height'],  $options['default_submenuthumb_crop']); // 220:110, true
	
	/* Thumb for Posts, displayed in post/page single display - Name: post */
	add_image_size( 'post', $options['default_post_width'], $options['default_post_height'], $options['default_post_crop']);  // 300:200  false
	
	    
	
	/* Thumb for Logos (used in carousel) - Name: logo-thumb */
	add_image_size( 'logo-thumb', $options['default_logo_carousel_width'], $options['default_logo_carousel_height'], $options['default_logo_carousel_crop']);   // 140:110, true

	/* 
	 * Größen für Bildergalerien: 
	 */
	/* Images for gallerys - Name: gallery-full */
	add_image_size( 'gallery-full', $options['default_gallery_full_width'], $options['default_gallery_full_height'], $options['default_gallery_full_crop']); // 940, 470, false
	//
	// Wird bei Default-Galerien verwendet als ANzeige des großen Bildes.
	add_image_size( 'gallery-thumb', $options['default_gallery_thumb_width'], $options['default_gallery_thumb_height'], $options['default_gallery_thumb_crop']); // 120, 80, true

	/* Grid-Thumbs for gallerys - Name: gallery-grid */
	add_image_size( 'gallery-grid', $options['default_gallery_grid_width'], $options['default_gallery_grid_height'], $options['default_gallery_grid_crop']); // 145, 120, false
	
	/* 2 column Imagelists for gallerys - Name: image-2-col */
	add_image_size( 'image-2-col', $options['default_gallery_grid2col_width'], $options['default_gallery_grid2col_height'], $options['default_gallery_grid2col_crop']); // 300, 200, true
	
	/* 4 column Imagelists for gallerys - Name: image-4-col */
	add_image_size( 'image-4-col', $options['default_gallery_grid4col_width'], $options['default_gallery_grid4col_height'], $options['default_gallery_grid4col_crop']);	// 140, 70, true


	
	
	
	/* Remove something out of the head */
	// remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
	// remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed	
	remove_action( 'wp_head', 'post_comments_feed_link ', 2 ); // Display the links to the general feeds: Post and Comment Feed
	remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
	remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
	// remove_action( 'wp_head', 'index_rel_link' ); // index link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
	remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
	//remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0);

	

}
add_action( 'after_setup_theme', 'fau_setup' );


/*-----------------------------------------------------------------------------------*/
/* Set extra init values
/*-----------------------------------------------------------------------------------*/
function fau_custom_init() {
    /* Keine verwirrende Abfrage nach Kommentaren im Page-Editor */
    remove_post_type_support( 'page', 'comments' );

    /* Disable Emojis */
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

}
add_action( 'init', 'fau_custom_init' );

/*-----------------------------------------------------------------------------------*/
/* Enqueues scripts and styles for front end.
/*-----------------------------------------------------------------------------------*/
function fau_register_scripts() {
    global $defaultoptions;
    global $options;
    
    wp_register_script( 'fau-scripts', $defaultoptions['src-scriptjs'], array('jquery'), $options['version'], true );
    wp_register_script( 'fau-libs-jquery-flexslider', get_fau_template_uri() . '/js/libs/jquery.flexslider.js', array('jquery'), $options['version'], true );
	// Flexslider für Startseite und für Galerien.     
    wp_register_script( 'fau-libs-jquery-caroufredsel', get_fau_template_uri() . '/js/libs/jquery.caroufredsel.js', array('jquery'), $options['version'], true );
    wp_register_script( 'fau-js-caroufredsel', get_fau_template_uri() . '/js/usecaroufredsel.min.js', array('jquery','fau-libs-jquery-caroufredsel'), $options['version'], true );
	// Slidende Logos 
}
add_action('init', 'fau_register_scripts');


/*-----------------------------------------------------------------------------------*/
/* Activate base scripts
/*-----------------------------------------------------------------------------------*/
function fau_basescripts_styles() {
    global $defaultoptions;
    global $usejslibs;
    global $options;
    
    wp_enqueue_style( 'fau-style', get_stylesheet_uri(), array(), $options['version'] );	
    wp_enqueue_script( 'fau-scripts');

}
add_action( 'wp_enqueue_scripts', 'fau_basescripts_styles' );

/*-----------------------------------------------------------------------------------*/
/* Activate scripts depending on use
/*-----------------------------------------------------------------------------------*/
function fau_enqueuefootercripts() {
    global $options;
    global $usejslibs;
   
    
     if ((isset($usejslibs['flexslider']) && ($usejslibs['flexslider'] == true))) {
	 // wird bei Startseite Slider und auch bei gallerien verwendet
	wp_enqueue_script('fau-libs-jquery-flexslider');	     
     }	 

     if ((isset($usejslibs['caroufredsel']) && ($usejslibs['caroufredsel'] == true))) {
	// wird bei Logo-Menus verwendet
	wp_enqueue_script('fau-libs-jquery-caroufredsel');
	wp_enqueue_script('fau-js-caroufredsel');
    }
}

add_action( 'wp_footer', 'fau_enqueuefootercripts' );

/*-----------------------------------------------------------------------------------*/
/* Activate scripts and style for backend use
/*-----------------------------------------------------------------------------------*/
function fau_admin_header_style() {
    wp_register_style( 'themeadminstyle', get_fau_template_uri().'/css/admin.css' );	   
    wp_enqueue_style( 'themeadminstyle' );	
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-datepicker');
    wp_register_script('themeadminscripts', get_fau_template_uri().'/js/admin.min.js', array('jquery'));    
    wp_enqueue_script('themeadminscripts');	   
}
add_action( 'admin_enqueue_scripts', 'fau_admin_header_style' );

/*-----------------------------------------------------------------------------------*/
/* Change default header
/*-----------------------------------------------------------------------------------*/
function fau_addmetatags() {
    global $options;

    $output = "";
    $output .= '<meta http-equiv="Content-Type" content="text/html; charset='.get_bloginfo('charset').'">'."\n";
    $output .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">'."\n";    

    $output .= fau_get_rel_alternate();
            
    if ((isset( $options['google-site-verification'] )) && ( strlen(trim($options['google-site-verification']))>1 )) {
        $output .= '<meta name="google-site-verification" content="'.$options['google-site-verification'].'">'."\n";
    }

    if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {
	    $output .=  '<link rel="apple-touch-icon" href="'.get_fau_template_uri().'/img/apple-touch-icon.png">'."\n";
	    $output .=  '<link rel="icon" href="'.get_fau_template_uri().'/img/apple-touch-icon.png">'."\n";
	    $output .=  '<link rel="icon" href="'.get_fau_template_uri().'/img/apple-touch-icon-120x120.png" sizes="120x120">'."\n";
	    $output .=  '<link rel="icon" href="'.get_fau_template_uri().'/img/apple-touch-icon-152x152.png" sizes="152x152">'."\n";
	    $output .=  '<link rel="shortcut icon" href="'.get_fau_template_uri().'/img/favicon.ico">'."\n";	
    }
    
    	// Adds RSS feed links to <head> for posts and comments.
	// add_theme_support( 'automatic-feed-links' );
	// Will post both: feed and comment feed; To use only main rss feed, i have to add it manually in head
    
    $title = sanitize_text_field(get_bloginfo( 'name' ));
    $output .= '<link rel="alternate" type="application/rss+xml" title="'.$title.' - RSS 2.0 Feed" href="'.get_bloginfo( 'rss2_url').'">'."\n";
    
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
    $prefetchDomains = [
        '//www.fau.de',
        '//www.fau.eu',
    ];
 
    $prefetchDomains = array_unique($prefetchDomains);
    $result = '';
 
    foreach ($prefetchDomains as $domain) {
        $domain = esc_url($domain);
        $result .= '<link rel="dns-prefetch" href="' . $domain . '" crossorigin />';
        $result .= '<link rel="preconnect" href="' . $domain . '" crossorigin />';
    }
 
    echo $result;
}
add_action('wp_head', 'fau_dns_prefetch', 0);


/*-----------------------------------------------------------------------------------*/
/* Change default title
/*-----------------------------------------------------------------------------------*/
function fau_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Seite %s', 'fau' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'fau_wp_title', 10, 2 );

/*-----------------------------------------------------------------------------------*/
/* Resets the Excerpt More
/*-----------------------------------------------------------------------------------*/
function fau_excerpt_more( $more ) {
    global $options;
    return $options['default_excerpt_morestring'];
}
add_filter('excerpt_more', 'fau_excerpt_more');


/*-----------------------------------------------------------------------------------*/
/* Changes default length for excerpt
/*-----------------------------------------------------------------------------------*/
function fau_excerpt_length( $length ) {
    global $options;
    return $options['default_excerpt_length'];
}
add_filter( 'excerpt_length', 'fau_excerpt_length' );


/*-----------------------------------------------------------------------------------*/
/* create array with organisation logos
/*-----------------------------------------------------------------------------------*/
function fau_init_header_logos() {
    global $options;
    global $default_fau_orga_data;

    $header_logos = array();
    foreach($default_fau_orga_data as $name=>$value) {

	if (strpos($name, '_') === 0) {
	    if ((strpos($name, '_faculty') === 0) && (!empty($options['website_usefaculty'])) ) {	    
		    $sub = $options['website_usefaculty'];
		    if (isset($default_fau_orga_data[$name][$sub])) {
			$header_logos[$sub]['url'] = $default_fau_orga_data[$name][$sub]['thumbnail'];
			if (isset($default_fau_orga_data[$name][$sub]['url'])) {
			    $header_logos[$sub]['thumbnail_url'] = $default_fau_orga_data[$name][$sub]['thumbnail'];
			} else {
			    $header_logos[$sub]['thumbnail_url'] = $default_fau_orga_data[$name][$sub]['url'];
			}
			$header_logos[$sub]['description'] = $default_fau_orga_data[$name][$sub]['title']; 
		    }
	    
	    } else {
		foreach($value as $sub=>$sval) {
		    $header_logos[$sub]['url'] = $default_fau_orga_data[$name][$sub]['url'];
		    if (isset($default_fau_orga_data[$name][$sub]['thumbnail'])) {
			$header_logos[$sub]['thumbnail_url'] = $default_fau_orga_data[$name][$sub]['thumbnail'];
		    } else {
			$header_logos[$sub]['thumbnail_url'] = $default_fau_orga_data[$name][$sub]['url'];
		    }
		    $header_logos[$sub]['description'] = $default_fau_orga_data[$name][$sub]['title']; 
		}
	    }
	} else {
	    $header_logos[$name]['url'] = $default_fau_orga_data[$name]['url'];
	    if (isset($default_fau_orga_data[$name]['thumbnail'])) {
		$header_logos[$name]['thumbnail_url'] = $default_fau_orga_data[$name]['thumbnail'];
	    } else {
		$header_logos[$name]['thumbnail_url'] = $default_fau_orga_data[$name]['url'];
	    }
	    $header_logos[$name]['description'] = $default_fau_orga_data[$name]['title']; 
	}
     }
     return $header_logos;
} 

/*-----------------------------------------------------------------------------------*/
/* Header setup
/*-----------------------------------------------------------------------------------*/
function fau_custom_header_setup() { 
    global $options;
	$args = array(
//	    'default-image'          => $options['default_logo_src'],
	    'height'			=> $options['default_logo_height'],
	    'width'			=> $options['default_logo_width'],
	    'admin-head-callback'	=> 'fau_admin_header_style',
	    'header-text'		=> false,
	    'flex-width'		=> true,
	    'flex-height'		=> true,
	);
	add_theme_support( 'custom-header', $args );
	$default_header_logos = fau_init_header_logos();
	register_default_headers( $default_header_logos );
}
add_action( 'after_setup_theme', 'fau_custom_header_setup' );


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
/*Fallback, if no main menu is defined yet
/*-----------------------------------------------------------------------------------*/
function fau_main_menu_fallback() {
    global $options;
    $output = '';
    $some_pages = get_pages(array('parent' => 0, 'number' => $options['default_mainmenu_number'], 'hierarchical' => 0));
    if($some_pages) {
        foreach($some_pages as $page) {
            $output .= sprintf('<li class="menu-item level1"><a href="%1$s">%2$s</a></li>', get_permalink($page->ID), $page->post_title);
        }
        
        $output = sprintf('<ul role="navigation" aria-label="%1$s" id="nav">%2$s</ul>', __('Navigation', 'fau'), $output);
    }   
    return $output;
}


/*-----------------------------------------------------------------------------------*/
/*  Refuse spam-comments on media
/*-----------------------------------------------------------------------------------*/
function filter_media_comment_status( $open, $post_id ) {
	$post = get_post( $post_id );
	if( $post->post_type == 'attachment' ) {
		return false;
	}
	return $open;
}
add_filter( 'comments_open', 'filter_media_comment_status', 10 , 2 );


/*-----------------------------------------------------------------------------------*/
/*  Search filter
/*-----------------------------------------------------------------------------------*/
function fau_searchfilter($query) {
    global $options;
    if ($query->is_search && !is_admin() ) {
	if(isset($_GET['post_type'])) {
	    $types = (array) $_GET['post_type'];
	} else {
	    $types = $options['search_post_types'];
	  //  $types = array("person", "post", "page", "attachment");
	  //  $types = array("attachment","person");
	}
	$allowed_types = get_post_types(array('public' => true, 'exclude_from_search' => false));
	foreach($types as $type) {
	    if( in_array( $type, $allowed_types ) ) { $filter_type[] = $type; }
	}
	if(count($filter_type)) {
	    $query->set('post_type',$filter_type);
	}	
        $query->set('post_status', array('publish','inherit'));

    }
}
add_filter("pre_get_posts","fau_searchfilter");


/*-----------------------------------------------------------------------------------*/
/*  Search sorting
/*-----------------------------------------------------------------------------------*/
add_filter('posts_orderby','fau_sort_custom',10,2);
function fau_sort_custom( $orderby, $query ){
    global $wpdb;

    if(!is_admin() && is_search())
    //    $orderby =  $wpdb->prefix."posts.post_type ASC, {$wpdb->prefix}posts.post_date DESC";
	 $orderby =  $wpdb->prefix."posts.post_modified DESC";

    return  $orderby;
}


/*-----------------------------------------------------------------------------------*/
/* wplink query args adjustment
/*-----------------------------------------------------------------------------------*/
function fau_wp_link_query_args( $query ) {
     // check to make sure we are not in the admin
   //  if ( !is_admin() ) {
          $query['post_type'] = array( 'post', 'page', 'person'  ); // show only posts and pages
   //  }
     return $query;
}
add_filter( 'wp_link_query_args', 'fau_wp_link_query_args' ); 


/*-----------------------------------------------------------------------------------*/
/*  display ids for pages columns and custom types
/*-----------------------------------------------------------------------------------*/
function revealid_add_id_column( $columns ) {
   $columns['revealid_id'] = 'ID';
   return $columns;
}

function revealid_id_column_content( $column, $id ) {
  if( 'revealid_id' == $column ) {
    echo $id;
  }
}
if ($options['advanced_reveal_pages_id']) {
    add_filter( 'manage_pages_columns', 'revealid_add_id_column', 5 );
    add_action( 'manage_pages_custom_column', 'revealid_id_column_content', 5, 2 );
}

/*-----------------------------------------------------------------------------------*/
/* Filter bad paragraphs - fallback
/*-----------------------------------------------------------------------------------*/
add_filter('the_content', 'remove_empty_p', 20, 1);
function remove_empty_p($content){
    $content = force_balance_tags($content);
    return preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
}

add_filter('the_content', 'remove_accordion_bad_br', 20, 1);
function remove_accordion_bad_br($content){
   // $content = force_balance_tags($content);
    return preg_replace('#<br\s*/*>\s*<div class="accordion#i', '<div class="accordion', $content);
}

add_filter('the_content', 'remove_bad_p', 20, 1);
function remove_bad_p($content){
   // $content = force_balance_tags($content);
    $content = preg_replace('#<p><div #i', '<div ', $content);
    return preg_replace('#</div></p>#i', '</div>', $content);
}

/*-----------------------------------------------------------------------------------*/
/* Filter for postcount
/*-----------------------------------------------------------------------------------*/
add_filter('wp_list_categories','categories_postcount_filter');
function categories_postcount_filter ($variable) {
   $variable = str_replace('(', '<span class="post_count">(', $variable);
   $variable = str_replace(')', ')</span>', $variable);
   return $variable;
}

/*-----------------------------------------------------------------------------------*/
/* Add rel lightbox to content images
/*-----------------------------------------------------------------------------------*/
add_filter('the_content', 'fau_addlightboxrel');
function fau_addlightboxrel ($content) {
    global $post;
    $pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
    $replacement = '<a$1class="lightbox" href=$2$3.$4$5$6</a>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}

/*-----------------------------------------------------------------------------------*/
/* Load Comment Functions
/*-----------------------------------------------------------------------------------*/
require get_template_directory() . '/functions/comments.php';


/*-----------------------------------------------------------------------------------*/
/* This is the end of the code as we know it
/*-----------------------------------------------------------------------------------*/



