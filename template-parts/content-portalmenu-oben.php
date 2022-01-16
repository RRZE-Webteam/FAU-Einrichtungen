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
    echo '<div class="content-row">';
    $nosub  = get_post_meta( $post->ID, 'fauval_portalmenu_nosub_oben', true );
    if ($nosub==1) {
        $displaysub =0;
    } else {
        $displaysub =1;
    }
    $nofallbackthumbs  = get_post_meta( $post->ID, 'fauval_portalmenu_nofallbackthumb_oben', true );
    $nothumbnails  = get_post_meta( $post->ID, 'fauval_portalmenu_thumbnailson_oben', true ); 
    $type = get_post_meta($post->ID, 'fauval_portalmenu_type_oben', true);
    $skewed = get_post_meta($post->ID, 'fauval_portalmenu_skewed_oben', true);

    echo FAUShortcodes::fau_portalmenu(array('menu' => $menuslug, 
        'showsubs' => $displaysub, 'nothumbs' => $nothumbnails, 
        'nofallback' => $nofallbackthumbs, 'type' => $type, 'skewed' => $skewed));
    
    echo '</div>';
  }