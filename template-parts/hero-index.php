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
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			    <?php fau_breadcrumb(); ?>			     
			     <p class="presentationtitle"  aria-hidden="true" role="presentation"><?php echo __('Index','fau'); ?></p>
				
			</div>
		</div>
	</div>
</section>
