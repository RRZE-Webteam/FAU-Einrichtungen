<?php
/**
 * Additional features to allow styling of the templates
 */

 /*-----------------------------------------------------------------------------------*/
 /* Extends the default WordPress body classes
 /*-----------------------------------------------------------------------------------*/
 function fau_body_class( $classes ) {
 
	 // Additional body classes for Meta WIdget (once only language switcher)
     if (is_workflow_translation_active()) {
	 if ( is_active_sidebar( 'language-switcher'  )) {
		 $classes[] = 'active-meta-widget';
	 }
     }

     if (function_exists( 'wpel_init' )) {
	 $classes[] = 'wp-external-links';
     }
     
    return $classes;
 }
 add_filter( 'body_class', 'fau_body_class' );
/*-----------------------------------------------------------------------------------*/
/* Add Class for protected pages
/*-----------------------------------------------------------------------------------*/
 function fau_protected_attribute ($classes, $item) {
	if($item->post_password != '')	{
		$classes[] = 'protected-page';
	}
	return $classes;
}
add_filter('page_css_class', 'fau_protected_attribute', 10, 3);

/*-----------------------------------------------------------------------------------*/
/* Define errorpages 401 and 403
/*-----------------------------------------------------------------------------------*/
function fau_prefix_custom_site_icon_size( $sizes ) {
   $sizes[] = 64;
   $sizes[] = 120;
   return $sizes;
}
add_filter( 'site_icon_image_sizes', 'fau_prefix_custom_site_icon_size' );
 
function fau_prefix_custom_site_icon_tag( $meta_tags ) {
   $meta_tags[] = sprintf( '<link rel="icon" href="%s" sizes="64x64" />', esc_url( get_site_icon_url( null, 64 ) ) );
   $meta_tags[] = sprintf( '<link rel="icon" href="%s" sizes="120x120" />', esc_url( get_site_icon_url( null, 120 ) ) );
 
   return $meta_tags;
}
add_filter( 'site_icon_meta_tags', 'fau_prefix_custom_site_icon_tag' );
/*-----------------------------------------------------------------------------------*/
/* Define errorpages 401 and 403
/*-----------------------------------------------------------------------------------*/
function custom_error_pages() {
    global $wp_query;
 
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 403)  {
        $wp_query->is_404 = FALSE;
        $wp_query->is_page = TRUE;
        $wp_query->is_singular = TRUE;
        $wp_query->is_single = FALSE;
        $wp_query->is_home = FALSE;
        $wp_query->is_archive = FALSE;
        $wp_query->is_category = FALSE;
        add_filter('wp_title','custom_error_title',65000,2);
        add_filter('body_class','custom_error_class');
        status_header(403);
        get_template_part('403');
        exit;
    }
 
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 401)  {
        $wp_query->is_404 = FALSE;
        $wp_query->is_page = TRUE;
        $wp_query->is_singular = TRUE;
        $wp_query->is_single = FALSE;
        $wp_query->is_home = FALSE;
        $wp_query->is_archive = FALSE;
        $wp_query->is_category = FALSE;
        add_filter('wp_title','custom_error_title',65000,2);
        add_filter('body_class','custom_error_class');
        status_header(401);
        get_template_part('401');
        exit;
    }
}
 
function custom_error_title($title='',$sep='') {
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 403)
        return "Forbidden ".$sep." ".get_bloginfo('name');
 
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 401)
        return "Unauthorized ".$sep." ".get_bloginfo('name');
}
 
function custom_error_class($classes) {
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 403)  {
        $classes[]="error403";
        return $classes;
    }
 
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 401)  {
        $classes[]="error401";
        return $classes;
    }
}
 
add_action('wp','custom_error_pages');

/*-----------------------------------------------------------------------------------*/
/* Surround embeddings with div class
/*-----------------------------------------------------------------------------------*/
function add_video_embed_note($html, $url, $attr) {
	return '<div class="oembed">'.$html.'</div>';
}
add_filter('embed_oembed_html', 'add_video_embed_note', 10, 3);





