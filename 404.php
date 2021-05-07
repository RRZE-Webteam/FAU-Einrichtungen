<?php
/**
 * The template for displaying 404 pages (Not Found).
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
			    <div class="col-xs-12">
			    <main<?php echo fau_get_page_langcode($post->ID);?> id="droppoint" class="error-content">
				<h1 class="screen-reader-text"><?php echo __('Seite nicht gefunden','fau'); ?></h1>

				<div class="error-notice">
				    <p class="hinweis">
					    <strong><?php _e('Es tut uns leid.','fau'); ?></strong>
				    </p>
				    <p>
					    <?php _e('Die von Ihnen aufgerufene Seite existiert nicht oder ihre Adresse hat sich durch Änderungen an der Seitenstruktur geändert.','fau'); ?>
				    </p>

				</div>
				<?php 
				get_template_part('template-parts/error', 'siegel');   
				get_template_part('template-parts/error', 'trysearch');  
				?>
    
			    </main>
			</div>
			<?php get_template_part('template-parts/search', 'helper');  ?>
		    </div>
		</div>
    		
	</section>
<?php get_template_part('template-parts/footer', 'social'); ?>	
<?php 
get_footer();

