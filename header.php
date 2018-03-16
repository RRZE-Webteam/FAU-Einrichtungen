<?php
/**
 * The Header for our theme.
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="wrap">		
	<h1 class="screen-reader-text"><?php _e('Navigation','fau'); ?></h1>
	<nav id="skiplinks">
	    <h2 class="screen-reader-text"><?php _e('Skiplinks','fau'); ?></h2>
		<ul class="jumplinks">
		    <li><a href="#content" data-target="#content" data-firstchild="0" class="jumplink-content"><?php _e('Zum Inhalt springen','fau'); ?></a></li>
		    <li><a href="#search-header" data-target="#meta .searchform input" data-firstchild="1" class="jumplink-search"><?php _e('Zur Suche springen','fau'); ?></a></li>
		    <li><a href="#hauptnav-anchor" data-target="#hauptnav-anchor" data-firstchild="1" class="jumplink-nav"><?php _e('Zur Hauptnavigation springen','fau'); ?></a></li>
		    <?php if(!is_tax() && !is_category()  && basename( get_page_template() )=='page-subnav.php') { ?>
		    <li><a href="#subnavtitle" data-firstchild="1" class="jumplink-subnav"><?php _e('Zur Bereichsnavigation springen','fau'); ?></a></li><?php } ?>
		</ul>
	</nav>    
	<div id="meta">
		<div class="container">
		    <div class="row">
			<nav class="meta-links">
			    <h2 class="screen-reader-text"><?php echo __('Meta-Navigation','fau');?></h2>
			    <?php echo fau_get_toplinks(); ?>
			</nav>
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
		    <?php if ( is_plugin_active( 'fau-orga-breadcrumb/fau-orga-breadcrumb.php' ) ) { ?>
		    <div class="row orga">
			<?php echo do_shortcode('[fauorga]'); ?>
		    </div>
		    <?php } ?>
		</div>
	</div>
	<header id="header">
	    <div class="container">
		<div class="row">
		    <div class="branding" id="logo" role="banner" itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
                        <p class="sitetitle">
                        <?php if ( ! is_front_page() ) { 
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
                        } ?>
                        </p>       
		    </div>
		    <nav class="header-menu">	
                        <h2 id="hauptnav-anchor" class="screen-reader-text"><?php _e("Hauptnavigation","fau"); ?></h2>
			<button id="mainnav-toggle" aria-expanded="false" aria-controls="menu"><span><?php _e("Menu","fau"); ?></span></button>						
			<?php
			    if(has_nav_menu( 'main-menu' ) && class_exists('Walker_Main_Menu', false)) {
				wp_nav_menu( array( 'theme_location' => 'main-menu', 'container' => false, 'items_wrap' => '<ul role="navigation" id="nav">%3$s</ul>', 'depth' => 2, 'walker' => new Walker_Main_Menu) ); 
			    } elseif(!has_nav_menu( 'main-menu' )) {
				echo fau_main_menu_fallback(); 
			    }
			?>
		    </nav>
		</div>
	    </div>
	</header>