/*-----------------------------------------------------------------------------------*/
/*  Anzeige Suchergebnisse
/*-----------------------------------------------------------------------------------*/
function fau_display_search_resultitem($withsidebar = 1) {
    global $post;
    global $options;
    
    $output = '';
    $withthumb = $options['search_display_post_thumbnails'];
    $withcats =  $options['search_display_post_cats'];
    $withtypenote = $options['search_display_typenote'];
    $attachment = array();
    
    if (isset($post) && isset($post->ID)) {
	
	$link = get_post_meta( $post->ID, 'external_link', true );
	$external = 0;
	if (isset($link) && (filter_var($link, FILTER_VALIDATE_URL))) {
	    $external = 1;
	} else {
	    $link = fau_make_link_relative(get_permalink($post->ID));
	}
	
	$type = get_post_type();
	$typeclass = "res-".$type;
	$output .= '<article class="search-result '.$typeclass.'">'."\n";
	$output .= "\t<h3><a ";
	if ($external==1) {
	    $output .= 'class="ext-link" ';
	}
	$output .= "href=\"".$link."\">".get_the_title()."</a></h3>\n";
	
	$typeinfo = get_post_type_object( $type );
	
	
	if ( $type == 'post') {
	    $typestr = '<div class="search-meta">';
	    if ($withtypenote == true) { 
		
		
		if ($external == 1) {
		    $typestr .= '<span class="post-meta-news-external"> ';
		    $typestr .= __('Externer ', 'fau'); 
		} else {
		    $typestr .= '<span class="post-meta-news"> ';
		}
		
		$typestr .= __('Beitrag', 'fau'); 
		$typestr .= '</span>';
	    }
	    $categories = get_the_category();
	    $separator = ', ';
	    $thiscatstr = '';
	    if(($withcats==true) && ($categories)){
		$typestr .= '<span class="post-meta-category"> ';
		$typestr .= __('Kategorie', 'fau');
		$typestr .= ': ';
		foreach($categories as $category) {
		    $thiscatstr .= '<a href="'.get_category_link( $category->term_id ).'">'.$category->cat_name.'</a>'.$separator;
		}
		$typestr .= trim($thiscatstr, $separator);
		$typestr .= '</span> ';
	    }
	    $istopevent  = get_post_meta( $post->ID, 'topevent_active', true ); 
	    $topevent_date = get_post_meta( $post->ID, 'topevent_date', true );
	    $topevent_date_time =0;
	    if ($topevent_date) {
		$topevent_date_time = strtotime( $topevent_date );
	    }
	    if (($topevent_date_time>0) && ($istopevent == true)) {
		    $typestr .= '<span class="post-meta-date"> ';
		    $typestr .= date_i18n( get_option( 'date_format' ), strtotime( $topevent_date ) ); 
		    $typestr .= ' (';
		    $typestr .= __('Veranstaltungshinweis', 'fau');
		    $typestr .= ')';
		    $typestr .= '</span>';
			
	     } else {
		$typestr .= '<span class="post-meta-date"> ';
		$typestr .= get_the_date();
		$typestr .= '</span>';
	     }
	    $typestr .= '</div>'."\n";
	} elseif ($type == 'person') {
	    $typestr = '<div class="search-meta">';
	    $typestr .= '<span class="post-meta-kontakt"> ';
	    $typestr .= $typeinfo->labels->singular_name; 
	    $typestr .= '</span>';
	    $typestr .= '</div>'."\n";
	    
	} elseif ($type == 'event') {
	    $typestr = '<div class="search-meta">';
	    $typestr .= '<span class="post-meta-event"> ';
	    $typestr .= __('Veranstaltungshinweis', 'fau');
	    $typestr .= '</span>';
	    $typestr .= '</div>'."\n";
	} elseif ($type == 'attachment') {    
	    
	    $attachment = wp_prepare_attachment_for_js($post->ID);
	    $filesize = isset($attachment['filesizeHumanReadable']) ? $attachment['filesizeHumanReadable'] : '';
	    $filesize = (isset($attachment['filesize']) && (!isset($filesize))) ? $attachment['filesize'] : $filesize;
	    $filesize = (isset($attachment['filesizeInBytes']) && (!isset($filesize))) ? $attachment['filesizeInBytes']." Bytes" : $filesize;
	   
	    $filetype = wp_check_filetype( $attachment['url'] );
	     
	    $image = wp_get_attachment_image( $attachment['id'] ); 
	    if (!empty($image)) {
		$attachment['image'] = $image;
	    
	    }
	    
	    $typestr = '<div class="search-meta">';
	    $typestr .= '<span class="post-meta-attachment">';	    
	    $typestr .= ' <span class="dateityp">'.$filetype['ext'].'</span> ';
	    $typestr .= __('Datei', 'fau');	  
	    $typestr .= '</span>';
	    
	    $typestr .= ' <span class="post-meta-date"> ';
	    $typestr .= get_the_date();	   
	    
	    if (get_the_date() !=get_the_modified_date()) { 
		$typestr .= ' ('.__('Erstellungsdatum', 'fau').')';
		$typestr .= '</span>';	
		$typestr .= ' <span class="post-meta-date"> ';
		$typestr .= get_the_modified_date();	   
		$typestr .= ' ('.__('Letzte Änderung', 'fau').')';		
	    }
	    $typestr .= '</span>';		

	   
	    $typestr .= ' <span class="download">';
	    $typestr .= ' <a href="'.fau_esc_url($attachment['url']).'">'.__('Download','fau').'</a>'; 
	    
	    $typestr .= ' <span class="filesize">(<span class="unsichtbar">';
	    $typestr .= __('Größe:', 'fau'). ' </span>'.$filesize; 
	    $typestr .= ')</span>';	
	    $typestr .= '</span>';
	    
	    $typestr .= '</div>'."\n";	    
	} elseif ($withtypenote == true) { 
	    $typestr = '<div class="search-meta">';
		$typestr .= '<span class="post-meta-defaulttype"> ';
		$typestr .= $typeinfo->labels->singular_name; 
		$typestr .= '</span>';		

		$typestr .= ' <span class="post-meta-date"> ';
		$typestr .= get_the_modified_date();	   
		$typestr .= ' ('.__('Letzte Änderung', 'fau').')';
		$typestr .= '</span>';
	    
	    
	    $typestr .= '</div>'."\n";
	} else  {
	    $typestr = '';
	}

	if (!empty($typestr)) { 
	     $output .= "\t".$typestr."\n"; 
	}
	$output .= "\t";
	
	
	if (($type == 'person') && (class_exists('FAU_Person_Shortcodes'))) {
	    
	    if (($withthumb==true) && (has_post_thumbnail( $post->ID )) )  {
		$output .= '<div class="row">'."\n";  
		$output .= "\t\t".'<div class="searchresult-image">'."\n"; 
		$output .= '<a href="'.$link.'" class="news-image"';
		if ($external==1) {
		    $output .= ' ext-link';
		}
		$output .= '">';

		$post_thumbnail_id = get_post_thumbnail_id( $post->ID, 'post-thumb' ); 
		$imagehtml = '';
		$imgsrcset = '';
		if ($post_thumbnail_id) {
		    $sliderimage = wp_get_attachment_image_src( $post_thumbnail_id,  'post-thumb');
        	    $imgsrcset =  wp_get_attachment_image_srcset($post_thumbnail_id, 'post-thumb');

		    $imageurl = $sliderimage[0]; 	
		}
		if (!isset($imageurl) || (strlen(trim($imageurl)) <4 )) {
		    $imageurl = $options['default_postthumb_src'];
		}
		$output .= '<img src="'.fau_esc_url($imageurl).'" width="'.$options['default_postthumb_width'].'" height="'.$options['default_postthumb_height'].'" alt=""';
		if ($imgsrcset) {
		    $output .= ' srcset="'.$imgsrcset.'"';
		}
		$output .= '>';
		$output .= '</a>';

		$output .= "\t\t".'</div>'."\n"; 
		$output .= "\t\t".'<div class="searchresult-imagetext">'."\n"; 
		
	    }

	    $output .= FAU_Person_Shortcodes::fau_person(array("id"=> $post->ID, 'format' => 'kompakt', 'showlink' => 0, 'showlist' => 0, 'hide' => 'bild' )); 	
	    if (($withthumb==true) && (has_post_thumbnail( $post->ID )) )  {
	        $output .= "\t</div> <!-- /row -->\n";
	    }	
/*	}elseif (($type == 'standort') && (function_exists('fau_standort'))) {
		 $output .= fau_standort(array("id"=> $post->ID));	 
		 
	*/	
		 
	} elseif ($type == 'attachment') {
	     if ($withthumb==true)   {
		$output .= '<div class="row">'."\n";  
		$output .= "\t\t".'<div class="searchresult-image">'."\n"; 
		if (!empty($attachment['image'])) {
		    $output .= $attachment['image'];
		} else {
		    $output .= '<img src="'.fau_esc_url($attachment['icon']).'"  alt="">';
		}
		$output .= "\t\t".'</div>'."\n"; 

		$output .= "\t\t".'<div class="searchresult-imagetext">'."\n"; 

	    }
	    $output .= "\t\t".'<p><em>'."\n"; 
	    $output .= "\t\t\t".$attachment['caption'];
	    $output .= "\t\t".'</em></p>'."\n"; 
	    $output .= "\t\t".'<p>'."\n"; 
	    $output .= "\t\t\t".$attachment['description'];
	    $output .= "\t\t".'</p>'."\n"; 

	    
	    if ($withthumb==true)   {
		$output .= "\t</div> <!-- /row -->\n";
	    }	
	} elseif ($type == 'studienangebot') {
	    $output .= "\t\t".'<p>'."\n"; 
	    $output .= fau_custom_excerpt($post->ID,$options['default_search_excerpt_length'],false,'',true,$options['search_display_excerpt_morestring']);	
	  
	    $output .= "\t\t\t".'</p>'."\n"; 


	} else {

	    if (($withthumb==true) && (has_post_thumbnail( $post->ID )) )  {
		$output .= '<div class="row">'."\n";  
		$output .= "\t\t".'<div class="searchresult-image">'."\n"; 
		$output .= '<a href="'.$link.'" class="news-image"';
		if ($external==1) {
		    $output .= ' ext-link';
		}
		$output .= '">';

		$post_thumbnail_id = get_post_thumbnail_id( $post->ID, 'post-thumb' ); 
		$imagehtml = '';
		$imgsrcset = '';
		if ($post_thumbnail_id) {
		    $sliderimage = wp_get_attachment_image_src( $post_thumbnail_id,  'post-thumb');
        	    $imgsrcset =  wp_get_attachment_image_srcset($post_thumbnail_id, 'post-thumb');

		    $imageurl = $sliderimage[0]; 	
		}
		if (!isset($imageurl) || (strlen(trim($imageurl)) <4 )) {
		    $imageurl = $options['default_postthumb_src'];
		}
		$output .= '<img src="'.fau_esc_url($imageurl).'" width="'.$options['default_postthumb_width'].'" height="'.$options['default_postthumb_height'].'" alt=""';
		if ($imgsrcset) {
		    $output .= ' srcset="'.$imgsrcset.'"';
		}
		$output .= '>';
		$output .= '</a>';

		$output .= "\t\t".'</div>'."\n"; 
		$output .= "\t\t".'<div class="searchresult-imagetext">'."\n"; 

	    }

	    $output .= "\t\t".'<p>'."\n"; 
	    $output .= fau_custom_excerpt($post->ID,$options['default_search_excerpt_length'],false,'',true,$options['search_display_excerpt_morestring']);	
	    if ($options['search_display_continue_arrow']) {
		$output .= fau_create_readmore($link,'',$external,true);	
	    }
	    $output .= "\t\t\t".'</p>'."\n"; 
	    if (($withthumb==true) && (has_post_thumbnail( $post->ID )) )  {
		$output .= "\t</div> <!-- /row -->\n";
	    }	
	
	}
	
	
	$output .= "</article>\n";
    } else {
	$output .= "<!-- empty result -->\n";
    }
    return $output;						     
							
}

