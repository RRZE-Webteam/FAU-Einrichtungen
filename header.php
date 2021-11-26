<?php
/**
 * The Header for our theme.
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */
	global $defaultoptions;

?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <?php wp_head(); ?>
    
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div id="pagewrapper">
	<div id="headerwrap">
	    <div class="metalinks">
		<?php get_template_part('template-parts/header', 'skiplinks'); ?>
		<div id="meta">
			<div class="header-container">
			    <div class="header-row">
				<nav class="meta-links" aria-label="<?php _e('Navigation: Weitere Angebote','fau');?>">
				    <?php echo fau_get_toplinks(); ?>
				</nav>
				<div class="meta-tools">
				    <?php 
				    get_template_part('template-parts/header', 'search'); 
				    
				    
				    if ($defaultoptions['debugmode'] && get_theme_mod('debug_sprachschalter')) {
					 get_template_part('template-parts/debugoutput', 'sprachschalter'); 
				     } elseif ( is_active_sidebar( 'language-switcher' ) )  {
					 dynamic_sidebar( 'language-switcher' ); 
				     }  ?>
				</div>
			    </div>
			   
			</div>
		</div>
		 <?php
		if ($defaultoptions['debugmode'] && get_theme_mod('debug_orgabreadcrumb')) {
		    get_template_part('template-parts/debugoutput', 'orga-breadcrumb'); 
		} elseif ( is_plugin_active( 'fau-orga-breadcrumb/fau-orga-breadcrumb.php' ) ) { 
		    get_template_part('template-parts/header', 'orga-breadcrumb'); 			
		} ?>
	    </div>
	    <header id="header">
		<div class="header-container">
		    <div class="header-row">
			<div class="branding" id="logo" role="banner" itemscope itemtype="http://schema.org/Organization">
			   
			    <?php 
	
			    $show_customlogo = false;
			    $custom_logo_id = get_theme_mod( 'custom_logo' );
			    $logo_src = '';
			    if ($custom_logo_id) {
				$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
				$logo_src = $logo[0];
				$show_customlogo = true;
				if (! empty( $logo_src ) ) {
				    if (preg_match('/\/themes\/FAU\-[a-z]+\/img\/logos\//i', $logo_src, $match)) {
					$show_customlogo = false;
					 // Version 2: Check for old Images in theme, that was chosen in customizer, but removed
					// from code later. In this case, ignore this entry.
				    }
				}
			    }
			   
			    if ( $show_customlogo ) {		
				echo '<p class="sitetitle">';		
				echo '<meta itemprop="url" content="'.$logo_src.'">';
				echo '<meta itemprop="name" content="'.get_bloginfo( 'name', 'display' ).'">';
				echo get_custom_logo();
				echo '</p>';
			    } else {
				get_template_part('template-parts/header', 'textlogo'); 
			    }
			   ?>
			   
			</div>
			<nav class="header-menu" aria-label="<?php _e("Hauptnavigation","fau"); ?>">
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
	</div>
