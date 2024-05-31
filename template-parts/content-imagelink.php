<?php

/**
 * Template Part for Imagelinks
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 2.5
 * Created on : 24.05.2024, 17:45:14
 */

$logoliste = get_post_meta($post->ID, 'fauval_imagelink_catid', true);
if ($logoliste) {
    $logosize = get_post_meta($post->ID, 'fauval_imagelink_size', true);
    $size = $logosize != '' ? esc_attr($logosize) : "logo-thumb";
    $logos = fau_imagelink_get(array('size' => $size, 'catid' => $logoliste));
    if ((isset($logos) && (!empty($logos)))) {
        
        $template = get_page_template_slug( $post->ID );
        
         $page_template = basename(get_page_template());
         if (($page_template == 'page-start-sub.php') || ($page_template == 'page-start.php')) {
             echo "<hr>";
         }

        echo $logos;
    }
}
