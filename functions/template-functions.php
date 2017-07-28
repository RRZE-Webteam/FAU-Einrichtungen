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