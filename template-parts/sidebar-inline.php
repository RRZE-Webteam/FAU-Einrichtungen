<?php
/**
 * The template partial for displaying the sidebar
 *
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */


    $sidebarfilled =0;
    $titleup = get_post_meta( $post->ID, 'sidebar_title_above', true );
    $textup = get_post_meta( $post->ID, 'sidebar_text_above', true );
    $titledown = get_post_meta( $post->ID, 'sidebar_title_below', true );
    $textdown = get_post_meta( $post->ID, 'sidebar_text_below', true );

    if ($titleup || $titledown || $textup || $textdown) {
	$sidebarfilled =1;
    } else {
	$foundlink = 0;   
	$linkblock1_number = get_theme_mod('advanced_page_sidebar_linkblock1_number');
	if ($linkblock1_number > 0) {	
	    for ($i = 1; $i <= $linkblock1_number; $i++) {	
		$name = 'fauval_linkblock1_link'.$i;
		$urlname= $name.'_url';
		$oldurl =  get_post_meta( $post->ID, $urlname, true );
		$oldid =  get_post_meta( $post->ID, $name, true );
		if ($oldid || !empty($oldurl)) {
		    $foundlink = 1;    
		}
	    }
	}
	if ($foundlink) {
	    $sidebarfilled =2;
	} else {
	    $linkblock2_number = get_theme_mod('advanced_page_sidebar_linkblock2_number');
	    if ($linkblock2_number > 0) {	
		for ($i = 1; $i <= $linkblock2_number; $i++) {	
		    $name = 'fauval_linkblock2_link'.$i;
		    $urlname= $name.'_url';
		    $oldurl =  get_post_meta( $post->ID, $urlname, true );
		    $oldid =  get_post_meta( $post->ID, $name, true );
		    if ($oldid || !empty($oldurl)) {
			$foundlink = 1;    
		    }
		}
	    }

	    if ($foundlink) {
		$sidebarfilled =3;
	    } else {
		$sidebar_personen = get_post_meta( $post->ID, 'sidebar_personen', true );
		if ($sidebar_personen) {
		    $sidebarfilled =4;	
		}
	    }
	}

    }


    if ($sidebarfilled>0) { ?>
	<div class="sidebar-inline">
	    <?php 		
	    get_template_part('template-parts/sidebar', 'events'); 	
	    get_template_part('template-parts/sidebar', 'textabove');  


	    $order = get_post_meta($post->ID, 'fauval_sidebar_order_personlinks', true );
	    if ($order==1) {
		get_template_part('template-parts/sidebar', 'quicklinks');
		get_template_part('template-parts/sidebar', 'personen-inline');
	    } else {
		get_template_part('template-parts/sidebar', 'personen-inline');
		get_template_part('template-parts/sidebar', 'quicklinks');
	    }

	 get_template_part('template-parts/sidebar', 'textbelow'); ?>
    </div>

    <?php }	?>
