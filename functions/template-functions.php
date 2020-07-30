<?php
/**
 * Additional features to allow styling of the templates
 */

 /*-----------------------------------------------------------------------------------*/
 /* Extends the default WordPress body classes
 /*-----------------------------------------------------------------------------------*/
 function fau_body_class( $classes ) {
    global $defaultoptions;
    global $default_fau_orga_data;
    global $default_fau_orga_faculty;
	 // Additional body classes for Meta WIdget (once only language switcher)
    global $is_sidebar_active;
    
    if (is_workflow_translation_active()) {
	 if ( is_active_sidebar( 'language-switcher'  )) {
		 $classes[] = 'active-meta-widget';
	 }
    }

    if (function_exists( 'wpel_init' )) {
	 $classes[] = 'wp-external-links';
    }
    

    if ((! is_plugin_active( 'rrze-elements/rrze-elements.php' ) )
	// && (! is_plugin_active( 'rrze-faq/rrze-faq.php' ) )) {
	&& (! is_plugin_active( 'rrze-glossary/rrze-glossary.php' ) )) {
		$classes[] = 'theme-accordion';
    }
    
    
    $website_usefaculty = $defaultoptions['website_usefaculty'];
    if ( (isset($website_usefaculty)) && (in_array($website_usefaculty,$default_fau_orga_faculty))) {
	 $classes[] = 'faculty-'.$website_usefaculty;
    }
    $website_type = get_theme_mod('website_type');
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
    
    $classes[] = 'fau-theme';
    if ($website_type==-1) {
	$classes[] = 'fauorg-home';
    } elseif ($website_type==0) {
	$classes[] = 'fauorg-fakultaet';
	$classes[] = 'fauorg-unterorg';
    } elseif ($website_type==1) {
	$classes[] = 'fauorg-fakultaet';
    } elseif ($website_type==2) {	
	$classes[] = 'fauorg-sonst';
    } elseif ($website_type==3) {	
	$classes[] = 'fauorg-kooperation';	
    } else {
	$classes[] = 'fauorg-fakultaet';
	$classes[] = 'fauorg-unterorg';
    }

    $header_image = get_header_image();
    if (empty( $header_image ) ) {	
	$classes[] = 'nologo';
    }
    
    $sitetitle = get_bloginfo( 'title' );
    if (strlen($sitetitle) > 50) {
	$classes[] = 'longtitle';
    }
    
    
    if (('' != get_theme_mod( 'slider-autoplay' )) && (true== get_theme_mod( 'slider-autoplay' )) ) {
	     $classes[] = 'slider-autoplay';
    } else {
	  $classes[] = 'slider-noplay';
    }

    if ('fade' == get_theme_mod( 'slider-animation' ) ) {
	    $classes[] = 'slider-fade';
    }
    
    if (('' != get_theme_mod( 'advanced_display_portalmenu_forceclick' )) && (true== get_theme_mod( 'advanced_display_portalmenu_forceclick' )) ) {
	     $classes[] = 'mainnav-forceclick';
    }
    if (('' != get_theme_mod( 'advanced_display_portalmenu_plainview' )) && (true== get_theme_mod( 'advanced_display_portalmenu_plainview' )) ) {
	$classes[] = 'mainnav-plainview';
    } else {
	$classes[] = 'mainnav-defaultview';
    }
    
    if (('' != get_theme_mod( 'advanced_display_header_md-showsitelogo' )) && (true == get_theme_mod( 'advanced_display_header_md-showsitelogo' )) ) {	    
	     $classes[] = 'md-showsitelogo';
    }
    
    
    
    if (false== get_theme_mod( 'advanced_activate_quicklinks' )) {
	 $classes[] = 'no-quicklinks';
    }
    
    if ($defaultoptions['slider-opacity-text-background'] != get_theme_mod('slider-opacity-text-background' ))  {
	$num = get_theme_mod('slider-opacity-text-background');
	if (isset($num)) {
	    if (isset($defaultoptions['slider-opacity-text-background-array'][$num])) {
		$thisclass = 'hero-slides-op-'.$num;
		$classes[] = $thisclass;
	    }

	}
	    
    }

    if ($is_sidebar_active) {
	 $classes[] = 'with-sidebar';
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
/* Mark sidebar as used. This will add the class with-sidebar in the body class
/*-----------------------------------------------------------------------------------*/
function fau_use_sidebar($activate) {
    global $is_sidebar_active;
    if ($activate) {
	$is_sidebar_active = 1;
    } else {
	$is_sidebar_active = 0;
    }

    return $is_sidebar_active;
}
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
/*  Anzeige Suchergebnisse
/*-----------------------------------------------------------------------------------*/
function fau_display_search_resultitem($withsidebar = 1) {
    global $post;
    global $defaultoptions;
    
    $output = '';
    $withthumb = get_theme_mod('search_display_post_thumbnails');
    $withcats =  get_theme_mod('search_display_post_cats');
    $withtypenote = get_theme_mod('search_display_typenote');
    $attachment = array();
    
    if (isset($post) && isset($post->ID)) {
	
	$link = get_post_meta( $post->ID, 'external_link', true );
	$link = esc_url(trim($link));
	$external = 0;
	if (isset($link) && (filter_var($link, FILTER_VALIDATE_URL))) {
	    $external = 1;
	} else {
	    $link = get_permalink($post->ID);
	}
	
	$type = get_post_type();
	$typeclass = "res-".$type;
	$output .= '<li class="search-result '.$typeclass.'">'."\n";
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
	    
	    $typestr .= ' <span class="filesize">(<span class="screen-reader-text">';
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

				
		$post_thumbnail_id = get_post_thumbnail_id( $post->ID); 
		
		$imagehtml = fau_get_image_htmlcode( $post_thumbnail_id, 'rwd-480-3-2', '' );
		if (fau_empty($imagehtml)) {
		    $imagehtml = fau_get_image_fallback_htmlcode('rwd-480-3-2', '');
		}
		$output .= $imagehtml;
		$output .= '</a>';

		$output .= "\t\t".'</div>'."\n"; 
		$output .= "\t\t".'<div class="searchresult-imagetext">'."\n"; 
		
	    }

	    $output .= FAU_Person_Shortcodes::fau_person(array("id"=> $post->ID, 'format' => 'kompakt', 'showlink' => 0, 'showlist' => 0, 'hide' => 'bild' )); 	
	    if (($withthumb==true) && (has_post_thumbnail( $post->ID )) )  {
	        $output .= "\t</div> <!-- /row -->\n";
	    }	
	
		 
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
	    $output .= fau_custom_excerpt($post->ID,get_theme_mod('default_search_excerpt_length'),false,'',true,get_theme_mod('search_display_excerpt_morestring'));	
	  
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

		
		$post_thumbnail_id = get_post_thumbnail_id( $post->ID); 
		
		$imagehtml = fau_get_image_htmlcode( $post_thumbnail_id, 'rwd-480-3-2', '' );
		if (fau_empty($imagehtml)) {
		    $imagehtml = fau_get_image_fallback_htmlcode('rwd-480-3-2', '');
		}
		$output .= $imagehtml;
		$output .= '</a>';

		$output .= "\t\t".'</div>'."\n"; 
		$output .= "\t\t".'<div class="searchresult-imagetext">'."\n"; 

	    }

	    $output .= "\t\t".'<p>'."\n"; 
	    $output .= fau_custom_excerpt($post->ID,get_theme_mod('default_search_excerpt_length'),false,'',true,get_theme_mod('search_display_excerpt_morestring'));	
	    if (get_theme_mod('search_display_continue_arrow')) {
		$output .= fau_create_readmore($link,'',$external,true);	
	    }
	    $output .= "\t\t\t".'</p>'."\n"; 
	    if (($withthumb==true) && (has_post_thumbnail( $post->ID )) )  {
		$output .= "\t</div> <!-- /row -->\n";
	    }	
	
	}
	
	
	$output .= "</li>\n";
    }
    return $output;						     
							
}

