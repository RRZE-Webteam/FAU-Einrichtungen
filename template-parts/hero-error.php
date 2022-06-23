<?php
/**
 * Template part for error messages
 * 
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */

if (isset($_REQUEST['status']) && $_REQUEST['status'] == 401) {
    $status = 401;
} elseif (isset($_REQUEST['status']) && $_REQUEST['status'] == 403) {
    $status = 403;
} elseif (isset($_REQUEST['status']) && $_REQUEST['status'] == 404) {
    $status = 404;
} elseif(is_404()) {
    $status = 404;
} else {
    $status = 400;
}


$msg = '';

switch($status) {
    case 401:
	$msg = __('Anmeldung fehlgeschlagen','fau');
	break;
    case 403:
	$msg = __('Zugriff nicht gestattet','fau');
	break;
    case 404:
	$msg = __('Seite nicht gefunden','fau');
	break;

   default:
	$msg = get_the_title();
       
    
}    
?>
<section id="hero" class="hero-small">
	<div class="hero-container hero-content">
	    <div class="hero-row">
		    <?php fau_breadcrumb(); ?>
	    </div>
	    <div class="hero-row" aria-hidden="true" role="presentation">
		 <p class="presentationtitle"><?php echo __('Fehler','fau'); if (!empty($msg)) { echo ': '.$msg; } ?></p>
	    </div>
	</div>
</section>

