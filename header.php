<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

global $options;

?><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	
	<div id="wrap">
		
		<nav id="skiplinks" aria-label="Skiplinks">
			<ul class="jumplinks">
			    <li><a href="#content" data-target="#content" data-firstchild="0" class="jumplink-content"><?php _e('Zum Inhalt springen','fau'); ?></a></li>
			    <li><a href="#search-header" data-target="#meta .searchform input" data-firstchild="1" class="jumplink-search"><?php _e('Zur Suche springen','fau'); ?></a></li>
			    <li><a href="#nav" data-target="#nav a" data-firstchild="1" class="jumplink-nav"><?php _e('Zum Hauptmenü springen','fau'); ?></a></li>
			    <?php if(!is_tax() && !is_category()  && basename( get_page_template() )=='page-subnav.php') { ?>
			    <li><a href="#subnav" data-target="#subnav li a" data-firstchild="1" class="jumplink-subnav"><?php _e('Zum Seitenmenü springen','fau'); ?></a></li><?php } ?>
			</ul>
		</nav>    
		<section id="meta">
			<div class="container">
				<div class="pull-left">
					<?php 
					echo fau_get_toplinks(); 
					?>
				</div>
				<div class="pull-right">
					<?php if ( is_active_sidebar( 'language-switcher' ) ) : ?>
						<?php dynamic_sidebar( 'language-switcher' ); ?>
					<?php endif; ?>
				    
				    <?php get_template_part('header', 'searchform'); ?>
				</div>
			</div>
		</section>
		<?php if (isset($options['display_nojs_notice']) && $options['display_nojs_notice']==1) { ?> 
		<noscript>
			<div id="no-script">
				<div class="container">
					<div class="notice">
						<?php  echo $options['display_nojs_note']; ?>				
					</div>
				</div>
			</div>
		</noscript>
		<?php } ?>
		<header id="header">
			<div class="container">
		    
			    <?php 

			    $header_image = get_header_image();
			    echo '<div class="branding" id="logo" role="banner" itemprop="publisher" itemscope itemtype="http://schema.org/Organization">';
			    echo '<h1>';
			    if ( ! is_front_page() ) { 
				echo '<a itemprop="url" rel="home" href="'.fau_esc_url(home_url( '/' ) ).'">';	
			    } 


			    if ( ! empty( $header_image ) ) {	
				echo '<img src="'.fau_esc_url( $header_image ).'"  alt="'.esc_attr(get_bloginfo( 'title' )).'">';	

			//	echo '<img src="'.fau_esc_url( $header_image ).'" width="'.get_custom_header()->width.'" height="'.get_custom_header()->height.'" alt="'.esc_attr(get_bloginfo( 'title' )).'">';	
				// echo '<img src="'.fau_esc_url( $header_image ).'" width="'.$options['default_logo_width'].'" height="'.$options['default_logo_height'].'" alt="'.esc_attr(get_bloginfo( 'title' )).'">';
				
			    } else {				 
				echo get_bloginfo( 'title' );   
			    } 
			    if ( ! is_front_page() ) {  
				echo "</a>"; 			    
			    }
			    echo '</h1>';
			    echo "</div>\n";
			    ?>
			    <a href="#" id="nav-toggle" class="hide-desktop">
				    <div></div>
				    <div></div>
				    <div></div>
			    </a>			
			    <?php
			    if(has_nav_menu( 'main-menu' ) && class_exists('Walker_Main_Menu', false)) {
				wp_nav_menu( array( 'theme_location' => 'main-menu', 'container' => false, 'items_wrap' => '<ul role="navigation" aria-label="'.__("Navigation", "fau").'" id="nav">%3$s</ul>', 'depth' => 2, 'walker' => new Walker_Main_Menu) ); 
			    } elseif(!has_nav_menu( 'main-menu' )) {
				echo fau_main_menu_fallback(); 
			    } else {
				// the class Walker_Main_Menu doesn't exist!
			    }
	            ?>
			</div>
		</header>