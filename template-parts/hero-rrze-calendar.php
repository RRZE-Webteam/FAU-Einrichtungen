<?php
/**
 * Template part for small hero
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */
 
global $options; 
global $options, $rrze_calendar_data, $rrze_calendar_endpoint_url, $rrze_calendar_endpoint_name;

if (!class_exists('RRZE_Calendar') || empty($rrze_calendar_data)) {
    wp_die();
}
$event = &$rrze_calendar_data;


$breadcrumb = '';
if (isset($options['breadcrumb_root'])) {
    if ($options['breadcrumb_withtitle']) {
        $breadcrumb .= '<h3 class="breadcrumb_sitetitle" role="presentation">'.get_bloginfo('title').'</h3>';
        $breadcrumb .= "\n";
    }
    $breadcrumb .= '<nav aria-labelledby="bc-title" class="breadcrumbs">'; 
    $breadcrumb .= '<h4 class="screen-reader-text" id="bc-title">'.__('Sie befinden sich hier:','fau').'</h4>';
    $breadcrumb .= '<a data-wpel-link="internal" href="' . site_url('/') . '">' . $options['breadcrumb_root'] . '</a>' . $options['breadcrumb_delimiter'];
    $breadcrumb .= '<a data-wpel-link="internal" href="' . $rrze_calendar_endpoint_url . '">' . $rrze_calendar_endpoint_name . '</a>';
}
?>

    <section id="hero" class="hero-small">
	<div class="container">
		<div class="row">
		    <div class="col-xs-12">
			<?php 
			echo $breadcrumb;
			?>
			
		    </div>
		</div>
		<div class="row">
		    <div class="col-xs-12">
			<h1><?php echo $event->summary;; ?></h1>
		    </div>
		</div>
	</div>
    </section>