/*-----------------------------------------------------------------------------------*/
/*  Blogroll
/*-----------------------------------------------------------------------------------*/
function fau_display_news_teaser($id = 0, $withdate = false, $hstart = 2, $hidemeta = false) {
    if ($id ==0) return;   
    
    $post = get_post($id);
    $output = '';
    if ($post) {
	
	
	if (!is_int($hstart)) {
	    $hstart = 2;
	} elseif (($hstart < 1) || ($hstart >6)) {
	    $hstart = 2;
	}
	$arialabelid= "aria-".$post->ID."-".random_int(10000,30000);
	    // add random key, due to the possible use of blogrolls of the news. The same article can be displayed
	    // more times on the same page. This would result in an wcag/html error, cause the uniq id would be used more as one time
	$output .= '<article class="news-item" aria-labelledby="'.$arialabelid.'" itemscope itemtype="http://schema.org/NewsArticle">';
	$link = get_post_meta( $post->ID, 'external_link', true );
	$link = esc_url(trim($link));
	$external = false;
	if (isset($link) && (filter_var($link, FILTER_VALIDATE_URL))) {
	    $external = true;
	} else {
	    $link = fau_esc_url(get_permalink($post->ID));
	}
	
	$output .= '<h'.$hstart.' id="'.$arialabelid.'" itemprop="headline">';  
	$output .= '<a itemprop="url" ';
	if ($external) {
	    $output .= 'class="ext-link" rel="canonical" ';
	}
	$output .= 'href="'.$link.'">'.get_the_title($post->ID).'</a>';
	$output .= "</h".$hstart.">";  
	
	if ($hidemeta == false) {
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
		$output .= '<div class="news-meta">';
		$output .= $typestr;
		$output .= '<span class="news-meta-date" itemprop="datePublished" content="'. esc_attr( get_post_time('c') ).'"> '.get_the_date('',$post->ID)."</span>";
		$output .= '</div>';
	    } else {
		$output .= '<div><meta itemprop="datePublished" content="'. esc_attr( get_post_time('c') ).'"></div>';
	    }
	}
	$output .= '<div><meta itemprop="dateModified" content="'. esc_attr( get_the_modified_time('c') ).'">';

	$schemaauthor = get_theme_mod('contact_address_name')." ".get_theme_mod('contact_address_name2'); 
	$output .= '<meta itemprop="author" content="'. esc_attr( $schemaauthor ).'"></div>';
					
	
	$output .= '<div class="row">';  	
	$show_thumbs = get_theme_mod('default_postthumb_always');
	
	if ((has_post_thumbnail( $post->ID )) || ($show_thumbs==true))  {
	    
	    $output .= '<div class="thumbnailregion">'; 
	    $output .= '<div aria-hidden="true" role="presentation" class="passpartout" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">'; 
	    $output .= '<a href="'.$link.'" tabindex="-1" class="news-image';
	    if ($external) {
		$output .= ' ext-link';
	    }
	    $output .= '">';

	    // $size =  'post-thumb';
	    $size =  'rwd-480-3-2';
	    
	    $post_thumbnail_id = get_post_thumbnail_id( $post->ID); 
	    
	    
	    $pretitle = get_theme_mod('advanced_blogroll_thumblink_alt_pretitle');
	    $posttitle = get_theme_mod('advanced_blogroll_thumblink_alt_posttitle');
	    $alttext = $pretitle.get_the_title($post->ID).$posttitle;
	    $alttext = esc_html($alttext);
	
	    $imagehtml = fau_get_image_htmlcode($post_thumbnail_id,'rwd-480-3-2',$alttext,'',array('itemprop' => 'thumbnailUrl'));
	    if (fau_empty($imagehtml)) {
		$imagehtml = fau_get_image_fallback_htmlcode('post-thumb',$alttext,'',array('itemprop' => 'thumbnailUrl'));
	    }
	    
	    $output .= $imagehtml;
	    $output .= '</a>';
	    $imgmeta = wp_get_attachment_image_src($post_thumbnail_id, 'rwd-480-3-2');
	    if ($imgmeta) {
		$output .= '<meta itemprop="url" content="'.fau_make_absolute_url($imgmeta[0]).'">';
	    }
	    global $defaultoptions;
	    $output .= '<meta itemprop="width" content="'.$defaultoptions['default_rwdimage_width'].'">';
	    $output .= '<meta itemprop="height" content="'.$defaultoptions['default_rwdimage_height'].'">';		    
	    $output .= '</div>'; 
	    $output .= '</div>'; 
	    $output .= '<div class="teaserregion">'; 
	} else {
	    $output .= '<div class="fullwidthregion">'; 
	}
	$output .= '<p itemprop="description">'; 
	$cuttet = false;
	$abstract = get_post_meta( $post->ID, 'abstract', true );
	if (strlen(trim($abstract))<3) {
	   $abstract =  fau_custom_excerpt($post->ID,get_theme_mod('default_anleser_excerpt_length'),false,'',true);
	}
	$output .= $abstract;
	$output .= fau_create_readmore($link,get_the_title($post->ID),$external,true);	
	$output .= '</p>'; 
	$output .= '</div>'; 
	$output .= "</div>";
	if (!$external) {
	    $output .= fau_create_schema_publisher();
	}	
	$output .= "</article>";	
    }
    return $output;
}
/*-----------------------------------------------------------------------------------*/
/*  Create String with custom excerpt
/*-----------------------------------------------------------------------------------*/
function fau_custom_excerpt($id = 0, $length = 0, $withp = true, $class = '', $withmore = false, $morestr = '', $continuenextline=false) {    
    if ($length==0) {
	$length = get_theme_mod('default_excerpt_length');
    }
    
    if (fau_empty($morestr)) {
	$morestr = get_theme_mod('default_excerpt_morestring');
    }
    $excerpt = "";
    //  $excerpt = get_the_excerpt($id); // get_post_field('post_excerpt',$id);
    // get_the_excerpt  nmacht Probleme, wenn text Shortcode enthält, daher direkte Lösung
    
    $excerpt = get_post_field('post_excerpt',$id);
    if (mb_strlen(trim($excerpt))<5) {
	$excerpt = get_post_field('post_content',$id);
    }

    $excerpt = preg_replace('/\s+(https?:\/\/www\.youtube[\/a-z0-9\.\-\?&;=_]+)/i','',$excerpt);
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt, get_theme_mod('custom_excerpt_allowtags')); 
  
  if (mb_strlen($excerpt)<5) {
      $excerpt = '<!-- '.__( 'Kein Inhalt', 'fau' ).' -->';
  }
    
  $needcontinue =0;
  if (mb_strlen($excerpt) >  $length) {
	$str = mb_substr($excerpt, 0, $length);
	$needcontinue = 1;
  }  else {
	$str = $excerpt;
  }
	    
    $the_str = '';
    if ($withp) {
	$the_str .= '<p';
	if (!fau_empty($class)) {
	    $the_str .= ' class="'.$class.'"';
	}
	$the_str .= '>';
    }
    $the_str .= $str;
    
    if (($needcontinue==1) && ($withmore==true)) {
	    if ($continuenextline) {
		  $the_str .= '<br>';
	    }
	    $the_str .= $morestr;
    }
  
    if ($withp) {
	$the_str .= '</p>';
    }
  return $the_str;
}

