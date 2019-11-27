<?php
/*
* Gallery functions for theme, overwriting WordPress default gallery
* 
* @package WordPress
* @subpackage FAU
* @since FAU 1.10.54
*/


/*-----------------------------------------------------------------------------------*/
/* Change output for gallery
/*-----------------------------------------------------------------------------------*/

add_filter('post_gallery', 'fau_post_gallery', 10, 2);
if ( ! function_exists( 'fau_post_gallery' ) ) {
    function fau_post_gallery($output, $attr) {
	global $post;
	global $usejslibs;
	global $defaultoptions;


	if (isset($attr['orderby'])) {
	    $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
	    if (!$attr['orderby'])
		unset($attr['orderby']);
	}

	extract(shortcode_atts(array(
	    'order'	=> 'ASC',
	    'orderby'	=> 'menu_order ID',
	    'id'		=> $post->ID,
	    'columns'	=> 0,
	    'include'	=> '',
	    'exclude'	=> '',
	    'type'	=> 'default',
	    'captions'	=> 0,
	    'link'	=> 'post',
		// aus Wizard:
		// file = direkt zur mediendatei
		// post = null = Anhang Seite   (Im WordPress Wizzard ist dies der Default!)
		// none = nirgendwohin

	    'class'	=> '', 
	    'nodots'	> 0,

	), $attr));

	$id = intval($id);
	if ('RAND' == $order) $orderby = 'none';
	$class = sanitize_text_field( $class );
	if (!empty($include)) {
	    $include = preg_replace('/[^0-9,]+/', '', $include);
	    $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

	    $attachments = array();
	    foreach ($_attachments as $key => $val) {
		$attachments[$val->ID] = $_attachments[$key];
	    }
	}

	if (empty($attachments)) return '';

	$gridtype = 'default';

	$output = '<div class="image-gallery';
	if (!empty($class)) {
	    $output .= ' '.$class;
	}
	$output .= '">';
	
	if (isset( $attr['captions'])) {
	    $attr['captions'] = filter_var( $attr['captions'], FILTER_VALIDATE_BOOLEAN );
	} else {
	    $attr['captions'] = '';
	}
	
	if (!isset($attr['type'])) {
	    $attr['type'] = 'default';
	}

	if ($attr['type'] != 'default') {

	    $gridclass = 'flexgrid';
	    $gridtype = 'grid';
	    if (isset($attr['columns'])) {
		if ($attr['columns']==2) {
		    $gridclass = 'two';
		} elseif ($attr['columns']==3) {
		    $gridclass = 'three';
		} elseif ($attr['columns']==4) {
		    $gridclass = 'four';
		} elseif ($attr['columns']==5) {
		    $gridclass = 'five';
		}
	    }
	    // Overwrite by type
	    if ($attr['type'] == '2cols') {
		$gridclass = 'two';
	    }
	    if ($attr['type'] == '4cols') {
		$gridclass = 'four';
	    }

	}


	if ($gridtype == 'grid') {
	    // Images in grid view

	    $rand = rand();
	    $usesameheight = '';

	    $output .= '<div class="grid" aria-hidden="true" role="presentation">'."\n";

	    if ($gridclass=='flexgrid') {
		$output .= '<div class="flexgrid">'."\n";
	    } else {
		$output .= '<div class="'.$gridclass.'">'."\n";
		$output .= '<div class="row">'."\n";
	    }


	    $i = 0;


	    foreach ($attachments as $id => $attachment) {

		    $imgmeta = fau_get_image_attributs($id);
		    $img_full = wp_get_attachment_image_src($id, 'full');
		    $lightboxattr = '';
		    
		    $lightboxtitle = ( isset($imgmeta->post_excerpt) ? sanitize_text_field( $imgmeta->post_excerpt ) : '' );
		    
		    if (strlen(trim($lightboxtitle))>1) {
			$lightboxattr = ' title="'.$lightboxtitle.'"';
		    }

		    $linkalt = $imgmeta['alt'];
		    if ('none' !== $attr['link']) {
			    // Bei Bildern, die als Link fungieren beschreibt der alt das Linkziel, nicht das Bild.
			    if (!fau_empty($imgmeta['title'])) {
				$linkalt = __('Bild ','fau').$imgmeta['title'].' '.__('aufrufen','fau');
			    } else {
			       $linkalt = __('Bild aufrufen','fau');
			    }
			
		    } elseif (fau_empty( $imgmeta['alt'])) {
			// Kein Link aber Bild-Alt trotzdem leer. Nehme einen der 
			// optionalen anderen Felder der Bildmeta
			if (!fau_empty($imgmeta['description'])) {
			    $linkalt =  sanitize_text_field($imgmeta['description']);	
			} elseif (!fau_empty($imgmeta['title'])) {
			    $linkalt =  sanitize_text_field($imgmeta['title']);
			} elseif (!fau_empty($imgmeta['excerpt'])) {
			    $linkalt =  sanitize_text_field($imgmeta['excerpt']);
					    
			} else {
			    $linkalt = '';
			}
			
		    }
		    
		    if(isset( $attr['captions']) && ($attr['captions']==1) &&(!fau_empty($imgmeta['excerpt']))) {
			$output .= '<figure class="with-caption">';
		    } else {
			$output .= '<figure>';
		    }
		    if ('none' !== $attr['link']) {
			if ($attr['link']=='post') {
			    // Anhang Seite
			    $output .= '<a href="'.get_attachment_link( $id ).'">';		  
			} else {
			    // File
			    $output .= '<a href="'.fau_esc_url($img_full[0]).'" class="lightbox" rel="lightbox-'.$rand.'"'.$lightboxattr.'>';		  
			}
		    }

		    $output .= fau_get_image_htmlcode($id, 'gallery-full', $linkalt);
		    
		    if ('none' !== $attr['link']) {
			$output .= '</a>';
		    }

		    if(isset( $attr['captions']) && ($attr['captions']==1) && (!fau_empty($imgmeta['excerpt']))) {
			$output .= '<figcaption>'.$imgmeta['excerpt'].'</figcaption>';
		    }
		    $output .= '</figure>'."\n";
		    $i++;

		    if (($gridclass == 'two') && ($i % 2 == 0)) {
			$output .= '</div><div class="row">'."\n";  
		    } elseif (($gridclass == 'three') && ($i % 3 == 0)) {
			$output .= '</div><div class="row">'."\n";
		    } elseif (($gridclass == 'four') && ($i % 4 == 0)) {
			$output .= '</div><div class="row">'."\n";
		    }
	    }

	    $output .= '</div>'."\n";
	    if ($gridclass !='flexgrid') {
		$output .= '</div>'."\n";
	    }
	    $output .= '</div>'."\n";


	} else {
	    if ((!$attr['captions']) && (get_theme_mod('galery_force_caption_onslick'))) {
		$attr['captions'] = 1;
	    }
	    wp_enqueue_script('fau-js-heroslider');

	    $rand = rand();	    
	    $output .= "<div id=\"slider-$rand\" class=\"gallery-slick\" aria-hidden=\"true\" role=\"presentation\">\n";	
	    $output .= "<div class=\"slider-for-$rand\">\n";

	    foreach ($attachments as $id => $attachment) {
		$img_full = wp_get_attachment_image_src($id, 'full');
		$imgmeta = fau_get_image_attributs($id);

		$output .= '<div class="item">';	
		$output .= fau_get_image_htmlcode($id, 'gallery-full', '','',array('role' => 'presentation'));

		$link_origin = get_theme_mod('galery_link_original');
		
		if (($link_origin) || ($attr['captions'])) {
		    $output .= '<figcaption class="gallery-image-caption">';
			$lightboxattr = '';
			if (($attr['captions']) && (!fau_empty($imgmeta['excerpt']))) {
			    $output .= $imgmeta['excerpt']; 
			    $lightboxtitle = sanitize_text_field($imgmeta['excerpt']);
			    if (strlen(trim($lightboxtitle))>1) {
				$lightboxattr = ' title="'.$lightboxtitle.'"';
			    }
			}
			if (($link_origin) && isset($attr['link']) && ($attr['link'] != 'none')) {
			    if (!fau_empty($imgmeta['excerpt'])) { 
				$output .= '<br>'; 			
			    }
			    $output .= '<span class="linkorigin">(<a href="'.fau_esc_url($img_full[0]).'" '.$lightboxattr.' class="lightbox" rel="lightbox-'.$rand.'">'.__('Vergrößern','fau').'</a>)</span>';
			}
		    $output .='</figcaption>';
		}

		$output .='</div>';

	    }

	    $output .= "</div>\n";
	    $output .= "<div class=\"slider-nav-width slider-nav-$rand\">\n";


	    foreach ($attachments as $id => $attachment) {
		$output .= '<div>';
		$imgmeta = fau_get_image_attributs($id);
		$alttext = sanitize_text_field($imgmeta['excerpt']);
		$output .= fau_get_image_htmlcode($id, 'rwd-480-3-2', $alttext);
		$output .= '</div>';
	    }

	    $output .= "</div>\n";	
	    $output .= "<script type=\"text/javascript\"> jQuery(document).ready(function($) {";	
	    $output .= "$('.slider-for-$rand').slick({slidesToShow: 1,slidesToScroll: 1, arrows: false, fade: true,adaptiveHeight: true,asNavFor: '.slider-nav-$rand' });";

	    $output .= "$('.slider-nav-$rand').slick({ slidesToShow: 4,  slidesToScroll: 1,   asNavFor: '.slider-for-$rand', centerMode: true,focusOnSelect: true, centerPadding: 5";
	    if ((isset($attr['nodots']) && $attr['nodots']==true)) {
		$output .= ", dots: false";
	    } else {
		$output .= ", dots: true";
	    }

	    $output .= ", responsive: [ 
	    {
		breakpoint: 920,
		settings: {
		  arrows: true,
		  slidesToShow: 3
		}
	      },
	    {
		breakpoint: 768,
		settings: {
		  arrows: true,
		  slidesToShow: 2
		}
	      },
	      {
		breakpoint: 480,
		settings: {
		  arrows: true,
		  slidesToShow: 2,
		  dots: false
		}
	      }
	    ]";
	    $output .= "});";
	    $output .= "});</script>";
	    $output .= "</div>";	

	}


	$output .= "</div>\n";
	return $output;
    }
}
/*-----------------------------------------------------------------------------------*/
/* EOF
/*-----------------------------------------------------------------------------------*/