/*-----------------------------------------------------------------------------------*/
/*  Blogroll
/*-----------------------------------------------------------------------------------*/
function fau_display_news_teaser($id = 0, $withdate = false) {
    if ($id ==0) return;   
    global $options;
    
    $post = get_post($id);
    $output = '';
    if ($post) {
	$output .= '<article class="news-item" itemscope itemtype="http://schema.org/NewsArticle">';
	$link = get_post_meta( $post->ID, 'external_link', true );
	$external = false;
	if (isset($link) && (filter_var($link, FILTER_VALIDATE_URL))) {
	    $external = true;
	} else {
	    $link = fau_esc_url(get_permalink($post->ID));
	}
	$output .= "\t<h2 itemprop=\"headline\">";  
	$output .= '<a itemprop="url" ';
	if ($external) {
	    $output .= 'class="ext-link" rel="canonical" ';
	}
	$output .= 'href="'.$link.'">'.get_the_title($post->ID).'</a>';
	$output .= "</h2>\n";  
	
	
	$categories = get_the_category();
	$separator = ', ';
	$thiscatstr = '';
	$typestr = '';
	if($categories){
	    $typestr .= '<span class="news-meta-categories"> ';
	    $typestr .= __('Kategorie', 'fau');
	    $typestr .= ': ';
	    foreach($categories as $category) {
		$thiscatstr .= '<a href="'.get_category_link( $category->term_id ).'">'.$category->cat_name.'</a>'.$separator;
	    }
	    $typestr .= trim($thiscatstr, $separator);
	    $typestr .= '</span> ';
	}
	    
	
	if ($withdate) {
	    $output .= '<div class="news-meta">'."\n";
	    $output .= $typestr;
	    $output .= '<span class="news-meta-date" itemprop="datePublished" content="'. esc_attr( get_post_time('c') ).'"> '.get_the_date('',$post->ID)."</span>\n";
	    $output .= '</div>'."\n";
	}

	
	$output .= "\t".'<div class="row">'."\n";  
	
	if ((has_post_thumbnail( $post->ID )) ||($options['default_postthumb_always']))  {
	    $output .= "\t\t".'<div aria-hidden="true" class="col-xs-5 col-sm-4" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">'."\n"; 
	    $output .= '<a href="'.$link.'" class="news-image"';
	    if ($external) {
		$output .= ' ext-link';
	    }
	    $output .= '>';

	    $post_thumbnail_id = get_post_thumbnail_id( $post->ID, 'post-thumb' ); 
	    $imagehtml = '';
	    $imgwidth = $options['default_postthumb_width'];
	    $imgheight = $options['default_postthumb_height'];
	    $imgsrcset = '';
	    if ($post_thumbnail_id) {
		$sliderimage = wp_get_attachment_image_src( $post_thumbnail_id,  'post-thumb');
		$imageurl = $sliderimage[0]; 	
		$imgwidth = $sliderimage[1];
		$imgheight = $sliderimage[2];
		$imgsrcset =  wp_get_attachment_image_srcset($post_thumbnail_id, 'post-thumb');
		  
	    }
	    if (!isset($imageurl) || (strlen(trim($imageurl)) <4 )) {
		$imageurl = $options['default_postthumb_src'];
	    }
	    $output .= '<img itemprop="thumbnailUrl" src="'.fau_esc_url($imageurl).'" width="'.$imgwidth.'" height="'.$imgheight.'" alt=""';
	    if ($imgsrcset) {
		$output .= ' srcset="'.$imgsrcset.'"';
	    }
	    $output .= '>';    
	    $output .= '</a>';
	    $output .= "\t\t\t".'<meta itemprop="url" content="'.fau_make_absolute_url($imageurl).'">';
	    $output .= "\t\t\t".'<meta itemprop="width" content="'.$imgwidth.'">';
	    $output .= "\t\t\t".'<meta itemprop="height" content="'.$imgheight.'">';		    
	    $output .= "\t\t".'</div>'."\n"; 
	    $output .= "\t\t".'<div class="col-xs-7 col-sm-8">'."\n"; 
	} else {
	    $output .= "\t\t".'<div class="col-xs-12">'."\n"; 
	}
	$output .= "\t\t\t".'<p itemprop="description">'."\n"; 
	
	
	$cuttet = false;
	$abstract = get_post_meta( $post->ID, 'abstract', true );
	if (strlen(trim($abstract))<3) {
	   $abstract =  fau_custom_excerpt($post->ID,$options['default_anleser_excerpt_length'],false,'',true);
	}
	$output .= $abstract;
	$output .= $link;
	$output .= fau_create_readmore($link,get_the_title($post->ID),$external,true);	
	$output .= "\t\t\t".'</p>'."\n"; 
	
	
	$output .= "\t\t".'</div>'."\n"; 
	$output .= "\t</div> <!-- /row -->\n";
	if (!$external) {
	    $output .= fau_create_schema_publisher();
	}	
	$output .= "</article> <!-- /news-item -->\n";	
    }
    return $output;
}