/*-----------------------------------------------------------------------------------*/
/*  Create String for Publisher Info, used by Schema.org Microformat Data
/*-----------------------------------------------------------------------------------*/
function fau_create_schema_publisher($withrahmen = true) {
    $out = '';
    if ($withrahmen) {
	$out .= '<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">';  
    }
    $header_image = get_header_image();
    if ($header_image) {
	$out .= '<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">';
	$out .= '<meta itemprop="url" content="'.fau_make_absolute_url( $header_image ).'">';
	$out .= '<meta itemprop="width" content="'.get_custom_header()->width.'">';
	$out .= '<meta itemprop="height" content="'.get_custom_header()->height.'">';
	$out .= '</div>';
    }
    $out .= '<meta itemprop="name" content="'.get_bloginfo( 'title' ).'">';
    $out .= '<meta itemprop="url" content="'.home_url( '/' ).'">';
    if ($withrahmen) {
	$out .= '</div>';
    }
    return $out;
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
	$output .= '<span class="read-more-arrow">&nbsp;</span>';
	$output .= '<span class="screen-reader-text">'.__('Weiterlesen','fau').'</span>'; 
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
	$info_credits = get_theme_mod('advanced_images_info_credits');
	if ($info_credits) {
	    
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
    
    if ($cateid==0) {
	$cateid = get_theme_mod('start_link_news_cat');
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
	$res .= "\t".'<a class="news-more" href="'.$link.'">'.get_theme_mod('start_link_news_linktitle').'</a>';
	$res .= '<a class="news-rss" href="'.get_category_feed_link($cateid).'">'.__('RSS','fau').'</a>';
	$res .= "</div>\n";	
    }
    return $res;
}


/*-----------------------------------------------------------------------------------*/
/* Default Linklisten
/*-----------------------------------------------------------------------------------*/
function fau_get_defaultlinks ($list = 'faculty', $ulclass = '', $ulid = '') {
    global $default_link_liste;
    
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
/* Erstellt Link zur Home-ORGA in der Meta-Nav
/*-----------------------------------------------------------------------------------*/
function fau_get_orgahomelink() {
    global $defaultoptions;
    global $default_fau_orga_data;
    global $default_fau_orga_faculty;
	
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
    $result = '';
 
    $website_usefaculty = $defaultoptions['website_usefaculty'];
    $isfaculty = false;
    if ( (isset($website_usefaculty)) && (in_array($website_usefaculty,$default_fau_orga_faculty))) {
	$isfaculty = true;
    }
    
    $linkhome = true;
    $linkhomeimg = false;
    $linkfaculty = false;

    
    $website_type = get_theme_mod("website_type");
   
    
    // Using if-then-else structure, due to better performance as switch 
    if ($website_type==-1) {
	$linkhome = true; // wird uber CSS unsichtbar gemacht fuer desktop und bei kleinen aufloesungen gezeigt
	$linkfaculty = false;
	$linkhomeimg = true;
    } elseif ($isfaculty) {
	$linkhomeimg = true;
	
	if ($website_type==0) {
	    //  0 = Fakultaetsportal oder zentrale Einrichtung
	    $linkfaculty = false;
	} else {
	    $linkfaculty = true;
	}
    } else {
	$linkhomeimg = true;
	if ($website_type==3) {
	    $linkhome = false;
	}
    }

    $charset = fau_get_language_main();
    
    $homeorga = 'fau';
    
    $hometitle = $shorttitle = $homeurl = $linkimg = $linkdataset ='';
    
    if ((isset($default_fau_orga_data[$homeorga])) && is_array($default_fau_orga_data[$homeorga])) {
	$hometitle = $default_fau_orga_data[$homeorga]['title'];
	$shorttitle = $default_fau_orga_data[$homeorga]['shorttitle'];
	if (isset($default_fau_orga_data[$homeorga]['homeurl_'.$charset])) {
	    $homeurl = $default_fau_orga_data[$homeorga]['homeurl_'.$charset];
	} else {
	    $homeurl = $default_fau_orga_data[$homeorga]['homeurl'];
	}
	$linkimg = $default_fau_orga_data[$homeorga]['home_imgsrc'];
	if (isset($default_fau_orga_data[$homeorga]['data-imgmobile'])) {
	    $linkdataset = $default_fau_orga_data[$homeorga]['data-imgmobile'];
	}

    } else {
	$linkhome = false;
    }
   
    $facultytitle = $facultyshorttitle = $facultyurl = '';
    if (($linkfaculty) && isset($default_fau_orga_data['_faculty'][$website_usefaculty])) {
	$facultytitle = $default_fau_orga_data['_faculty'][$website_usefaculty]['title'];
	$facultyshorttitle = $default_fau_orga_data['_faculty'][$website_usefaculty]['shorttitle'];

	if (isset($default_fau_orga_data['_faculty'][$website_usefaculty]['homeurl_'.$charset])) {
	    $facultyurl = $default_fau_orga_data['_faculty'][$website_usefaculty]['homeurl_'.$charset];
	} else {
	    $facultyurl = $default_fau_orga_data['_faculty'][$website_usefaculty]['homeurl'];
	}
	
	
    } else {
	$linkfaculty = false;
    }

    $orgalist = '';
    
    
    if (($linkhome) && isset($homeurl)) {
	$orgalist .= '<li class="fauhome">';
	$orgalist .= '<a href="'.$homeurl.'">';
	    			
	if ($linkhomeimg) {
	    $orgalist .= '<img src="'.fau_esc_url($linkimg).'" alt="'.esc_attr($hometitle).'"'; 
	    if ($linkdataset) {
		 $orgalist .= ' data-imgmobile="'.fau_esc_url($linkdataset).'"'; 
	    }
	    $orgalist .= '>'; 
	} else {
	    $orgalist .= $shorttitle; 
	}	
	$orgalist .= '</a>';
	$orgalist .= '</li>'."\n";	
    }
    

    if (($linkfaculty) && isset($facultyurl)) {
	$orgalist .= '<li data-wpel-link="internal" class="facultyhome">';
	$orgalist .= '<a href="'.$facultyurl.'">';
	
	$useshorttitle = get_theme_mod("default_faculty_useshorttitle");
	if ($useshorttitle) {
	    $orgalist .= $facultyshorttitle; 
	} else {
	    $orgalist .= $facultytitle; 
	}
	$orgalist .= '</a>';
	$orgalist .= '</li>'."\n";	
    }
    
    if (isset($orgalist)) {	
	$result .= '<ul class="orgalist">';
	$result .= $orgalist;
	$result .= '</ul>';	
    }
    
    return $result;

}
/*-----------------------------------------------------------------------------------*/
/* Erstellt Links in der Metanav oben
/*-----------------------------------------------------------------------------------*/
function fau_get_toplinks($args = array()) {
    global $default_link_liste;
	   
    
    
    $uselist =  $default_link_liste['meta'];
    $result = '';

    $orgalist = fau_get_orgahomelink();
    $thislist = "";
    

    
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
		 //   $classes[] = 'menu-item';
		 //   $classes = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
		 //   $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ) ) ); 
		 //    $class_names = ' class="' . esc_attr( $class_names ) . '"';
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
    
    
    if (isset($orgalist)) {	
	$result .= $orgalist;
    }
    if (isset($thislist)) {	
	if (is_array($args) && isset($args['title'])) {
	    $html = 'h3';
	    if (isset($args['titletag'])) {
		 $html = $args['titletag'];
	    }
	    $html = esc_attr($html);
	    
	    $result .= '<'.$html.'>'.esc_attr($args['title']).'</'.$html.'>';
	}
	
	$result .= '<ul class="meta-nav menu">';
	$result .= $thislist;
	$result .= '</ul>';	
	$result .= "\n";	
    }
    return $result;

}

/*-----------------------------------------------------------------------------------*/
/* Get cat id by name or slug
/*-----------------------------------------------------------------------------------*/
function fau_get_cat_ID($string) {
    if (empty($string)) {
        return 0;
    }
    $string= esc_attr( $string );
    if (is_string($string)) {
	$thisid = get_cat_ID($string);
        if ($thisid==0) {
            $idObj = get_category_by_slug( $string );
            if (false==$idObj) {
                return -1;
            }
            $thisid = $idObj->term_id;
        }
        return $thisid;
    } elseif(is_numeric($string)) {
        return $string;
    }
    
}

/*-----------------------------------------------------------------------------------*/
/* Get tag id
/*-----------------------------------------------------------------------------------*/
function fau_get_tag_ID($tag_name) {
    $tag = get_term_by('name', $tag_name, 'post_tag');
    if ($tag) {
	return $tag->term_id;
    } else {
	return -1;
    }
}
 /*-----------------------------------------------------------------------------------*/
 /* Display blog entries as blogroll
 /*-----------------------------------------------------------------------------------*/
 if ( ! function_exists( 'fau_blogroll' ) ) :
 function fau_blogroll($posttag = '', $postcat = '', $num = 4, $divclass= '', $hstart = 2, $hidemeta = false) {
    $posttag = $posttag ? esc_attr( $posttag ) : '';
        
    if ((!isset($posttag)) && (!isset($postcat))) {
	// kein wert gesetzt, also nehm ich die letzten Artikel
	$postcat =0;
    } else {
	if (is_string($posttag)) {
	    $posttag = fau_get_tag_ID($posttag);
	}
	$postcat = fau_get_cat_ID($postcat);
    }
    
    if (!is_int($num)) {
	$num = 4;
    }
    if (!is_int($hstart)) {
	$hstart = 2;
    }
    $divclass = $divclass ? esc_attr( $divclass ) : '';

    
    $parameter =  array(
        'posts_per_page'	=> $num,
        'post_status'		=> 'publish',
        'ignore_sticky_posts'	=> 1,
    );
    $found = 0;
    if ((isset($posttag)) && ($posttag >= 0)) {
	$parameter['tag_id'] = $posttag;
	$found =1;
    }
    if ((isset($postcat)) && ($postcat >= 0)) {
	$parameter['cat'] = $postcat;
	$found =2;
    }
    if ($found==0) {
	return;
    }
    $blogroll_query = new WP_Query($parameter );
    $out = '<section class="blogroll '.$divclass.'">';
    if($blogroll_query->have_posts()) :
	while($blogroll_query->have_posts()) : 
	    $blogroll_query->the_post();
	    $id = get_the_ID();
	    $out .= fau_display_news_teaser($id,true,$hstart,$hidemeta);
	endwhile; 
    endif; // have_posts()                  
     
    wp_reset_postdata();
    $out .= '</section>'."\n";
    return $out;
 }
 endif;
  /*-----------------------------------------------------------------------------------*/
 /* Display blog entries as list
 /*-----------------------------------------------------------------------------------*/
 if ( ! function_exists( 'fau_articlelist' ) ) :
 function fau_articlelist($posttag = '', $postcat = '', $num = 5, $divclass= '', $title = '') {
    $posttag = $posttag ? esc_attr( $posttag ) : '';
    
if ((!isset($posttag)) && (!isset($postcat))) {
	// kein wert gesetzt, also nehm ich die letzten Artikel
	$postcat =0;
    } else {
	if (is_string($posttag)) {
	    $posttag = fau_get_tag_ID($posttag);
	}
	$postcat = fau_get_cat_ID($postcat);
    }
    
    if (!is_int($num)) {
	$num = 5;
    }
    $divclass = $divclass ? esc_attr( $divclass ) : '';

 
    $parameter =  array(
        'posts_per_page'	=> $num,
        'post_status'		=> 'publish',
        'ignore_sticky_posts'	=> 1,
    );
    $found = 0;
    if ((isset($posttag)) && ($posttag >= 0)) {
	$parameter['tag_id'] = $posttag;
	$found =1;
    }
    if ((isset($postcat)) && ($postcat >= 0)) {
	$parameter['cat'] = $postcat;
	$found =2;
    }
    if ($found==0) {
	return;
    }
    $blogroll_query = new WP_Query($parameter );
    

    $divclass = $divclass ? esc_attr( $divclass ) : '';
    $title =  esc_attr( $title );
   

    $out ='';
    if (!empty($title)) {
        $out .= '<section class="section_articlelist"><h2>'.$title.'</h2>';
    }
    $out .= '<ul class="articlelist '.$divclass.'">';
    if($blogroll_query->have_posts()) :
	while($blogroll_query->have_posts()) : 
	    $blogroll_query->the_post();
    
            $out .= '<li>';
            $out .= '<a href="'.esc_url( get_permalink() ).'">';
            $out .= get_the_title();
            $out .= '</a>';
            $out .= '</li>';
	endwhile; 
    endif; // have_posts()                  
     
    wp_reset_postdata();
    $out .= '</ul>'."\n";
    if (!empty($title)) {
        $out .= '</section>';
    }  
    return $out;
 }
 endif;



/*-----------------------------------------------------------------------------------*/
/* Add another esc_url, but also makes URL relative
/*-----------------------------------------------------------------------------------*/
 function fau_esc_url( $url) {
     if (!isset($url)) {
	 $url = home_url("/");
     }
     return fau_make_link_relative(esc_url($url));
 }
 
 function get_fau_template_uri () {
     return get_template_directory_uri();
 }
 
/*-----------------------------------------------------------------------------------*/
/* Makes absolute URL from relative URL
/*-----------------------------------------------------------------------------------*/

 function fau_make_absolute_url( $url) {
    if (!isset($url)) {
	$url = home_url("/");
    } else {
	if (substr($url,0,1)=='/') {
	    $base = home_url();
	    return $base.$url;
	} else {
	    return $url;
	}
    }
 }
 
/*-----------------------------------------------------------------------------------*/
/* Rewrite Template redirects for generated urls
/*-----------------------------------------------------------------------------------*/
// add_action('template_redirect', 'fau_rw_relative_urls');
 
 // removed, due to problems with other plugins that need absolute urls ;/
 // WW: 19.09.2018
 
 /* 
function fau_rw_relative_urls() {
    // Don't do anything if:
    // - In feed
    // - In sitemap by WordPress SEO plugin
    if (is_admin() || is_feed() || get_query_var('sitemap')) {
        return;
    }
    $filters = array(
        'post_type_link',
        'page_link',
        'post_type_archive_link',
        'get_pagenum_link',
        'get_comments_pagenum_link',
        'term_link',
        'search_link',
        'day_link',
        'month_link',
        'year_link',
        'script_loader_src',
        'style_loader_src',
    );
    foreach ($filters as $filter) {
        add_filter($filter, 'fau_make_link_relative');
    }
}
*/
/*-----------------------------------------------------------------------------------*/
/* make urls relative to base url
/*-----------------------------------------------------------------------------------*/
function fau_make_link_relative($url) {
    $orig = $url;
    $current_site_url = get_site_url();   
    if (!empty($GLOBALS['_wp_switched_stack'])) {
        $switched_stack = $GLOBALS['_wp_switched_stack'];
        $blog_id = end($switched_stack);
        if ($GLOBALS['blog_id'] != $blog_id) {
            $current_site_url = get_site_url($blog_id);
        }
    }
    $current_host = parse_url($current_site_url, PHP_URL_HOST);
    $host = parse_url($url, PHP_URL_HOST);
    if($current_host == $host) {
        $url = wp_make_link_relative($url);
    }
    return apply_filters('fau_relative_link',$url, $orig); 
}

/*-----------------------------------------------------------------------------------*/
/* Force srcset urls to be relative
/*-----------------------------------------------------------------------------------*/
add_filter( 'wp_calculate_image_srcset', function( $sources ) {
    if(	! is_array( $sources ) )
       	return $sources;

    foreach( $sources as &$source ) {
        if( isset( $source['url'] ) )
            $source['url'] = fau_esc_url( $source['url']);
    }
    return $sources;

}, PHP_INT_MAX );


 
 /*-----------------------------------------------------------------------------------*/
 /* Custom template tags: Functions for templates and output
 /*-----------------------------------------------------------------------------------*/
function fau_load_template_part($template_name, $part_name=null) {
    ob_start();
    get_template_part($template_name, $part_name);
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
}

/*-----------------------------------------------------------------------------------*/
/* Check for langcode and return it
/*-----------------------------------------------------------------------------------*/
function fau_get_page_langcode($id = 0) {
    if ($id == 0) {
	return;
    }
    
    $langcode = get_post_meta($id, 'fauval_langcode', true);
    $setlang = "";
    if (!fau_empty($langcode)) {
        $sitelang = fau_get_language_main();
	if ($langcode != $sitelang) {
	    $setlang = ' lang="'.$langcode.'"';
	}
    }
    return $setlang;
}
/*-----------------------------------------------------------------------------------*/
/* getAccordionbyTheme if RRZE ELement Accordions are not active
/*-----------------------------------------------------------------------------------*/
 function getAccordionbyTheme($id = 0, $title = '', $color= '', $load = '', $name= '', $content = '') {
	$addclass = '';
	$title = esc_attr($title);
	$color = $color ? ' ' . esc_attr($color) : '';
	$load = $load ? ' ' . esc_attr($load) : '';
	$name = $name ? ' name="' . esc_attr($name) . '"' : '';

	
	/* TODO:
	 * 1. Add detection of rrze-elements plugin and if its there and active, use it.
	 * 2. Later: Add a warning prompt to activate rrze-elements instead of
	 *    returning the content
	 * 
	 */
	
	
	if (empty($title) && (empty($content))) {
	    return;
	}
	if (!empty($load)) {
	    $addclass .= " " . $load;
	}

	$id = intval($id) ? intval($id) : 0;
	if ($id < 1) {
	    if (!isset($GLOBALS['current_collapse'])) {
		$GLOBALS['current_collapse'] = 0;
	    } else {
		$GLOBALS['current_collapse'] ++;
	    }
	    $id = $GLOBALS['current_collapse'];
	}
	$output = '<div class="accordion-group' . $color . '">';
	$output .= '<h3 class="accordion-heading"><button class="accordion-toggle" data-toggle="collapse" href="#collapse_' . $id . '">' . $title . '</button></h3>';
	$output .= '<div id="collapse_' . $id . '" class="accordion-body' . $addclass . '"' . $name . '>';
	$output .= '<div class="accordion-inner clearfix">';

	$output .= do_shortcode(trim($content));

	$output .= '</div></div>';  // .accordion-inner & .accordion-body
	$output .= '</div>';        // . accordion-group

	return $output;
}

/*-----------------------------------------------------------------------------------*/
/* Add langcode to title if need
/*-----------------------------------------------------------------------------------*/
function fau_get_the_title($id = 0) {
    global $post;
    if ($id==0) {
	$id = $post->ID;
    }
    
    if (is_page($id)) {
	$titlelangcode = get_post_meta($id, 'fauval_pagetitle_langcode', true);
	if (!fau_empty($titlelangcode)) {
	    $sitelang = fau_get_language_main();
	    if ($titlelangcode != $sitelang) {
		$res = '<span lang="'.$titlelangcode.'">';
		$res .=get_the_title($id);
		$res .= '</span>';
		return $res;
	    }
	} 
    } else {
	return get_the_title($id);
    }
}
/*-----------------------------------------------------------------------------------*/
/* create HTML for image figcaption
/*-----------------------------------------------------------------------------------*/
function fau_get_image_figcaption($atts = array(), $type = 'post-image', $class = 'post-image-caption') {
    // $type:
    //    'post-image': Standard Post Image
    
    
    $out = '';
    if (!empty($atts)) {
	$caption_content = '';
	
	// Fill with default behaviour; Will be overwritten by cases
	if ($atts['excerpt'] &&  !fau_empty($atts['excerpt'])) {
	    $caption_content = trim(strip_tags( $atts['excerpt'] ));	
	} elseif (isset($atts['description'])) {
	    // Fallback to description, if avaible
	    $caption_content = trim(strip_tags( $atts['description'] ));	
	} elseif (isset($atts['title'])) {
	    // Fallback to title, if avaible  
	    $caption_content = trim(strip_tags( $atts['title'] ));	
	}
	
	if ($type == 'post-image') {
	    // Default Post Image in Post Display
	    if (isset($atts['fauval_overwrite_thumbdesc'])) {
		$caption_content = $atts['fauval_overwrite_thumbdesc'];
	    } else {
		
		$displaycredits = get_theme_mod("advanced_display_postthumb_credits");
		if (($displaycredits==true) && (!fau_empty($atts['copyright'])) && ($caption_content !== trim(strip_tags(  $atts['copyright'])))) {
		    $caption_content .= '<span class="copyright">'.trim(strip_tags(  $atts['copyright'])).'</span>';    
		}	    
	    }
	    
	}
	
	if (!fau_empty($caption_content)) {
	    $out = '<figcaption class="'.$class.'">';
	    $out .= $caption_content;
	    $out .= '</figcaption>';
	}
    } 
    return $out;  
}
/*-----------------------------------------------------------------------------------*/
/* create HTML for image with srcset-codes
/*-----------------------------------------------------------------------------------*/
function fau_get_image_htmlcode($id = 0, $size = 'rwd-480-3-2', $alttext = '', $classes = '', $atts = array()) {
    if ($id==0) {
	return;
    }

    $img = wp_get_attachment_image_src($id, $size);
    if ($img) {
	
	$attributes = '';
	if (!empty($atts)) {
	    foreach ( $atts as $attr => $value ) {
		 if ( ! empty( $value ) ) {
			$attributes .= ' ' . $attr . '="' . $value . '"';
		}
	    }
	}
        
	$widthstr = '';
	if ((isset($img[1])) && ($img[1] > 0)) {
           $widthstr = ' width="'.$img[1].'"';
        }
        $heightstr = '';
	if ((isset($img[2])) && ($img[2] > 0)) {
           $heightstr = ' height="'.$img[2].'"';
        }
        // In case of svg images, width and height are empty
	
	$imgsrcset =  wp_get_attachment_image_srcset($id, $size);
	$imgsrcsizes = wp_get_attachment_image_sizes($id, $size);
	$alttext = esc_html($alttext);
	if (!isset($alttext)) {
	    $imgmeta = fau_get_image_attributs($id);
	    $alttext = $imgmeta['alt'];
	}
	$item_output = '';
	$item_output .= '<img src="'.fau_esc_url($img[0]).'"'.$widthstr.$heightstr.' alt="'.$alttext.'"';
	if ($imgsrcset) {
	    $item_output .= ' srcset="'.$imgsrcset.'"';
	    if ($imgsrcsizes) {
		 $item_output .= ' sizes="'.$imgsrcsizes.'"';
	    }
	}
	if ($classes) {
	     $item_output .= ' class="'.$classes.'"';
	}
	if ($attributes) {
	     $item_output .= $attributes;
	}
	$item_output .= '>';
	return $item_output;
    }
    return;
}
/*-----------------------------------------------------------------------------------*/
/* get fallback image by size
/*-----------------------------------------------------------------------------------*/
function fau_get_image_fallback_htmlcode($size = 'rwd-480-3-2', $alttext = '', $classes = '', $atts = array()) {
   global $options;
   global $defaultoptions;
   
    $imgsrc =  $imgsrcset = $imgsrcsizes = '';
    $alttext = esc_html($alttext);
    $width = $height = 0;
    
    switch ($size) {

	case 'topevent_thumb':
	    $imgsrc = $options['default_rwdimage_src'];
	    $width = $defaultoptions['default_rwdimage_width'];
	    $height = $defaultoptions['default_rwdimage_height'];
	    
	    $fallback = get_theme_mod('fallback_topevent_image');
	    if ($fallback) {
		$thisimage = wp_get_attachment_image_src( $fallback,  'rwd-480-3-2');
		$imgsrc = $thisimage[0]; 	
		$width = $thisimage[1];
		$height = $thisimage[2];
		$imgsrcset =  wp_get_attachment_image_srcset($fallback, 'rwd-480-3-2');
		$imgsrcsizes = wp_get_attachment_image_sizes($fallback, 'rwd-480-3-2');
	    }

	    break; 
	
	case 'post-thumb':
	    $imgsrc = $options['default_rwdimage_src'];
	    $width = $defaultoptions['default_rwdimage_width'];
	    $height = $defaultoptions['default_rwdimage_height'];
	    $fallback = get_theme_mod('default_postthumb_image');
	    if ($fallback) {
		$thisimage = wp_get_attachment_image_src( $fallback, 'rwd-480-3-2');
		$imgsrc = $thisimage[0]; 	
		$width = $thisimage[1];
		$height = $thisimage[2];
		$imgsrcset =  wp_get_attachment_image_srcset($fallback, 'rwd-480-3-2');
		$imgsrcsizes = wp_get_attachment_image_sizes($fallback,'rwd-480-3-2');
	    }
 
	    break;
	    
	case 'fallback_submenu_image':
	    $imgsrc = $options['default_rwdimage_2-1_src'];
	    $width = $options['default_rwdimage_2-1_width'];
	    $height = $options['default_rwdimage_2-1_height']; 
	    $fallback = get_theme_mod('fallback_submenu_image');
	    if ($fallback) {
		$thisimage = wp_get_attachment_image_src( $fallback,  'rwd-480-2-1');
		$imgsrc = $thisimage[0]; 	
		$width = $thisimage[1];
		$height = $thisimage[2];
		$imgsrcset =  wp_get_attachment_image_srcset($fallback, 'rwd-480-2-1');
		$imgsrcsizes = wp_get_attachment_image_sizes($fallback, 'rwd-480-2-1');
	    }
	    
	    
	    break;
	    

	case 'rwd-480-2-1':
	case 'page-thumb':
	    $imgsrc = $options['default_rwdimage_2-1_src'];
	    $width = $options['default_rwdimage_2-1_width'];
	    $height = $options['default_rwdimage_2-1_height']; 
	    break;
	case 'rwd-480-3-2':
	    $imgsrc = $options['default_rwdimage_src'];
	    $width = $options['default_rwdimage_width'];
	    $height = $options['default_rwdimage_height']; 
	    break;

	default:
	    $imgsrc = $options['default_rwdimage_src'];
	    $width = $options['default_rwdimage_width'];
	    $height = $options['default_rwdimage_height']; 
	    break;
    }
    if ($imgsrc) {
	$attributes = '';
	if (!empty($atts)) {
	    foreach ( $atts as $attr => $value ) {
		 if ( ! empty( $value ) ) {
			$attributes .= ' ' . $attr . '="' . $value . '"';
		}
	    }
	}
	$item_output = '';
	$item_output .= '<img src="'.fau_esc_url($imgsrc).'" width="'.$width.'" height="'.$height.'" alt="'.$alttext.'"';
	if ($imgsrcset) {
	    $item_output .= ' srcset="'.$imgsrcset.'"';
	    if ($imgsrcsizes) {
		 $item_output .= ' sizes="'.$imgsrcsizes.'"';
	    }
	}
	if ($classes) {
	     $item_output .= ' class="'.$classes.'"';
	}
	if ($attributes) {
	     $item_output .= $attributes;
	}
	$item_output .= '>';
	return $item_output;
    
    }

    return;
}
/*-----------------------------------------------------------------------------------*/
/* This is the end :)
/*-----------------------------------------------------------------------------------*/
		
