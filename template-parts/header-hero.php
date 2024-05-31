<?php
/* 
 * Creates the Hero Bar below the main navigation
 * 
 * Theme FAU Einrichtungen, since V2.3
 *  
 */

$posttype = get_post_type();
$known_types_without_hero = get_theme_mod('default_posttypes_without_hero'); 

if (isset($posttype) && (isset($known_types_without_hero)) && (in_array($posttype, $known_types_without_hero))) {
    // no hero for these types
    // mostly used by special plugins, like FAU Studium
} elseif (is_search()) {
    get_template_part('template-parts/hero', 'search');  
} elseif (is_404()) {
    get_template_part('template-parts/hero', 'error');
} elseif (isset($_REQUEST['status']) && $_REQUEST['status'] >= 400) {
    get_template_part('template-parts/hero', 'error');
} else {
   
    $herotype = get_theme_mod('advanced_header_template');    
    $page_template = basename(get_page_template());
    switch ($page_template) {

        case 'page-start-sub.php':
            get_template_part('template-parts/hero', 'banner');
            break;
        case 'page-start.php':
            get_template_part('template-parts/hero', 'slider');
            break;
        case 'scroll-stories.php':
        case 'video-podcast-post.php':    
        case 'page-portal.php':
        case 'page-portalindex.php':   
        case 'page-subnav.php': 
        case 'page-full.php': 
        case 'page.php':

                if ($herotype=='banner') {
                    get_template_part('template-parts/hero', 'banner');
                } elseif ($herotype=='slider') {	
                    get_template_part('template-parts/hero', 'slider');
                } elseif (($posttype == 'post') && (is_archive())) {   
                    get_template_part('template-parts/hero', 'category');
                } else {
                    get_template_part('template-parts/hero', 'small'); 
                } 

            break;
        default:
            get_template_part('template-parts/hero', 'small'); 
             
    }
    
}