/*-----------------------------------------------------------------------------------*/
/*  Weiterlesen-Link einheitlich gestalten fuer verschiedene Ausgaben
/*-----------------------------------------------------------------------------------*/
function fau_create_readmore($url,$linktitle = '',$external = false, $ariahide = true) {
    $output = '';
    if (isset($url)) {

	$link = fau_esc_url($url);	
	$output .= '<a';
	
	if ($ariahide) {
	    $output .= ' aria-hidden="true" tabindex="-1"';
	}
	if ($external) {
	    $output .= ' class="ext-link"';
	}
	$output .= ' href="'.$link.'"';
	if (!empty($linktitle)) {
	    $output .= ' title="'.$linktitle.'"';
	}
	$output .= '>';
	$output .= '<i class="read-more-arrow">&nbsp;</i>';
	if ($ariahide===false) {
	    $output .= '<span class="screen-reader-text">'.__('Weiterlesen','fau').'</span>'; 
	}
	$output .= '</a>'; 
    }
    return $output;
}

/*-----------------------------------------------------------------------------------*/
/* Array als Table ausgeben
/*-----------------------------------------------------------------------------------*/

function fau_array2table($array, $table = true) {
    $out = '';
    $tableHeader = '';
    foreach ($array as $key => $value) {
	 $out .= '<tr>';
	 $out .= "<th>$key</th>";
        if (is_array($value)) {   
            if (!isset($tableHeader)) {
                $tableHeader =
                    '<th>' .
                    implode('</th><th>', array_keys($value)) .
                    '</th>';
            }
            array_keys($value);
	    $out .= "<td>";
            $out .= fau_array2table($value, true);     
	    $out .= "</td>";
        } else {
            $out .= "<td>$value</td>";
        }
	$out .= '</tr>';
    }

    if ($table) {
        return '<table>' . $tableHeader . $out . '</table>';
    } else {
        return $out;
    }
}

