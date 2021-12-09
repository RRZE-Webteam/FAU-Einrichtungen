<?php
/**
 * Template part for small hero
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
			<?php 
			fau_breadcrumb();
			?>
			
		    </div>
		</div>
		<div class="row" aria-hidden="true" role="presentation">
		    <div class="col-xs-12">
			<p class="presentationtitle" <?php echo fau_get_page_langcode($post->ID);?>><?php the_title(); ?></p>
		    </div>
		</div>
	</div>
    </section>
