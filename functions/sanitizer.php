<?php
/**
* @package WordPress
* @subpackage FAU
* @since FAU 1.8
*/

/*-----------------------------------------------------------------------------------*/
/* Sanitize optional classes for hr shortcodes
/*-----------------------------------------------------------------------------------*/
function fau_sanitize_hr_shortcode( $fau_hr_styles ) {
	
    if (isset($fau_hr_styles)) {
	$fau_hr_styles  = esc_attr( trim($fau_hr_styles) );
    }
    if (fau_empty($fau_hr_styles)) return;
	
	if ( ! in_array( $fau_hr_styles, array( 'big', 'line' ) ) ) {
		$fau_hr_styles = '';
		
	}
	return $fau_hr_styles;
}

/*-----------------------------------------------------------------------------------*/
/* Sanitize string with trimming at first
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'fau_san' ) ) :  
    function fau_san($s){
	return filter_var(trim($s), FILTER_SANITIZE_STRING);
    }
endif;    


/*-----------------------------------------------------------------------------------*/
/* Empty function, which strips out empty chars
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'fau_empty' ) ) :  
    function fau_empty($string){ 
	 $string = trim($string); 
	 if(!is_numeric($string)) return empty($string); 
	 return FALSE; 
    } 
endif;    

/*--------------------------------------------------------------------*/
/* Sanitize range
/*--------------------------------------------------------------------*/
function fau_sanitize_customizer_range( $value ) {
    return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
}
/*--------------------------------------------------------------------*/
/* Sanitize bool
/*--------------------------------------------------------------------*/
function fau_sanitize_customizer_bool( $value ) {
    if ( ! in_array( $value, array( true, false ) ) )
        $value = false;
 
    return $value;
}
/*--------------------------------------------------------------------*/
/* Sanitize known social media for classes
/*--------------------------------------------------------------------*/
function fau_sanitize_socialmedia_classes( $socialmedia ) {
    if (!empty($socialmedia)) {
	$socialmedia = esc_attr($socialmedia);
	$socialmedia = strtolower($socialmedia);
	if ( ! in_array( $socialmedia, 
		array( 'facebook', 'twitter', 'instagram', 'pinterest', 'delicious', 'diaspora', 'gplus', 
			'flickr', 'itunes', 'skype',  'xing',  'tumblr', 'github', 'feed',  'wikipedia'  ) ) ) {
		$socialmedia = '';
	}
    }
    return $socialmedia;
}
