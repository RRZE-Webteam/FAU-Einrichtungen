<?php

/**
 * Template Part für Contentmenus unter dem Content-Bereich
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 2.0
 */

$menuslug = get_post_meta( $post->ID, 'portalmenu-slug_oben', true );	
if ($menuslug) { 	
    echo '<div class="content-row portalmenu-oben">';
     $shortcodeopt = 'menu="'.$menuslug.'"';
   
    $nosub  = get_post_meta( $post->ID, 'fauval_portalmenu_nosub_oben', true );  
    if ($nosub==1) {
        $shortcodeopt .= ' showsubs="false"';
    } else {
        $shortcodeopt .= ' showsubs="true"';
    }
    
    
    $nofallbackthumbs  = get_post_meta( $post->ID, 'fauval_portalmenu_nofallbackthumb_oben', true );
    if ($nofallbackthumbs) {
        $shortcodeopt .= ' nofallback="true"';
    }
    
    
    $nothumbnails  = get_post_meta( $post->ID, 'fauval_portalmenu_thumbnailson_oben', true ); 
    if ($nothumbnails) {
        $shortcodeopt .= ' nothumbs="true"';
    }
    
    
    $type = get_post_meta($post->ID, 'fauval_portalmenu_type_oben', true);
    if ($type) {
        $shortcodeopt .= ' type="'.$type.'"';
    }
    

    $listview = get_post_meta($post->ID, 'fauval_portalmenu_listview_oben', true);
    if ($listview) {
        $shortcodeopt .= ' listview="true"';
    }
    
    $hoverzoom = get_post_meta($post->ID, 'fauval_portalmenu_hoverZoom_oben', true);
    if ($hoverzoom) {
        $shortcodeopt .= ' hoverzoom="true"';
    }

    $hoverblur = get_post_meta($post->ID, 'fauval_portalmenu_hoverBlur_oben', true);
    if ($hoverblur) {
        $shortcodeopt .= ' hoverblur="true"';
    }

    echo do_shortcode('[portalmenu '.$shortcodeopt.']');
    echo '</div>';
  }