/*-----------------------------------------------------------------------------------*/
/* Get Image Attributs / Extends https://codex.wordpress.org/Function_Reference/wp_get_attachment_metadata
/*-----------------------------------------------------------------------------------*/
function fau_get_image_attributs($id=0) {
    global $options;

        $precopyright = ''; // __('Bild:','fau').' ';
        if ($id==0) return;
        
        $meta = get_post_meta( $id );
        if (!isset($meta)) {
         return;
        }
    
        $result = array();
	if (isset($meta['_wp_attachment_image_alt'][0])) {
	    $result['alt'] = trim(strip_tags($meta['_wp_attachment_image_alt'][0]));
	} else {
	    $result['alt'] = "";
	}   

        if (isset($meta['_wp_attachment_metadata']) && is_array($meta['_wp_attachment_metadata'])) {        
	    $data = unserialize($meta['_wp_attachment_metadata'][0]);
	    if (isset($data['image_meta']) && is_array($data['image_meta'])) {
		if (isset($data['image_meta']['copyright'])) {
		       $result['copyright'] = trim(strip_tags($data['image_meta']['copyright']));
		}
		if (isset($data['image_meta']['author'])) {
		       $result['author'] = trim(strip_tags($data['image_meta']['author']));
		} elseif (isset($data['image_meta']['credit'])) {
		       $result['credit'] = trim(strip_tags($data['image_meta']['credit']));
		}
		if (isset($data['image_meta']['title'])) {
		     $result['title'] = $data['image_meta']['title'];
		}
		if (isset($data['image_meta']['caption'])) {
		     $result['caption'] = $data['image_meta']['caption'];
		}
	    }
	    if (isset($data['width'])) {
		$result['orig_width'] = $data['width'];
	    }
	    if (isset($data['height'])) {
		$result['orig_height'] = $data['height'];
	    }
	    if (isset($data['file'])) {
		$result['orig_file'] = $data['file'];
	    }
	    
        }
	if (isset($meta['_wp_attached_file']) && is_array($meta['_wp_attached_file'])) {
	    $result['attachment_file'] = $meta['_wp_attached_file'][0];
	} 
	
        $attachment = get_post($id);
	$result['excerpt'] = $result['credits'] = $result['description']= $result['title'] = '';
        if (isset($attachment) ) {
	   
	    if (isset($attachment->post_excerpt)) {
		$result['excerpt'] = trim(strip_tags( $attachment->post_excerpt ));
	    }
	    if (isset($attachment->post_content)) {
		$result['description'] = trim(strip_tags( $attachment->post_content ));
	    }        
	    if (isset($attachment->post_title) && (empty( $result['title']))) {
		 $result['title'] = trim(strip_tags( $attachment->post_title )); 
	    }   
        }      
	
	if ($options['advanced_images_info_credits'] == 1) {
	    
	    if (!empty($result['description'])) {
		$result['credits'] = $result['description'];
	    } elseif (!empty($result['copyright'])) {
		$result['credits'] = $precopyright.' '.$result['copyright'];	
	    } elseif (!empty($result['author'])) {
		$result['credits'] = $precopyright.' '.$result['author'];
	    } elseif (!empty($result['credit'])) {
		$result['credits'] = $precopyright.' '.$result['credit'];	
   	    } else {
		if (!empty($result['caption'])) {
		    $result['credits'] = $result['caption'];
		} elseif (!empty($result['excerpt'])) {
		    $result['credits'] = $result['excerpt'];
		} 
	    } 
	} else {
	
	    if (!empty($result['copyright'])) {
		$result['credits'] = $precopyright.' '.$result['copyright'];		
	    } elseif (!empty($result['author'])) {
		$result['credits'] = $precopyright.' '.$result['author'];
	    } elseif (!empty($result['credit'])) {
		$result['credits'] = $precopyright.' '.$result['credit'];		
		} else {
		if (!empty($result['description'])) {
		    $result['credits'] = $result['description'];
		} elseif (!empty($result['caption'])) {
		    $result['credits'] = $result['caption'];
		} elseif (!empty($result['excerpt'])) {
		    $result['credits'] = $result['excerpt'];
		} 
	    }   
	}
        return $result;
                
}

/*-----------------------------------------------------------------------------------*/
/* Get category links for front page
/*-----------------------------------------------------------------------------------*/

function fau_get_category_links($cateid = 0) {
    global $options;
    
    if ($cateid==0) {
	$cateid = $options['start_link_news_cat'];
    }
    $link = get_category_link($cateid);
    if (empty($link)){
	$cat = get_categories(); 
	if (is_array($cat)) {
	    $cateid = $cat[0]->cat_ID;
	    $link = get_category_link($cateid);
	}
    }
    $res = '';
    if (!fau_empty($link)) {
	$res .= '<div class="news-more-links">'."\n";
	$res .= "\t".'<a class="news-more" href="'.$link.'">'.$options['start_link_news_linktitle'].'</a>';
	$res .= '<a class="news-rss" href="'.get_category_feed_link($cateid).'">'.__('RSS','fau').'</a>';
	$res .= "</div>\n";	
    }
    return $res;
}



/*-----------------------------------------------------------------------------------*/
/* Change output for gallery
/*-----------------------------------------------------------------------------------*/

