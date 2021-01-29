<?php
/**
* @package WordPress
* @subpackage FAU
* @since FAU 1.12
*/

global $fau_used_svgs;

if ( ! function_exists( 'fau_get_svg' ) ) {
    /* 
     * Lade eine SVG und füge sie ein (return des Strings), falls sie noch nicht 
     * zuvor eingefügt wurde
     */
    function fau_get_svg($name = '', $width = 0, $height = 0, $class = '', $show = true) {
	if (empty($name)) {
	    return;
	}
	global $fau_used_svgs;
	
	$slug = sanitize_title($name);
	if (!isset($fau_used_svgs[$slug])) {   
	    
	    $file = get_template_directory()."/src/svglib/".$slug.".svg";
	    $svgcontent = file_get_contents($file);
	    // look for <symbol id=""> to get the predefined id if set
	    preg_match('/<symbol\s+id="([a-z0-9\-]+)"/i', $svgcontent, $matches, PREG_OFFSET_CAPTURE);
	    if ((isset($matches)) && (isset($matches[1]))) {
		$id = $matches[1][0];
	    } else {
		$id = $slug;
	    }
	
	    echo $svgcontent;
	    $fau_used_svgs[$slug] = $id;
	    if ($show) {
		$res = '<svg';
		if (isset($height) && ($height > 0)) {
		    $res .= ' height="'.$height.'"';
		}
		if (isset($width) && ($width > 0)) {
		    $res .= ' width="'.$width.'"';
		}
		$res .= '>';
		 $res .= '<use xlink:href="#'.$fau_used_svgs[$slug].'"';
		 if (isset($class) && (!empty($class))) {
		    $res .= ' class="'.$class.'"';		
		}
		$res .= '/>';
		$res .= '</svg>';
		echo $res;
	    }
	    $fau_used_svgs[$slug] = $id;
	} else {
	    // SVG already defined. so we return the use class only
	    
	    
	    $res = '<svg';
	    if (isset($height) && ($height > 0)) {
		$res .= ' height="'.$height.'"';
	    }
	    if (isset($width) && ($width > 0)) {
		$res .= ' width="'.$width.'"';
	    }
	    $res .= '>';
	     $res .= '<use xlink:href="#'.$fau_used_svgs[$slug].'"';
	     if (isset($class) && (!empty($class))) {
		$res .= ' class="'.$class.'"';		
	    }
	    $res .= '/>';
	    $res .= '</svg>';
	    echo $res;
	}
	return;
    }
}