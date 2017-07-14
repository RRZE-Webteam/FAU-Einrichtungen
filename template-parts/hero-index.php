<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */
	    
global $options;
?>

<section id="hero" class="hero-small">
	<div class="container">
		<div class="row">
			<div class="span8">
		
			    <?php
			    fau_breadcrumb();		    
			      ?>
			     
			    <h1><?php echo __('Index','fau') ?></h1>
				
			</div>
		</div>
	</div>
</section>
