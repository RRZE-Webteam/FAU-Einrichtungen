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
    $nosub  = get_post_meta( $post->ID, 'fauval_portalmenu_nosub', true );
    if ($nosub==1) {
        $displaysub =0;
    } else {
        $displaysub =1;
    }
    $nofallbackthumbs  = get_post_meta( $post->ID, 'fauval_portalmenu_nofallbackthumb', true );
    $nothumbnails  = get_post_meta( $post->ID, 'fauval_portalmenu_thumbnailson', true ); 
    $type = get_post_meta($post->ID, 'fauval_portalmenu_type', true);
    $skewed = get_post_meta($post->ID, 'fauval_portalmenu_skewed', true);

    echo FAUShortcodes::fau_portalmenu(array('menu' => $menuslug, 
        'showsubs' => $displaysub, 'nothumbs' => $nothumbnails, 
        'nofallback' => $nofallbackthumbs, 'type' => $type, 'skewed' => $skewed));
  }