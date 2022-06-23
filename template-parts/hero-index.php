<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

?>
    <section id="hero" class="hero-small">
	<div class="hero-container hero-content">
		<div class="hero-row">
			<?php 
			fau_breadcrumb();
			?>
		</div>
		<div class="hero-row" aria-hidden="true" role="presentation">
		    <p class="presentationtitle"><?php echo get_the_archive_title(); ?></p>
		</div>
	</div>
    </section>
