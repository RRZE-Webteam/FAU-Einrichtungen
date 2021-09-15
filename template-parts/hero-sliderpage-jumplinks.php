<?php

/**
 * Template Part hero junplinks
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */

if (('' != get_theme_mod( 'advanced_activate_quicklinks' )) && (true== get_theme_mod( 'advanced_activate_quicklinks' )) ) {
?>
    <nav class="hero-navigation" aria-label="<?php echo __('Quicklinks','fau'); ?>">
	<div class="container">
	    <div class="row">
		    <?php 
		    if (get_theme_mod('website_type')==-1) { ?>
		     <div class="quicklinks">
			    <?php if(has_nav_menu('quicklinks-1')) { ?>
				    <p class="headline"><?php echo fau_get_menu_name('quicklinks-1'); ?></p>
				    <?php wp_nav_menu( array( 'theme_location' => 'quicklinks-1', 'container' => false, 'items_wrap' => '<ul class="%2$s">%3$s</ul>' ) ); 
			    } else {
				echo fau_get_defaultlinks('faculty','menu-faculties');
			    } ?>
		    </div>
		    <div class="quicklinks">
			    <?php if(has_nav_menu('quicklinks-2')) { ?>
				    <p class="headline"><?php echo fau_get_menu_name('quicklinks-2'); ?></p>
				    <?php wp_nav_menu( array( 'theme_location' => 'quicklinks-2', 'container' => false, 'items_wrap' => '<ul class="%2$s">%3$s</ul>' ) );  
			    } else {
				echo fau_get_defaultlinks('diefau');
			    } ?>
		    </div>
		    <?php } else { ?>
		    <div role="presentation" class="infobar">				    
			<?php 
		       $header_image = get_header_image();
			if (!empty( $header_image ) ){	
			    echo '<p class="sitetitle">'. get_bloginfo( 'title' ). "</p>\n";
			}
			if (null !== get_bloginfo( 'description' )) {
			     echo '<p class="description">'.get_bloginfo( 'description' )."</p>";
			}
			?>
		    </div>
		    <?php } ?>
		    <div class="quicklinks">
			    <?php if(has_nav_menu('quicklinks-3')) { ?>
				    <p class="headline"><?php echo fau_get_menu_name('quicklinks-3'); ?></p>
				    <?php wp_nav_menu( array( 'theme_location' => 'quicklinks-3', 'container' => false, 'items_wrap' => '<ul class="%2$s">%3$s</ul>' ) ); 
			    } else {
				echo fau_get_defaultlinks('centers');
			    } ?>
		    </div>
		    <div class="quicklinks">
			    <?php if(has_nav_menu('quicklinks-4')) { ?>
				    <p class="headline"><?php echo fau_get_menu_name('quicklinks-4'); ?></p>
				    <?php wp_nav_menu( array( 'theme_location' => 'quicklinks-4', 'container' => false, 'items_wrap' => '<ul class="%2$s">%3$s</ul>' ) );  
			    } else {
				echo fau_get_defaultlinks('infos');
			    } ?>
		    </div>
	    </div>
	</div>
	<?php  } ?>
    </nav>
