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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="wrap">
	<div role="navigation" aria-labelledby="navigation-top-title">
	    <h1 class="screen-reader-text" id="navigation-top-title"><?php _e('Navigation','fau'); ?></h1>
	    <?php get_template_part('template-parts/header', 'skiplinks'); ?>

	    <div id="meta">
		    <div class="container">
			<div class="row">
			    <nav class="meta-links" aria-labelledby="meta-links-title">
				<h2 id="meta-links-title" class="screen-reader-text"><?php echo __('Meta-Navigation','fau');?></h2>
				<?php echo fau_get_toplinks(); ?>
			    </nav>
			    <div class="meta-tools">
				<?php 
				get_template_part('template-parts/header', 'search'); 
				
				if ( is_active_sidebar( 'language-switcher' ) )  {
				     dynamic_sidebar( 'language-switcher' ); 
				 } ?>
			    </div>
			</div>
			<?php if ( is_plugin_active( 'fau-orga-breadcrumb/fau-orga-breadcrumb.php' ) ) { ?>
			<div class="row orga">
			    <?php echo do_shortcode('[fauorga]'); ?>
			</div>
			<?php } ?>
		    </div>
	    </div>
	</div>
	<header id="header" aria-label="<?php _e("Seitenkopf","fau"); ?>">
	    <div class="container">
		<div class="row">
		    <div class="branding" id="logo" role="banner" itemscope itemtype="http://schema.org/Organization">
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
		    <nav class="header-menu" aria-labelledby="hauptnav-anchor">
                <h2 id="hauptnav-anchor" class="screen-reader-text"><?php _e("Hauptnavigation","fau"); ?></h2>
                <a href="#nav" id="mainnav-toggle"><span><?php _e("Menu","fau"); ?></span></a>
			<?php
			    if(has_nav_menu( 'main-menu' ) && class_exists('Walker_Main_Menu', false)) {
				if (('' != get_theme_mod( 'advanced_display_portalmenu_plainview' )) && (true== get_theme_mod( 'advanced_display_portalmenu_plainview' )) ) {
				    wp_nav_menu( array( 'theme_location' => 'main-menu', 'container' => false, 'items_wrap' => '<div id="nav"><ul class="nav">%3$s</ul></div>', 'depth' => 4, 'walker' => new Walker_Main_Menu_Plainview) );
				} else {
				    wp_nav_menu( array( 'theme_location' => 'main-menu', 'container' => false, 'items_wrap' => '<div id="nav"><ul class="nav">%3$s</ul></div>', 'depth' => 2, 'walker' => new Walker_Main_Menu) );
				}
			    } elseif(!has_nav_menu( 'main-menu' )) {
				echo fau_main_menu_fallback();
			    }
			?>
		    </nav>
		</div>
	    </div>
	</header>