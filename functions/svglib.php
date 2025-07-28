<?php
/**
* @package WordPress
* @subpackage FAU
* @since FAU 1.12
*/
// global $fau_used_svgs;

if ( ! function_exists( 'fau_use_svg' ) ) {
    /* 
     * Lade eine SVG und füge sie ein (return des Strings), falls sie noch nicht 
     * zuvor eingefügt wurde
     */
    function fau_use_svg($name = '', $width = 0, $height = 0, $class = '', $echo = true, $aria = array()) {
        if (empty($name)) {
            return;
        }
        global $fau_used_svgs;
        $slug = sanitize_title($name);
        fau_register_svg_symbol($name);

        if (fau_is_svg($slug)) {   
            $labelpointer = $role = $title = $desc = $arialabel = '';
            $ariahidden = false;
            if ((isset($aria)) && (is_array($aria))) {
                $uniq = wp_unique_id();
                $labelpointer = $role = '';
                if (isset($aria['title'])) {
                    $title = '<title id="'.$slug.'-title-'.$uniq.'">'.esc_attr($aria['title']).'</title>';
                    $labelpointer .= $slug.'-title-'.$uniq;
                }
                if (isset($aria['desc'])) {
                    $desc = '<desc id="'.$slug.'-desc-'.$uniq.'">'.esc_attr($aria['desc']).'</desc>';	
                    if (!empty($labelpointer)) {
                    $labelpointer .= ' ';
                    }
                    $labelpointer .= $slug.'-desc-'.$uniq;
                }
                if (isset($aria['labelledby'])) {
                    $labelpointer = esc_attr($aria['labelledby']);
                }
                if (isset($aria['hidden'])) {
                    $ariahidden = true;
                }
                if ($labelpointer) {
                    $arialabel = 'aria-labelledby="'.$labelpointer.'"';
                }
                if (isset($aria['role'])) {
                    $role = esc_attr($aria['role']);
                } elseif ($arialabel) {
                    $role = 'img'; 
                }
            }


            $res = '<svg';
            if (isset($height) && ($height > 0)) {
                $res .= ' height="'.$height.'"';
            }
            if (isset($width) && ($width > 0)) {
                $res .= ' width="'.$width.'"';
            }
            if ($arialabel) {
                $res .= ' '.$arialabel;
            }
            if ($ariahidden) {
                $res .= ' aria-hidden="true"';
            }
            if ($role) {
                $res .= ' role="'.$role.'"';
            }
            $res .= '>';
            if ($title) {
                $res .= $title;
            }
            if ($desc) {
                $res .= $desc;
            }
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

function fau_is_svg($name = '') {
    if (empty($name)) {
	    return;
    }
    global $fau_used_svgs;
    $slug = sanitize_title($name);
    if (isset($fau_used_svgs[$slug])) {   
        return true;
    }
    return false;
}


function fau_read_svg($name = '', $echo = true) {
  global $fau_used_svgs;
  
  if (!empty($name)) {
      $slug = sanitize_title($name);
      if (fau_is_svg($slug)) {
	    if ($echo) {
            return $fau_used_svgs[$slug]['symbol'];
	    }
        return true;
	  
      } else {
	  $predefined= fau_get_default_svg_symbol($slug);
	  if ($predefined) {
	      $fau_used_svgs[$slug]['symbol'] = $predefined;	
	       if ($echo) {
		    return $predefined;
	       } else {
		    return true; 
	       }
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
				$fau_used_svgs[$slug]['viewBox'] = $viewbox[1];
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
				$fau_used_svgs[$slug]['viewBox'] = '0 0 '.$width.' '.$height;
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
			if (!empty($titlea[0])) {
			    $symbol .= '<title>'.esc_html($name).'</title>';
			    $fau_used_svgs[$slug]['title'] = $titlea[0];
			}
			
			 // if there is a <desc>..</dec> within the content, we add it with the array for future use 
			preg_match_all('/<desc\s*([^<>]*)>(.*)<\/desc>/i', $innerpart, $desc);
			if (!empty($desc[0])) {
			    $fau_used_svgs[$slug]['desc'] = $desc[0];
			}
			
			$innerpart = $symbol.$innerpart.'</symbol>';
			
		    }
		     
		    $fau_used_svgs[$slug]['symbol'] = $innerpart;	  
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
	     preg_match_all('/<svg ([^>]+)>(.*)<\/svg>/si', $default_fau_svgsymbols[$slug], $matches);
	      if (isset($matches)) {
		     
		return $matches[2][0];
	     
	     
	      }
	 }
    }
    return;
}

// register SVG for inserting html to use over symbol
function fau_register_svg_symbol($name = '') {
    global $fau_used_svgs;

    if (empty($name)) 
	return false;

    $slug = sanitize_title($name);
     
    if (!fau_is_svg($slug)){
	fau_read_svg($slug, false);
    }
   
    $fau_used_svgs[$slug]['usesymbol'] = true;

    return true;
}

function fau_insert_svg_symbols() {
    global $fau_used_svgs;
     

    if (empty($fau_used_svgs)) {
        return;
    }
    $outputready = false;
    
    $out = "\n".'<svg class="fau-svg-definitions" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">';
    foreach ($fau_used_svgs as $name => $value) {
	    if ($fau_used_svgs[$name]['usesymbol']) {
            $symbol = $fau_used_svgs[$name]['symbol'];
            $out .= "\n\t".$symbol; 
            $outputready = true;
	    }
    }
    $out .= "\n".'</svg>'."\n";
    if ($outputready) {
        echo $out;    
    }
    return;
}
// add_action( 'wp_body_open', 'fau_insert_body_open_svgsymbols' );
add_action( 'wp_footer', 'fau_insert_svg_symbols' );


// register SVG for background-css use as inline style
function fau_register_svg_inline($name = '', $classdef = '', $addbgproperty = '', $color = '', $svgparams = '') {
    global $fau_used_svgs;

    if (empty($name)) 
    	return false;
    
    $slug = sanitize_title($name);
    
    if (empty($classdef)) 
	return false;
	
    if (!fau_is_svg($slug)){
	fau_read_svg($slug, false);
    }
   
    $fau_used_svgs[$slug]['useinlinecss'] = true;
    $fau_used_svgs[$slug]['classdef'] = $classdef;
    
     if (!empty($addbgproperty))
        $fau_used_svgs[$slug]['bgproperty'] = $addbgproperty;
     
     
    if (!empty($color))
        $fau_used_svgs[$slug]['color'] = $color;
    
    if (!empty($svgparams))
        $fau_used_svgs[$slug]['svgparams'] = $svgparams;
    
    return true;
}

// Insert Inline CSS from given SVGs
function fau_insert_svg_inlinecss() {
    global $fau_used_svgs;
    
   
    if (empty($fau_used_svgs)) {
	return;
    }
    
    $outputready = false;
    $out = "\n".'<style type="text/css">';
    
    foreach ($fau_used_svgs as  $name => $value) {
	    if (isset($fau_used_svgs[$name]['useinlinecss']) && ($fau_used_svgs[$name]['useinlinecss'])) {
		$innerpart = $fau_used_svgs[$name]['symbol'];
		$viewbox = 'viewBox="'.$fau_used_svgs[$name]['viewBox'].'"';
		
		preg_match('/<symbol\s*([^>]+)>(.*)<\/symbol>/', $innerpart, $output_array);
		
		if (!empty($output_array[0])) {
		    if (isset($output_array[2]) && (!empty($output_array[2]))) {
			    $svgcode = $output_array[2];

			    // remove title and desc
			    preg_replace('/<title>(.*)<\/title>/', '', $svgcode);
			    preg_replace('/<desc>(.*)<\/desc>/', '', $svgcode);
		    }	
		}

		if (!empty($fau_used_svgs[$name]['color'])) {
		    $color = $fau_used_svgs[$name]['color'];
		    $colorreplace = 'fill="'.$fau_used_svgs[$name]['color'].'"';
		    preg_replace('/fill="([a-z0-9#]+)"/gi', $colorreplace, $svgcode);
		}
		if (isset($fau_used_svgs[$name]['classdef'])) {
		    $classdef = $fau_used_svgs[$name]['classdef'];
		    $out .= $classdef.' { ';
		    $out .=  'background: url(\'data:image/svg+xml;charset=UTF8,<svg xmlns="http://www.w3.org/2000/svg" '.$viewbox;
		    if (!empty($fau_used_svgs[$name]['svgparams'])) {
			$out .= ' '.$fau_used_svgs[$name]['svgparams'];
		    }
		    $out .=  '>';

		    $out .= $svgcode;
		    $out .= '</svg>\')';
		    if ($fau_used_svgs[$name]['bgproperty']) {
			$out .= ' '.$fau_used_svgs[$name]['bgproperty'];
		    }
		    $out .= ';';
		    $out .= '}';
		    $outputready = true;
		}
	    }
    }
    $out .= "\n".'</style>'."\n";
    
    if ($outputready) {
	echo $out;    
    }
    
    return;
}
add_action( 'wp_footer', 'fau_insert_svg_inlinecss' );