add_filter('post_gallery', 'fau_post_gallery', 10, 2);
function fau_post_gallery($output, $attr) {
    global $post;
    global $options;
    global $usejslibs;
    
    if (isset($attr['orderby'])) {
        $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
        if (!$attr['orderby'])
            unset($attr['orderby']);
    }

    extract(shortcode_atts(array(
        'order' => 'ASC',
        'orderby' => 'menu_order ID',
        'id' => $post->ID,
        'itemtag' => 'dl',
        'icontag' => 'dt',
        'captiontag' => 'dd',
        'columns' => 3,
        'size' => 'thumbnail',
        'include' => '',
        'exclude' => '',
	'type' => NULL,
	'lightbox' => FALSE,
	'captions' => 1,
	'columns'   => 6,
	'link'	=> 'file'

    ), $attr));

    $id = intval($id);
    if ('RAND' == $order) $orderby = 'none';

    if (!empty($include)) {
        $include = preg_replace('/[^0-9,]+/', '', $include);
        $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

        $attachments = array();
        foreach ($_attachments as $key => $val) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    }

    if (empty($attachments)) return '';

	
    $output = '';
    if (!isset($attr['captions'])) {
	$attr['captions'] =1;
    }
     if (!isset($attr['columns'])) {
	$attr['columns'] = 7;
    }
    if (!isset($attr['type'])) {
	$attr['type'] = 'default';
    }
    if (!isset($attr['link'])) {
	$attr['link'] = 'file';
    }
    switch($attr['type'])  {
	    case "grid":
		    {
			$rand = rand();

			$output .= "<div class=\"image-gallery-grid clearfix\">\n";
			$output .= "<ul class=\"grid\">\n";

			    foreach ($attachments as $id => $attachment) {
				    $img = wp_get_attachment_image_src($id, 'gallery-grid');
				    $meta = get_post($id);
				    // $img_full = wp_get_attachment_image_src($id, 'gallery-full');
				    $img_full = wp_get_attachment_image_src($id, 'full');
				    $lightboxattr = '';
				    $lightboxtitle = sanitize_text_field($meta->post_excerpt);
				    if (strlen(trim($lightboxtitle))>1) {
					$lightboxattr = ' title="'.$lightboxtitle.'"';
				    }
				    if(isset( $attr['captions']) && ($attr['captions']==1) && $meta->post_excerpt) {
					    $output .= "<li class=\"has-caption\">\n";
				    } else  {
					    $output .= "<li>\n";
				    }
					$output .= '<a href="'.fau_esc_url($img_full[0]).'" class="lightbox"';
					$output .= ' rel="lightbox-'.$rand.'"'.$lightboxattr.'>';

				    $output .= '<img src="'.fau_esc_url($img[0]).'" width="'.$img[1].'" height="'.$img[2].'" alt="">';
				    $output .= '</a>';
				    if($meta->post_excerpt) {
					    $output .= '<div class="caption">'.$meta->post_excerpt.'</div>';
				    }
			    $output .= "</li>\n";
			}

			    $output .= "</ul>\n";
			$output .= "</div>\n";

			    break;
		    }

	    case "2cols":
		    {
			    $rand = rand();

			    $output .= '<div class="row">'."\n";
			    $i = 0;

			    foreach ($attachments as $id => $attachment) {
				    $img = wp_get_attachment_image_src($id, 'image-2-col');
				    $img_full = wp_get_attachment_image_src($id, 'full');
				    $meta = get_post($id);
				     $lightboxattr = '';
				    $lightboxtitle = sanitize_text_field($meta->post_excerpt);
				    if (strlen(trim($lightboxtitle))>1) {
					$lightboxattr = ' title="'.$lightboxtitle.'"';
				    }
				    $output .= '<div class="span4">';
				    $output .= '<a href="'.fau_esc_url($img_full[0]).'" class="lightbox" rel="lightbox-'.$rand.'"'.$lightboxattr.'>';
				    $output .= '<img class="content-image-cols" src="'.fau_esc_url($img[0]).'" width="'.$img[1].'" height="'.$img[2].'" alt=""></a>';
				    if($attr['captions'] && $meta->post_excerpt) $output .= '<div class="caption">'.$meta->post_excerpt.'</div>';
				    $output .= '</div>'."\n";
				    $i++;

				    if($i % 2 == 0) {
					    $output .= '</div><div class="row">'."\n";
				    }
			    }

			    $output .= '</div>'."\n";

			    break;
		    }

	    case "4cols":
		    {
			    $rand = rand();

			    $output .= '<div class="row">'."\n";
			    $i = 0;

			    foreach ($attachments as $id => $attachment) {
				    $img = wp_get_attachment_image_src($id, 'image-4-col');
				    $img_full = wp_get_attachment_image_src($id, 'full');
				    $meta = get_post($id);
				    $lightboxattr = '';
				    $lightboxtitle = sanitize_text_field($meta->post_excerpt);
				    if (strlen(trim($lightboxtitle))>1) {
					$lightboxattr = ' title="'.$lightboxtitle.'"';
				    }
				    $output .= '<div class="span2">';
				    $output .= '<a href="'.fau_esc_url($img_full[0]).'" class="lightbox" rel="lightbox-'.$rand.'"'.$lightboxattr.'>';
				    $output .= '<img class="content-image-cols" src="'.fau_esc_url($img[0]).'" width="'.$img[1].'" height="'.$img[2].'" alt=""></a>';
				    if($attr['captions'] && $meta->post_excerpt) $output .= '<div class="caption">'.$meta->post_excerpt.'</div>';
				    $output .= '</div>';
				    $i++;

				    if($i % 4 == 0) {
					    $output .= '    </div><div class="row">'."\n";
				    }
			    }

			    $output .= "</div>\n";

			    break;
		    }

	    default:
		    {
			$usejslibs['flexslider'] = true;
			$rand = rand();	    
			$output .= "<div id=\"slider-$rand\" class=\"image-gallery-slider\">\n";
			$output .= "	<ul class=\"slides\">\n";

			foreach ($attachments as $id => $attachment) {
			    $img = wp_get_attachment_image_src($id, 'gallery-full');
			    $meta = get_post($id);
			    $img_full = wp_get_attachment_image_src($id, 'full');

			    $output .= '<li><img src="'.fau_esc_url($img[0]).'" width="'.$img[1].'" height="'.$img[2].'" alt="">';
			    if (($options['galery_link_original']) || ($meta->post_excerpt != '')) {
				$output .= '<div class="gallery-image-caption">';
				$lightboxattr = '';
				if($meta->post_excerpt != '') { 
				    $output .= $meta->post_excerpt; 
				    $lightboxtitle = sanitize_text_field($meta->post_excerpt);
				    if (strlen(trim($lightboxtitle))>1) {
					$lightboxattr = ' title="'.$lightboxtitle.'"';
				    }
				}
				if (($options['galery_link_original']) && ($attr['link'] != 'none')) {
				    if($meta->post_excerpt != '') { $output .= '<br>'; }
				    $output .= '<span class="linkorigin">(<a href="'.fau_esc_url($img_full[0]).'" '.$lightboxattr.' class="lightbox" rel="lightbox-'.$rand.'">'.__('Vergrößern','fau').'</a>)</span>';
				}
				$output .='</div>';
			    }
			    $output .= "</li>\n";
			}

			$output .= "	</ul>\n";
			$output .= "</div>\n";

			
			
			$output .= "<div id=\"carousel-$rand\" class=\"image-gallery-carousel\">";
			$output .= "	<ul class=\"slides\">";

			foreach ($attachments as $id => $attachment) {
			    $img = wp_get_attachment_image_src($id, 'gallery-thumb');
			    $output .= '	<li><img src="'.fau_esc_url($img[0]).'" width="'.$img[1].'" height="'.$img[2].'" alt=""></li>';
			}

			$output .= "	</ul>";
			$output .= "</div>";				
			$output .= "<script type=\"text/javascript\"> jQuery(document).ready(function($) {";			
			$output .= "$('#carousel-$rand').flexslider({maxItems: ".$attr['columns'].",selector: 'ul > li',animation: 'slide',keyboard:true,multipleKeyboard:true,directionNav:true,controlNav: true,pausePlay: false,slideshow: false,asNavFor: '#slider-$rand',itemWidth: 125,itemMargin: 5});";
			$output .= "$('#slider-$rand').flexslider({selector: 'ul > li',animation: 'slide',keyboard:true,multipleKeyboard:true,directionNav: false,controlNav: false,pausePlay: false,slideshow: false,sync: '#carousel-$rand'});";
			$output .= "});</script>";

		    }
    }
    return $output;
}

