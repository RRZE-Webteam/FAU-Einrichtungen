<?php
/*-----------------------------------------------------------------------------------*/
/* Native Theme Accordions, if no plugin is active
/*-----------------------------------------------------------------------------------*/

$plugin_there = false;
if ( is_plugin_active( 'rrze-elements/rrze-elements.php' ) ) {
    $plugin_there = true;
}
if ($plugin_there == false) {    
    fau_shortcodes_accordions();
}
/*-----------------------------------------------------------------------------------*/
/* Add Accordion shortcodes
/*-----------------------------------------------------------------------------------*/
function fau_shortcodes_accordions() {
            add_shortcode('collapsibles', 'fau_collapsibles' );
            add_shortcode('accordion', 'fau_collapsibles' );
            add_shortcode('accordionsub',  'fau_collapsibles' );
                // Define more as one shortcode name to allow nestet accordions

            add_shortcode('collapse',  'fau_collapse' );
            add_shortcode('accordion-item',  'fau_collapse' );
                // Define more as one shortcode name to allow nestet accordions	
}
/*-----------------------------------------------------------------------------------*/
/* Accordion Element
/*-----------------------------------------------------------------------------------*/
if  (! function_exists( 'fau_collapsibles' ) ) {
    function fau_collapsibles( $atts, $content = null ) {

	if( isset($GLOBALS['collapsibles_count']) )
	    $GLOBALS['collapsibles_count']++;
	else
	    $GLOBALS['collapsibles_count'] = 0;

	$defaults = array();
	extract( shortcode_atts( $defaults, $atts ) );

	$output = '<div class="alert alert-warning">Hinweis: Diese Accordion-Funktion ist Teil des Themes und veraltet. Sie wird bald entfernt. Um weiterhin Accordions zu verwenden, aktivieren Sie bitte das Elements-Plugin.</div>';

	$output .= '<div class="accordion" id="accordion-' . $GLOBALS['collapsibles_count'] . '">';
	$output .= do_shortcode( $content );
	$output .= '</div>';

	return $output;
    }
}
/*-----------------------------------------------------------------------------------*/
/* Accordion Rahmen
/*-----------------------------------------------------------------------------------*/
if  (! function_exists( 'fau_collapse' ) ) {
    function fau_collapse( $atts, $content = null ) {

	if( !isset($GLOBALS['current_collapse']) )
	    $GLOBALS['current_collapse'] = 0;
	else 
	    $GLOBALS['current_collapse']++;


	$defaults = array( 'title' => 'Tab', 'color' => '', 'id' => '', 'load' => '', 'name' => '', );
	extract( shortcode_atts( $defaults, $atts ) );

	$addclass = '';

	$title = esc_attr($title);
	$name = esc_attr($name);
	$color = $color ? ' ' . esc_attr( $color ) : '';
	$load = $load ? ' ' . esc_attr( $load ) : '';

	if (!empty($load)) {
	    $addclass .= " ".$load;
	}

	$id = intval($id) ? intval($id) : 0;

	$output = getAccordionbyTheme($id,$title,$color,$load,$name,$content);
	

	
	return $output;
    }
}