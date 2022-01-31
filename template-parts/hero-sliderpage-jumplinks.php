<?php

/**
 * Template Part hero junplinks
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */

if ((has_nav_menu('quicklinks-3')) || (has_nav_menu('quicklinks-4'))) {
?>
    <nav class="hero-navigation" aria-label="<?php echo __('Quicklinks','fau'); ?>">
	<div class="content-container">
	    <div class="content-row">

		    <div role="presentation" class="infobar">				    
			<?php 

			echo '<p class="sitetitle">'. get_bloginfo( 'title' ). "</p>\n";

			if (null !== get_bloginfo( 'description' )) {
			     echo '<p class="description">'.get_bloginfo( 'description' )."</p>";
			}
			?>
		    </div>

		    <div class="quicklinks">
			    <?php if(has_nav_menu('quicklinks-3')) { ?>
				    <p class="headline"><?php echo fau_get_menu_name('quicklinks-3'); ?></p>
				    <?php wp_nav_menu( array( 'theme_location' => 'quicklinks-3', 'container' => false, 'items_wrap' => '<ul class="%2$s">%3$s</ul>' ) ); 
			    } ?>
		    </div>
		    <div class="quicklinks">
			    <?php if(has_nav_menu('quicklinks-4')) { ?>
				    <p class="headline"><?php echo fau_get_menu_name('quicklinks-4'); ?></p>
				    <?php wp_nav_menu( array( 'theme_location' => 'quicklinks-4', 'container' => false, 'items_wrap' => '<ul class="%2$s">%3$s</ul>' ) );  
			    } ?>
		    </div>
	    </div>
	</div>
	
    </nav>
<?php  
} 
