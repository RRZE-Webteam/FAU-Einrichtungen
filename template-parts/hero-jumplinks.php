<?php

/**
 * Template Part hero junplinks
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */

global $options; 
?>
	    <div class="container hero-navigation">
		<div class="row">
			<?php 
			if ($options['website_type']==-1) { ?>
			 <div class="quicklinks">
				<?php if(has_nav_menu('quicklinks-1')) { ?>
					<h3><?php echo fau_get_menu_name('quicklinks-1'); ?></h3>
					<?php wp_nav_menu( array( 'theme_location' => 'quicklinks-1', 'container' => false, 'items_wrap' => '<ul class="%2$s">%3$s</ul>' ) ); 
				} else {
				    echo fau_get_defaultlinks('faculty','menu-faculties');
				} ?>
			</div>
			<div class="quicklinks">
				<?php if(has_nav_menu('quicklinks-2')) { ?>
					<h3><?php echo fau_get_menu_name('quicklinks-2'); ?></h3>
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
				echo "<h1>". get_bloginfo( 'title' ). "</h1>\n";
			    }
			    if (null !== get_bloginfo( 'description' )) {
				 echo '<p class="description">'.get_bloginfo( 'description' )."</p>";
			    }
			    ?>
			</div>
			<?php } ?>

			<div class="quicklinks">
				<?php if(has_nav_menu('quicklinks-3')) { ?>
					<h3><?php echo fau_get_menu_name('quicklinks-3'); ?></h3>
					<?php wp_nav_menu( array( 'theme_location' => 'quicklinks-3', 'container' => false, 'items_wrap' => '<ul class="%2$s">%3$s</ul>' ) ); 
				} else {
				    echo fau_get_defaultlinks('centers');
				} ?>
			</div>
			<div class="quicklinks">
				<?php if(has_nav_menu('quicklinks-4')) { ?>
					<h3><?php echo fau_get_menu_name('quicklinks-4'); ?></h3>
					<?php wp_nav_menu( array( 'theme_location' => 'quicklinks-4', 'container' => false, 'items_wrap' => '<ul class="%2$s">%3$s</ul>' ) );  
				} else {
				    echo fau_get_defaultlinks('infos');
				} ?>
			</div>
		</div>
	    <?php if ($options['advanced_page_start_herojumplink']) { ?>
		<a tabindex="-1" aria-hidden="true" href="#content" class="hero-jumplink-content"></a>
	    <?php } ?>
	</div>