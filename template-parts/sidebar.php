<?php
/**
 * The template partial for displaying the sidebar
 *
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */


get_template_part('template-parts/sidebar', 'textabove');  

$order = get_post_meta($post->ID, 'fauval_sidebar_order_personlinks', true );

if ($order==1) {
    get_template_part('template-parts/sidebar', 'quicklinks');
    get_template_part('template-parts/sidebar', 'personen');
} else {
    get_template_part('template-parts/sidebar', 'personen');
    get_template_part('template-parts/sidebar', 'quicklinks');
}

get_template_part('template-parts/sidebar', 'textbelow'); ?>

