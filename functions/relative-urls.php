<?php

/* 
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 * 
 * Make URLs relative; Several functions
 * 
 */


 /*-----------------------------------------------------------------------------------*/
/* Add another esc_url, but also makes URL relative
/*-----------------------------------------------------------------------------------*/
 function fau_esc_url( $url) {
     if (!isset($url)) {
	 $url = home_url("/");
     }
     return fau_make_link_relative(esc_url($url));
 }
 
 function get_fau_template_uri () {
     return get_template_directory_uri();
 }
 
/*-----------------------------------------------------------------------------------*/
/* Makes absolute URL from relative URL
/*-----------------------------------------------------------------------------------*/

 function fau_make_absolute_url( $url) {
    if (!isset($url)) {
	$url = home_url("/");
    } else {
	if (substr($url,0,1)=='/') {
	    $base = home_url();
	    return $base.$url;
	} else {
	    return $url;
	}
    }
 }
 
/*-----------------------------------------------------------------------------------*/
/* Rewrite Template redirects for generated urls
/*-----------------------------------------------------------------------------------*/
// add_action('template_redirect', 'fau_rw_relative_urls');
 
 // removed, due to problems with other plugins that need absolute urls ;/
 // WW: 19.09.2018
 
 /* 
function fau_rw_relative_urls() {
    // Don't do anything if:
    // - In feed
    // - In sitemap by WordPress SEO plugin
    if (is_admin() || is_feed() || get_query_var('sitemap')) {
        return;
    }
    $filters = array(
        'post_type_link',
        'page_link',
        'post_type_archive_link',
        'get_pagenum_link',
        'get_comments_pagenum_link',
        'term_link',
        'search_link',
        'day_link',
        'month_link',
        'year_link',
        'script_loader_src',
        'style_loader_src',
    );
    foreach ($filters as $filter) {
        add_filter($filter, 'fau_make_link_relative');
    }
}
*/
/*-----------------------------------------------------------------------------------*/
/* make urls relative to base url
/*-----------------------------------------------------------------------------------*/
function fau_make_link_relative($url) {
    $current_site_url = get_site_url();   
	if (!empty($GLOBALS['_wp_switched_stack'])) {
        $switched_stack = $GLOBALS['_wp_switched_stack'];
        $blog_id = end($switched_stack);
        if ($GLOBALS['blog_id'] != $blog_id) {
            $current_site_url = get_site_url($blog_id);
        }
    }
    $current_host = parse_url($current_site_url, PHP_URL_HOST);
    $host = parse_url($url, PHP_URL_HOST);
    if($current_host == $host) {
        $url = wp_make_link_relative($url);
    }
    return $url; 
}

/*-----------------------------------------------------------------------------------*/
/* Force srcset urls to be relative
/*-----------------------------------------------------------------------------------*/
add_filter( 'wp_calculate_image_srcset', function( $sources ) {
    if(	! is_array( $sources ) )
       	return $sources;

    foreach( $sources as &$source ) {
        if( isset( $source['url'] ) )
            $source['url'] = fau_esc_url( $source['url']);
    }
    return $sources;

}, PHP_INT_MAX );

