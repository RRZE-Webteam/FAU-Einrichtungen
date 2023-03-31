<?php
/**
 * The template for displaying 404 pages (Zugriff nicht erlaubt).
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header();
?>
 <div id="content">
    <div class="content-container">	   
	<div class="content-row">
	    <div class="errorpage-content ">
		     <main>

				<h1 class="screen-reader-text"><?php echo __('Zugriff nicht gestattet','fau');  ?></h1>
				<div class="error-notice">
				    <p class="hinweis">
					    <strong><?php _e('Es tut uns leid.','fau'); ?></strong>
				    </p>
				    <p>
					   <?php _e('Leider dürfen Sie auf diese Seite nicht zugreifen.','fau'); ?>
				    </p>						
				</div>	    
			      <?php 
				get_template_part('template-parts/error', 'trysearch');  
				get_template_part('template-parts/search', 'helper'); 
				?>   
		      </main> 
	    </div>
	    <?php if ( is_plugin_active( 'FAU-Fehlermeldungen/fau-fehlermeldungen.php' ) ) { ?>
	      <aside class="portalpage-sidebar" aria-label="<?php echo __('Sidebar','fau');?>">
	      <?php 
	       echo do_shortcode('[fau_fehlermeldungen type="403" fulltext="false" ]'); 
	      ?>
	     </aside>

	    <?php } ?>
	</div>
    </div>
 </div>
<?php
get_template_part('template-parts/footer', 'social'); 
get_footer();
