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
global $options; 
					    
?>


	<section id="hero" class="hero-small">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="breadcrumbs">
						<a href="<?php echo fau_esc_url( home_url( '/' ) ); ?>"><?php echo $options['breadcrumb_root']; ?></a>
					</div>

				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<h1><?php echo $msg; ?></h1>
				</div>
			</div>
		</div>
	</section>