/*-----------------------------------------------------------------------------------*/
/* Default Linklisten
/*-----------------------------------------------------------------------------------*/

function fau_get_defaultlinks ($list = 'faculty', $ulclass = '', $ulid = '') {
    global $default_link_liste;
    global $options;
    
    if (is_array($default_link_liste[$list])) {
	$uselist =  $default_link_liste[$list];
    } else {
	$uselist =  $default_link_liste['faculty'];
    }
    
    $result = '';
    if (isset($uselist['_title'])) {
	$result .= '<h3>'.$uselist['_title'].'</h3>';	
	$result .= "\n";
    }
    $thislist = '';
    
    foreach($uselist as $key => $entry ) {
	if (substr($key,0,4) != 'link') {
	    continue;
	}
	$thislist .= '<li';
	if (isset($entry['class'])) {
	    $thislist .= ' class="'.$entry['class'].'"';
	}
	$thislist .= '>';
	if (isset($entry['content'])) {
	    $thislist .= '<a data-wpel-link="internal" href="'.$entry['content'].'">';
	}
	$thislist .= $entry['name'];
	if (isset($entry['content'])) {
	    $thislist .= '</a>';
	}
	$thislist .= "</li>\n";
    }    
    if (isset($thislist)) {
	$result .= '<ul';
	if (!empty($ulclass)) {
	    $result .= ' class="'.$ulclass.'"';
	}
	if (!empty($ulid)) {
	    $result .= ' id="'.$ulid.'"';
	}
	$result .= '>';
	$result .= $thislist;
	$result .= '</ul>';	
	$result .= "\n";	
    }
    return $result;
}


/*-----------------------------------------------------------------------------------*/
/* Erstellt Toplinkliste
/*-----------------------------------------------------------------------------------*/

