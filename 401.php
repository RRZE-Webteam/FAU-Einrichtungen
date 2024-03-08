<?php
/**
 * The template for displaying 401 pages (Unauthorisiert).
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header();
$error_hidedefaultnotice = get_theme_mod('advanced_error_sidebar_hidedefaultnotice');

?>
 <div id="content">
    <div class="content-container">	   
	<div class="content-row">
	    <div class="errorpage-content ">
		     <main>

				<h1 class="screen-reader-text"><?php echo __('Anmeldung fehlgeschlagen','fau'); ?></h1>
                <?php if (empty($error_hidedefaultnotice)) { ?> 
				<div class="error-notice alert clearfix clear alert-warning">
				    <p class="hinweis">
					    <strong><?php _e('Es tut uns leid.','fau'); ?></strong>
				    </p>
				    <p>
					    <?php _e('Leider ist Ihre Anmeldung fehlgeschlagen.','fau'); ?>
				    </p>						
				</div>	  
			    <?php 
                }
				get_template_part('template-parts/error', 'trysearch');  
				get_template_part('template-parts/search', 'helper'); 
				?>   
		      </main> 
	    </div>
	    <?php if ( is_plugin_active( 'FAU-Fehlermeldungen/fau-fehlermeldungen.php' ) ) { ?>
	      <aside class="portalpage-sidebar" aria-label="<?php echo __('Sidebar','fau');?>">
	      <?php 
	       echo do_shortcode('[fau_fehlermeldungen type="401" fulltext="false" ]'); 
	      ?>
	     </aside>

	    <?php } ?>
	</div>
    </div>
 </div>
<?php
get_footer();