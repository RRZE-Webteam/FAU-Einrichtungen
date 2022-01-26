<?php

/**
 * Template Part für Contentmenus unter dem Content-Bereich
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 2.0
 */

$menuslug = get_post_meta( $post->ID, 'portalmenu-slug', true );	
if ($menuslug) { 	
    echo "<hr>";
    
    
    $shortcodeopt = 'menu="'.$menuslug.'"';
   
    $nosub  = get_post_meta( $post->ID, 'fauval_portalmenu_nosub', true );  
    if ($nosub==1) {
	$shortcodeopt .= ' showsubs="false"';
    } else {
        $shortcodeopt .= ' showsubs="true"';
    }
    
    
    $nofallbackthumbs  = get_post_meta( $post->ID, 'fauval_portalmenu_nofallbackthumb', true );
    if ($nofallbackthumbs) {
	 $shortcodeopt .= ' nofallback="true"';
    }
    
    
    $nothumbnails  = get_post_meta( $post->ID, 'fauval_portalmenu_thumbnailson', true ); 
    if ($nothumbnails) {
	 $shortcodeopt .= ' nothumbs="true"';
    }
    
    
    $type = get_post_meta($post->ID, 'fauval_portalmenu_type', true);
    if ($type) {
	$shortcodeopt .= ' type="'.$type.'"';
    }
    
    $skewed = get_post_meta($post->ID, 'fauval_portalmenu_skewed', true);
    if ($skewed) {
	 $shortcodeopt .= ' skewed="true"';
    }
    
    
    echo do_shortcode('[portalmenu '.$shortcodeopt.']'); 
    

  }