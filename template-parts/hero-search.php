<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */
?>
<section id="hero" class="hero-small">
	<div class="container hero-content">
	    <div class="row">
		<div class="col-xs-12">
		    <?php fau_breadcrumb(); ?>
		</div>
	    </div>
	    <div class="row" aria-hidden="true" role="presentation">
		<div class="col-xs-12">	
			 <p class="presentationtitle"><?php echo get_theme_mod('title_hero_search'); ?></p>
		</div>
	    </div>
	</div>
</section>
