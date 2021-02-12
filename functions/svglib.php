<?php
/**
* @package WordPress
* @subpackage FAU
* @since FAU 1.12
*/
// global $fau_used_svgs;

if ( ! function_exists( 'fau_get_svg' ) ) {
    /* 
     * Lade eine SVG und füge sie ein (return des Strings), falls sie noch nicht 
     * zuvor eingefügt wurde
     */
    function fau_get_svg($name = '', $width = 0, $height = 0, $class = '', $echo = true) {
	if (empty($name)) {
	    return;
	}
	global $fau_used_svgs;
	$slug = sanitize_title($name);
	if (isset($fau_used_svgs[$slug])) {   
	
	   $svgcontent = fau_read_svg($name);

	    $res = '<svg';
	    if (isset($height) && ($height > 0)) {
		$res .= ' height="'.$height.'"';
	    }
	    if (isset($width) && ($width > 0)) {
		$res .= ' width="'.$width.'"';
	    }
	    $res .= '>';
	     $res .= '<use xlink:href="#'.$slug.'"';
	     if (isset($class) && (!empty($class))) {
		$res .= ' class="'.$class.'"';		
	    }
	    $res .= '/>';
	    $res .= '</svg>';
            if ($echo) {
                echo $res;
                return true;
            } else {
                return $res;
            }
	   
	}
	return false;
    }
}

function fau_read_svg($name = '', $echo = true) {
  global $fau_used_svgs;
  
  if (!empty($name)) {
      $slug = sanitize_title($name);
      if (isset($fau_used_svgs[$slug])) {
	  return $fau_used_svgs[$slug];	  
      } else {
	  $predefined= fau_get_default_svg_symbol($slug);
	  if ($predefined) {
	      $fau_used_svgs[$slug] = $predefined;	  
	      return $predefined;
	  }
	  
	  
	  // Not found in predfined symbols. Therfor look in the svg files
	  global $defaultoptions;
	  $file = $defaultoptions['src-svglib_dir'].$slug.".svg";
	  $svgcontent = file_get_contents($file);
	  if ($svgcontent) {
              // first remove tab or spaces at the beginning of lines 
              $svgcontent = preg_replace("/^[\s\t]+/m", "", $svgcontent);
               // and we remove line endings
              $svgcontent = preg_replace("/[\n\r]/", "", $svgcontent);
              
	      // Now check the file and transform its content into a symbol-string
	      
	      // why not the PHP DOM Functions? Cause all we do are some little RegExps! 
	      // We dont need to bloat memory just to make a regexp!
	      // 
	      // we read everything between <svg> .. </svg>
	      // if there is no content between <svg> .. </svg>, we break
	     
	      preg_match_all('/<svg ([^>]+)>(.*)<\/svg>/si', $svgcontent, $matches);
	      if (isset($matches)) {
		     
		$innerpart = $matches[2][0];
		$svgattributs = $matches[1][0];
		      
		// then we insert this in a <symbol> .. </symbol>, if there is no <symbol> in the innerpart
		 if (!empty($innerpart)) {
		    preg_match_all('/<symbol ([^>]+)>/i', $innerpart, $output_array);
		  
		    if (empty($output_array[0])) {
			// no <symbol> found, we generate it
			// the id-Attribut is the slug
			
			$symbol = '<symbol id="'.$slug.'"';
			
			 // we try to find out the viewBox by parsing the <svg>-Attributs and insert them into the <symbol> 
			
			if ($svgattributs) {
			    preg_match('/viewbox="([\d\s]+)"/i', $svgattributs, $viewbox);
			    if (isset($viewbox[1]) && (!empty($viewbox[1]))) {
				$symbol .= ' viewBox="'.$viewbox[1].'"';
			    } else {
				 // if there is no viewBox in <svg> but a with/heigh, we create the viewbox out of them
				// width="216" height="42" viewBox="0 0 216 42"
				
				preg_match('/width="([\d+)"/i', $svgattributs, $widtha);
				if (isset($widtha[1]) && (!empty($widtha[1]))) {
				    $width = $widtha[1];
				}
				preg_match('/height="([\d+)"/i', $svgattributs, $heighta);
				if (isset($heighta[1]) && (!empty($heighta[1]))) {
				    $height = $heighta[1];
				}
				$symbol .= ' viewBox="0 0 '.$width.' '.$height.'"';
			    }
			   
			    // Check for Class
			    preg_match('/class="([a-z0-9\-_]+)"/i', $svgattributs, $classdata);
			   if (isset($classdata[1]) && (!empty($classdata[1]))) {
				$symbol .= ' class="'.$classdata[1].'"';
			   }
			}
			$symbol .= '>';
			
			 // if there is no <title>..</title> within the content, we add it with the slug name 
			preg_match_all('/<title\s*([^<>]*)>(.*)<\/title>/i', $innerpart, $titlea);
			if (empty($titlea[0])) {
			    $symbol .= '<title>'.esc_html($name).'</title>';
			}
			$innerpart = $symbol.$innerpart.'</symbol>';
			
		    }
		     
		    $fau_used_svgs[$slug] = $innerpart;	  
		    if ($echo) {
			return $innerpart;
		    }
		    return true;
		    
		 }
		} 
	    }
	  
      }
  } 
  return false;
} 

function fau_get_default_svg_symbol($slug = '') {
    if (!empty($slug)) {
	 global $default_fau_svgsymbols;
	 if (isset($default_fau_svgsymbols[$slug])) {
	     return $default_fau_svgsymbols[$slug];
	 }
    }
    return;
}

function fau_insert_svgsymbols() {
    global $fau_used_svgs;
     
    if (empty($fau_used_svgs)) {
	return;
    }
    
    $out = "\n".'<svg class="fau-svg-definitions" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">';
    foreach ($fau_used_svgs as $svg => $symbol) {
	$out .= "\n\t".$symbol; 
    }
    $out .= "\n".'</svg>'."\n";
    
    echo $out;    
    return;
}
// add_action( 'wp_body_open', 'fau_insert_body_open_svgsymbols' );
add_action( 'wp_footer', 'fau_insert_svgsymbols' );