function fau_get_toplinks() {
    global $options;
    global $defaultoptions;
    global $default_link_liste;
    global $default_fau_orga_data;
    global $default_fau_orga_faculty;
	    
    $uselist =  $default_link_liste['meta'];
    $result = '';
    

    if (isset($uselist['_title'])) {
	$result .= '<h3>'.$uselist['_title'].'</h3>';	
	$result .= "\n";
    }
    

	/* 
	 * website_type: 
	 *  0 = Fakultaetsportal oder zentrale Einrichtung
	 *	=> Nur Link zur FAU, kein Link zur Fakultät
	 *	   Link zur FAU als Text, da FAU-Logo bereits Teil des
	 *         Fakultätslogos
	 *  1 = Lehrstuhl oder eine andere Einrichtung die einer Fakultät zugeordnet ist 
	 *	=> Link zur FAU und Link zur Fakultät, 
	 *         Link zur FAU als Grafik, Link zur Fakultät als Text (lang oder kurz nach Wahl)
	 *  2 = Sonstige Einrichtung, die nicht einer Fakultät zugeordnet sein muss
	 *	=> Nur Link zur FAU, kein Link zur Fakultät
	 *	   Link zur FAU als Grafik (das ist der Unterschied zur Option 0)
	 *  3 = Koopertation mit Externen (neu ab 1.4)
	 *	=> Kein Link zur FAU
	 *  -1 = FAU-Portal (neu ab 1.4, nur für zukunftigen Bedarf)
	 *	=> Kein Link zur FAU, aktiviert 4 Spalten unter HERO
	 * 
	 * 'website_usefaculty' = ( nat | phil | med | tf | rw )
	 *  Wenn gesetzt, wird davon ausgegangen, dass die Seite
	 *  zu einer Fakultät gehört; Daher werden website_type-optionen auf
	 *  0 und 2 reduziert. D.h.: Immer LInk zur FAU, keine Kooperationen.
	 *  
	 */
    
    $options['website_usefaculty'] = $defaultoptions['website_usefaculty'];
    $isfaculty = false;
    if ( (isset($options['website_usefaculty'])) && (in_array($options['website_usefaculty'],$default_fau_orga_faculty))) {
	$isfaculty = true;
    }
    
    $linkhome = true;
    $linkhomeimg = false;
    $linkfaculty = false;

    // Using if-then-else structure, due to better performance as switch 
    if ($options['website_type']==-1) {
	$linkhome = false;
	$linkfaculty = false;
	$linkhomeimg = false;
    } elseif ($isfaculty) {
	if ($options['website_type']==0) {
	    $linkhomeimg = false;
	    $linkfaculty = false;
	} else {
	    $linkhomeimg = true;
	    $linkfaculty = true;
	}
    } else {
	if ($options['website_type']==1) {
	    // Option sollte eigentlich nicht moeglich sein. Aber zur
	    // moglichen zukünftigen Nutzung eingebaut.
	     $linkhomeimg = true;
	} elseif ($options['website_type']==2) {
	     $linkhomeimg = true;
	} elseif ($options['website_type']==3) {
	    $linkhome = false;
       
	}
    }

    $charset = fau_get_language_main();
    
    if (isset($options['default_home_orga'])) {
	$orga = $options['default_home_orga'];
    } else {
	$orga = 'fau';
    }
    $hometitle = $shorttitle = $homeurl = $linkimg = '';
    if ((isset($default_fau_orga_data[$orga])) && is_array($default_fau_orga_data[$orga])) {
	$hometitle = $default_fau_orga_data[$orga]['title'];
	$shorttitle = $default_fau_orga_data[$orga]['shorttitle'];
	if (isset($default_fau_orga_data[$orga]['homeurl_'.$charset])) {
	    $homeurl = $default_fau_orga_data[$orga]['homeurl_'.$charset];
	} else {
	    $homeurl = $default_fau_orga_data[$orga]['homeurl'];
	}
	$linkimg = $default_fau_orga_data[$orga]['home_imgsrc'];
    } else {
	$linkhome = false;
    }
   
    $facultytitle = $facultyshorttitle = $facultyurl = '';
    if (($linkfaculty) && isset($default_fau_orga_data['_faculty'][$options['website_usefaculty']])) {
	$orga =  $options['website_usefaculty'];
	$facultytitle = $default_fau_orga_data['_faculty'][$orga]['title'];
	$facultyshorttitle = $default_fau_orga_data['_faculty'][$orga]['shorttitle'];

	if (isset($default_fau_orga_data['_faculty'][$orga]['homeurl_'.$charset])) {
	    $facultyurl = $default_fau_orga_data['_faculty'][$orga]['homeurl_'.$charset];
	} else {
	    $facultyurl = $default_fau_orga_data['_faculty'][$orga]['homeurl'];
	}
	
	
    } else {
	$linkfaculty = false;
    }

    
    
    $thislist = '';
    
    
    if (($linkhome) && isset($homeurl)) {
	$thislist .= '<li class="fauhome">';
	$thislist .= '<a href="'.$homeurl.'">';
	    			
	if ($linkhomeimg) {
	    $thislist .= '<img src="'.fau_esc_url($linkimg).'" alt="'.esc_attr($hometitle).'">'; 
	} else {
	    $thislist .= $shorttitle; 
	}	
	$thislist .= '</a>';
	$thislist .= '</li>'."\n";	
    }
    

    if (($linkfaculty) && isset($facultyurl)) {
	$thislist .= '<li data-wpel-link="internal" class="facultyhome">';
	$thislist .= '<a href="'.$facultyurl.'">';
	if ($options['default_faculty_useshorttitle']) {
	    $thislist .= $facultyshorttitle; 
	} else {
	    $thislist .= $facultytitle; 
	}
	$thislist .= '</a>';
	$thislist .= '</li>'."\n";	
    }

    
    
    if ( has_nav_menu( 'meta' ) ) {
	// wp_nav_menu( array( 'theme_location' => 'meta', 'container' => false, 'items_wrap' => '<ul id="meta-nav" class="%2$s">%3$s</ul>' ) );
	
	 $menu_name = 'meta';

	    if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
		$menu_items = wp_get_nav_menu_items($menu->term_id);
		foreach ( (array) $menu_items as $key => $menu_item ) {
		    $title = $menu_item->title;
		    $url = $menu_item->url;
		    $class_names = '';
		    $classes[] = 'menu-item';
		    $classes = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
		    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ) ) ); 
		    $class_names = ' class="' . esc_attr( $class_names ) . '"';
		    $thislist .= '<li'.$class_names.'><a data-wpel-link="internal" href="' . $url . '">' . $title . '</a></li>';
		}
	    } 
	
    } else {
	foreach($uselist as $key => $entry ) {
	   if (substr($key,0,4) != 'link') {
	       continue;
	   }
	   $thislist .= '<li';
	   if (isset($entry['class'])) {
	       $thislist .= ' class="'.$entry['class'].'"';
	   }
	   $thislist .= '>';
	   if (isset($entry['content'])) {
	       $thislist .= '<a data-wpel-link="internal" href="'.$entry['content'].'">';
	   }
	   $thislist .= $entry['name'];
	   if (isset($entry['content'])) {
	       $thislist .= '</a>';
	   }
	   $thislist .= "</li>\n";
       }   
    }
    if (isset($thislist)) {	
	$result .= '<ul id="meta-nav" class="menu">';
	$result .= $thislist;
	$result .= '</ul>';	
	$result .= "\n";	
    }
    return $result;
	     
    
  
}

