<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

global $options;
get_header(); ?>


	<section id="hero" class="hero-small">
		<div class="container">
			<div class="row">
				<div class="span12">
					<div class="breadcrumbs">
						<a href="<?php echo fau_esc_url( home_url( '/' ) ); ?>"><?php echo $options['breadcrumb_root']; ?></a>
					</div>

					<div class="hero-meta-portal">
						401
					</div>
				</div>
			</div>
			<div class="row">
				<div class="span6">
					<h1><?php _e('Anmeldung fehlgeschlagen','fau'); ?></h1>
				</div>
			</div>
		</div>
	</section>
	

	<section id="content">
		<div class="container">
		
			<div class="row">
				<div class="span6">
					<p class="hinweis">
						<strong><?php _e('Es tut uns leid.','fau'); ?></strong><br>
						<?php _e('Leider ist Ihre Anmeldung fehlgeschlagen.','fau'); ?>
					</p>
					<div class="row">
						<div class="span4 offset2"><img src="<?php echo fau_get_template_uri(); ?>/img/friedrich-alexander.gif" alt="" class="error-404-persons"></div>
					</div>
				</div>
			</div>

			<?php get_template_part('search', 'helper');  ?>

		</div>
			<?php get_template_part('footer', 'social'); ?>	
	</section>

<?php 
get_footer();  
