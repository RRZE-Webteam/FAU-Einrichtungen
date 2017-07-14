<?php
/**
 * The template for displaying 404 pages (Zugriff nicht erlaubt).
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
				<div class="span6">
					<p class="hinweis">
						<strong><?php _e('Es tut uns leid.','fau'); ?></strong><br>
						<?php _e('Leider dürfen Sie auf diese Seite nicht zugreifen.','fau'); ?>
					</p>
					<div class="row">
						<div class="span4 offset2"><img src="<?php echo fau_get_template_uri(); ?>/img/friedrich-alexander.gif" width="227" height="169" alt="" class="error-siegel"></div>
					</div>
				</div>
			</div>

			<?php get_template_part('template-parts/search', 'helper');  ?>

		</div>
	    		<?php get_template_part('template-parts/footer', 'social'); ?>	
	</section>

<?php 
get_footer();
