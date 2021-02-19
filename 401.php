<?php
/**
 * The template for displaying 401 pages (Unauthorisiert).
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header();
get_template_part('template-parts/hero', 'error');  

?>
	<section id="content">
	    <div class="container">
		<div class="row">
		     <h1 class="screen-reader-text"><?php echo __('Anmeldung fehlgeschlagen','fau'); ?></h1>
		    <div class="error-notice">
			<p class="hinweis">
				<strong><?php _e('Es tut uns leid.','fau'); ?></strong>
			</p>
			<p>
				<?php _e('Leider ist Ihre Anmeldung fehlgeschlagen.','fau'); ?>
			</p>						
		    </div>
		    <?php 
		    get_template_part('template-parts/error', 'siegel');   
		    get_template_part('template-parts/error', 'trysearch');  
		    ?>

		</div>
		<?php get_template_part('template-parts/search', 'helper');  ?>

	    </div>
	    
	</section>
<?php get_template_part('template-parts/footer', 'social'); ?>	
<?php 
get_footer();  
