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
?>
<!DOCTYPE html>
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
			    <div class="row">
				<div class="meta-links">
					<h3><?php _e('Weitere Webauftritte','fau'); ?></h3>
					<?php echo fau_get_toplinks(); ?>
				</div>
				<div class="meta-tools">
				    <div class="meta-search">
					<button id="search-toggle" aria-expanded="false" aria-controls="search-header"><span><?php _e("Suche","fau"); ?></span></button>
					<?php get_template_part('template-parts/search', 'header'); ?>
				    </div>
				<?php if ( is_active_sidebar( 'language-switcher' ) ) : ?>
				    <?php dynamic_sidebar( 'language-switcher' ); ?>						   
				<?php endif; ?>
				</div>
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
			<div class="row">
			    <div class="branding" id="logo" role="banner" itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
			    <?php 
			    
			    echo '<h1>';
			    if ( ! is_front_page() ) { 
				echo '<a itemprop="url" rel="home" href="'.fau_esc_url(home_url( '/' ) ).'">';	
			    } 
			    $header_image = get_header_image();
			    if ( ! empty( $header_image ) ) {	
				$customheader =  get_custom_header();
				$attachment_id = 0;
				if (isset($customheader->attachment_id)) {
				    $attachment_id = $customheader->attachment_id; 
				    $srcset=  esc_attr(wp_get_attachment_image_srcset( $attachment_id, 'full'));
				} else {
				    $srcset ='';
				}  
				echo '<img src="'.$header_image.'" width="'.get_custom_header()->width.'" height="'.get_custom_header()->height.'" alt="'.esc_attr(get_bloginfo( 'title' )).'"';
				if ($srcset) {
				    echo ' srcset="'.$srcset.'"';
				}
				echo ">";	
				
			    } else {				 
				echo "<span>".get_bloginfo( 'title' )."</span>";   
			    } 
			    if ( ! is_front_page() ) {  
				echo "</a>"; 			    
			    } 
			    echo '</h1>';
			    ?>
			    </div>
			    <nav class="header-menu">		
				<button id="mainnav-toggle" aria-expanded="false" aria-controls="menu"><span><?php _e("Menu","fau"); ?></span></button>						
				<?php
				    if(has_nav_menu( 'main-menu' ) && class_exists('Walker_Main_Menu', false)) {
					wp_nav_menu( array( 'theme_location' => 'main-menu', 'container' => false, 'items_wrap' => '<ul role="navigation" aria-label="'.__("Navigation", "fau").'" id="nav">%3$s</ul>', 'depth' => 2, 'walker' => new Walker_Main_Menu) ); 
				    } elseif(!has_nav_menu( 'main-menu' )) {
					echo fau_main_menu_fallback(); 
				    }
				?>
			    </nav>
			</div>
		    </div>
		</header>