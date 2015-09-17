<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

global $options;
get_header();
?>


	<section id="hero" class="hero-small">
		<div class="container">
			<div class="row">
				<div class="span12">
					<div class="breadcrumbs">
						<a href="<?php echo fau_esc_url( home_url( '/' ) ); ?>"><?php echo $options['breadcrumb_root']; ?></a>
					</div>

					<div class="hero-meta-portal">
						404
					</div>
				</div>
			</div>
			<div class="row">
				<div class="span6">
					<h1><?php _e('Seite nicht gefunden','fau'); ?></h1>
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
						<?php _e('Die von Ihnen aufgerufene Seite existiert nicht oder ihre Adresse hat sich durch Änderungen der Seiten geändert.','fau'); ?>
					</p>
					<div class="row">
						<div class="span4 offset2"><img src="<?php echo fau_get_template_uri(); ?>/img/friedrich-alexander.gif" alt="" class="error-404-persons"></div>
					</div>
				</div>
				<div class="span6">
					<form role="search" method="get" class="searchform searchform-content" action="<?php echo home_url( '/' )?>">
						<h3><?php _e('Vielleicht hilft Ihnen die Suche:','fau'); ?></h3>
						<?php
							
							$uri = esc_url($_SERVER['REQUEST_URI']);
							$uri = str_replace('/', ' ', $uri);

						?>
						<label class="unsichtbar" for="suchmaske-error"><?php _e('Geben Sie hier den Suchbegriff ein','fau'); ?></label>
						<input type="text" value="<?php echo $uri ?>" name="s" id="suchmaske-error" placeholder="<?php _e('Suchen nach...','fau'); ?>">
						<input type="submit" id="searchsubmit" value="<?php _e('Finden','fau'); ?>">
					</form>
				</div>
			</div>

		    
		    
			<?php get_template_part('search', 'helper');  ?>
			
			
		</div>
	    		<?php get_template_part('footer', 'social'); ?>	
	</section>

<?php 
get_footer();

