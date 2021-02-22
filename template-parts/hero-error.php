<?php
/**
 * Template part for error messages
 * 
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */
 

 if(isset($_REQUEST['status']) && $_REQUEST['status'] == 401)  {
    $msg = __('Anmeldung fehlgeschlagen','fau');
} elseif (isset($_REQUEST['status']) && $_REQUEST['status'] == 403) {
    $msg = __('Zugriff nicht gestattet','fau');
} else {
    $msg = __('Seite nicht gefunden','fau');
}
					    
?>


	<section id="hero" class="hero-small">
		<div class="container hero-content">
		    <div class="row">
			<div class="col-xs-12">
				<div class="breadcrumbs">
					<a href="<?php echo fau_esc_url( home_url( '/' ) ); ?>"><?php echo get_theme_mod('breadcrumb_root'); ?></a>
				</div>

			</div>
		    </div>
		    <div class="row">
			<div class="col-xs-12">
				 <p class="presentationtitle"  aria-hidden="true" role="presentation"><?php echo $msg; ?></p>
			</div>
		    </div>
		</div>
             <?php get_template_part('template-parts/hero', 'siegel'); ?>
	</section>