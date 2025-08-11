<?php

/**
* @package WordPress
* @subpackage FAU
* @since FAU 1.12.34
*/
/*-----------------------------------------------------------------------------------*/
/* Change default title
/*-----------------------------------------------------------------------------------*/
function fau_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Seite %s', 'fau' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'fau_wp_title', 10, 2 );

/*-----------------------------------------------------------------------------------*/
/* Resets the Excerpt More
/*-----------------------------------------------------------------------------------*/
function fau_excerpt_more( $more ) {
    return get_theme_mod('default_excerpt_morestring');
}
add_filter('excerpt_more', 'fau_excerpt_more');


/*-----------------------------------------------------------------------------------*/
/* Changes default length for excerpt
/*-----------------------------------------------------------------------------------*/
function fau_excerpt_length( $length ) {    
    return get_theme_mod('default_excerpt_length');
}
add_filter( 'excerpt_length', 'fau_excerpt_length' );


/*-----------------------------------------------------------------------------------*/
/*  Refuse spam-comments on media
/*-----------------------------------------------------------------------------------*/
function filter_media_comment_status( $open, $post_id ) {
	$post = get_post( $post_id );
	if( $post->post_type == 'attachment' ) {
		return false;
	}
	return $open;
}
add_filter( 'comments_open', 'filter_media_comment_status', 10 , 2 );
/*-----------------------------------------------------------------------------------*/
/*  Remove Inline Style for recent comments
/*-----------------------------------------------------------------------------------*/
add_filter( 'show_recent_comments_widget_style', '__return_false' );


/*-----------------------------------------------------------------------------------*/
/*  Search filter
/*-----------------------------------------------------------------------------------*/
function fau_searchfilter($query) {
    if ($query->is_search && !is_admin() ) {
        $allowed_types = get_post_types(array('public' => true, 'exclude_from_search' => false));
        if(isset($_GET['post_type'])) {
            $types = (array) $_GET['post_type'];
        } else {
            $types = get_theme_mod('search_post_types');
          //  $types = array("person", "post", "page", "attachment");
          //  $types = array("attachment","person");
             if (empty($types)) {
                $filter_type = $allowed_types;
            }
        }
       
        
        foreach($types as $type) {
            if (in_array( $type, $allowed_types ) ) { 
                $filter_type[] = $type; 
            }
        }
        if(count($filter_type)) {
            $query->set('post_type',$filter_type);
        }	
        $query->set('post_status', array('publish','inherit'));

    }
}
add_filter("pre_get_posts","fau_searchfilter");


/*-----------------------------------------------------------------------------------*/
/*  Search sorting
/*-----------------------------------------------------------------------------------*/
add_filter('posts_orderby','fau_sort_custom',10,2);
function fau_sort_custom( $orderby, $query ){
    global $wpdb;

    if(!is_admin() && is_search())
    //    $orderby =  $wpdb->prefix."posts.post_type ASC, {$wpdb->prefix}posts.post_date DESC";
	 $orderby =  $wpdb->prefix."posts.post_modified DESC";

    return  $orderby;
}


/*-----------------------------------------------------------------------------------*/
/* wplink query args adjustment
/*-----------------------------------------------------------------------------------*/
function fau_wp_link_query_args( $query ) {
     // check to make sure we are not in the admin
   //  if ( !is_admin() ) {
          $query['post_type'] = array( 'post', 'page', 'person'  ); // show only posts and pages
   //  }
     return $query;
}
add_filter( 'wp_link_query_args', 'fau_wp_link_query_args' ); 


/*-----------------------------------------------------------------------------------*/
/*  display ids for pages columns and custom types
/*-----------------------------------------------------------------------------------*/
function fau_revealid_add_id_column( $columns ) {
   $columns['revealid_id'] = 'ID';
   return $columns;
}

function fau_revealid_id_column_content( $column, $id ) {
  if( 'revealid_id' == $column ) {
    echo $id;
  }
}
if (get_theme_mod('advanced_reveal_pages_id')) {
    add_filter( 'manage_pages_columns', 'fau_revealid_add_id_column', 5 );
    add_action( 'manage_pages_custom_column', 'fau_revealid_id_column_content', 5, 2 );
}

/*-----------------------------------------------------------------------------------*/
/* Filter bad paragraphs - fallback
/*-----------------------------------------------------------------------------------*/
add_filter('the_content', 'fau_remove_empty_p', 20, 1);
function fau_remove_empty_p($content){
    $content = force_balance_tags($content);
    return preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
}

add_filter('the_content', 'fau_remove_accordion_bad_br', 20, 1);
function fau_remove_accordion_bad_br($content){
   // $content = force_balance_tags($content);
    return preg_replace('#<br\s*/*>\s*<div class="accordion#i', '<div class="accordion', $content);
}

add_filter('the_content', 'fau_remove_bad_p', 20, 1);
function fau_remove_bad_p($content){
   // $content = force_balance_tags($content);
    $content = preg_replace('#<p><div #i', '<div ', $content);
    return preg_replace('#</div></p>#i', '</div>', $content);
}
/*-----------------------------------------------------------------------------------*/
/* Remove font-Angaben aus Absätzen oder Spans.
/*-----------------------------------------------------------------------------------*/
function fau_remove_font_attributes_from_content($content) {
    $fau_sanitize_inlinestyles = get_theme_mod('advanced_sanitize_inlinestyles');

    if (!$fau_sanitize_inlinestyles) {
       return $content;
    }
    
    // Entfernt jegliche font-* Attribute aus den style-Attributen von diversen Tags
    $content = preg_replace_callback('/<(p|span|h1|h2|h3|h4|h5|h6)\b([^>]*?)\sstyle="([^"]*)"\s?([^>]*)>/i', function ($matches) {
        // Entfernt alle style-Deklarationen, die mit "font-" beginnen
        $newStyle = preg_replace('/font-[^:]+:\s?[^;]+;?\s*/i', '', $matches[3]);

        // Falls nach dem Entfernen das style-Attribut leer ist, wird es komplett entfernt
        if (trim($newStyle) === '') {
            return "<{$matches[1]}{$matches[2]}{$matches[4]}>";
        } else {
            return "<{$matches[1]}{$matches[2]} style=\"$newStyle\"{$matches[4]}>";
        }
    }, $content);

    $content = preg_replace('/\sstyle="\s*"/i', '', $content);
    return $content;
}
add_filter('the_content', 'fau_remove_font_attributes_from_content');
/*-----------------------------------------------------------------------------------*/
/* Remove text-Angaben aus Absätzen oder Spans.
/*-----------------------------------------------------------------------------------*/
function fau_remove_text_attributes_from_content($content) {
    $fau_sanitize_inlinestyles = get_theme_mod('advanced_sanitize_inlinestyles');

    if (!$fau_sanitize_inlinestyles) {
       return $content;
    }
    // Entfernt jegliche font-* Attribute aus den style-Attributen von diversen Tags
    $content = preg_replace_callback('/<(p|span|h1|h2|h3|h4|h5|h6)\b([^>]*?)\sstyle="([^"]*)"\s?([^>]*)>/i', function ($matches) {
        // Entfernt alle style-Deklarationen, die mit "font-" beginnen
        $newStyle = preg_replace('/text-[^:]+:\s?[^;]+;?\s*/i', '', $matches[3]);

        // Falls nach dem Entfernen das style-Attribut leer ist, wird es komplett entfernt
        if (trim($newStyle) === '') {
            return "<{$matches[1]}{$matches[2]}{$matches[4]}>";
        } else {
            return "<{$matches[1]}{$matches[2]} style=\"$newStyle\"{$matches[4]}>";
        }
    }, $content);

    $content = preg_replace('/\sstyle="\s*"/i', '', $content);
    return $content;
}
add_filter('the_content', 'fau_remove_text_attributes_from_content');

/*-----------------------------------------------------------------------------------*/
/* Filter empty lists 
 * Reason: https://www.w3.org/TR/wai-aria-1.1/#mustContain
 */
/*-----------------------------------------------------------------------------------*/
add_filter('the_content', 'fau_remove_empty_list', 20, 1);
function fau_remove_empty_list($content){
    $content = force_balance_tags($content);
    return preg_replace('#<ul>\s*+(<br\s*/*>)?\s*</ul>#i', '<ul><li>&nbsp;</li></ul>', $content);
}

/*-----------------------------------------------------------------------------------*/
/* Filter for postcount
/*-----------------------------------------------------------------------------------*/
function categories_postcount_filter ($variable) {
   $variable = str_replace('(', '<span class="post_count">(', $variable);
   $variable = str_replace(')', ')</span>', $variable);
   return $variable;
}
add_filter('wp_list_categories','categories_postcount_filter');

/*-----------------------------------------------------------------------------------*/
/* Add css class to linked images and lightbox to content images
/*-----------------------------------------------------------------------------------*/
function fau_add_classes_to_linked_images($content) {
    $classes = 'media-img'; // can do multiple classes, separate with space

    if (preg_match('/<a href=\"([^\"]+)\.(bmp|gif|jpeg|jpg|png)(?![\w.\-_])\"><img/i', $content) ) {
        // link geht auf die Bilddatei direkt, ergänze daher die class lightbox, bisher keine class gesetzt
        $pattern = '/<a href=\"([^\"]+)\.(bmp|gif|jpeg|jpg|png)(?![\w.\-_])\"><img/i';
        $replacement = '<a class="lightbox" href="$1.$2"><img';
        $content = preg_replace($pattern, $replacement, $content);
    }

    $patterns = array();
    $replacements = array();
    
    // matches img tag wrapped in anchor tag where anchor tag where anchor has no existing classes
    $patterns[0] = '/<a(?![^>]*class)([^>]*)>\s*<img([^>]*)>\s*<\/a>/'; 
    $replacements[0] = '<a\1 class="' . $classes . '"><img\2></a>';

    // matches img tag wrapped in anchor tag where anchor has existing classes contained in double quotes
    $patterns[1] = '/<a([^>]*)class="([^"]*)"([^>]*)>\s*<img([^>]*)>\s*<\/a>/'; 
    $replacements[1] = '<a\1class="' . $classes . ' \2"\3><img\4></a>';
    $content = preg_replace($patterns, $replacements, $content);  
    
    // copy alignment to a class
    $content = preg_replace('/<a ([^<>]*)\s*class="([^<>"]+)"\s*([^<>]*)><img ([^<>]*)\s*(aligncenter|alignright|alignleft)([^<>]*)>\s*<\/a>/mi', '<a $1 class="$2 $5" $3><img $4 $5 $6></a>', $content);
    return $content;
}
add_filter('the_content', 'fau_add_classes_to_linked_images', 10, 1);



/*-----------------------------------------------------------------------------------*/
/* Remove post class, we dont need
/*-----------------------------------------------------------------------------------*/
add_filter('post_class', 'fau_remove_default_post_class', 10,3);
function fau_remove_default_post_class($classes, $class, $post_id) {
    
    if (is_admin() ) {
        // Do not change anything if we are in the backend
        return $classes;
    }
    // adapted form https://www.forumming.com/question/21152/remove-extra-classes-from-post-title
    
    // Array that holds the undesired classes
    $removeClasses = array(
        'hentry',
        'type-',
        'post-',
        'status-',
        'category-',
        'tag-',
        'format'
    );


    $newClasses = array();
    foreach ($classes as $_class) {
        $hasClass = FALSE;
        foreach ($removeClasses as $_removeClass) {
            if (strpos($_class, $_removeClass) === 0) {
                $hasClass = TRUE;
                break;
            }
        }
        if (!$hasClass) {
            $newClasses[] = $_class;
        }
    }

    return ($newClasses);

}

/*-----------------------------------------------------------------------------------*/
/* Remove post class, we dont need
/*-----------------------------------------------------------------------------------*/
function fau_hide_admin_bar_from_front_end(){
    if (!is_user_logged_in()) {
       return false;
    }
    return true;
}
add_filter( 'show_admin_bar', 'fau_hide_admin_bar_from_front_end' );
/*-----------------------------------------------------------------------------------*/
/* Modify default Tag Cloud
/*-----------------------------------------------------------------------------------*/
function fau_widget_tag_cloud_args($args) {
    $args['largest']  = 4;
    $args['smallest'] = 0.8;
    $args['unit']     = 'rem';

    return $args;
}
add_filter('widget_tag_cloud_args', 'fau_widget_tag_cloud_args', 10, 1 );


/*-----------------------------------------------------------------------------------*/
/* Add Class for  menu items and removes unneeded item-classes
/*-----------------------------------------------------------------------------------*/
function fau_add_subnav_css_class($css_class, $page){
    if (is_array($css_class)) {
        $new_class = array();
        foreach ($css_class as $i => $c) {
            if ($c == 'page_item') {
                //	nope
            } elseif (preg_match("/page\-item\-[0-9]+/", $c, $matches)) {
                 //	dont need
            } else {
                $new_class[] = $c;
            }
        }
    } else {
        $css_class = preg_replace("/page\-item\-[0-9]+/", "", $css_class);
        $new_class = $css_class;
    }
    if ($page->post_password != '') {
        $new_class[] = 'protected-page';
    }
    if (in_array($page->post_status, ['draft', 'pending', 'auto-draft'])) {
        $new_class[] = "draft-page";
    }
    if ($page->post_status == 'future') {
        $new_class[] = "captain-future";
    }
    if ($page->post_status == 'private') {
        $new_class[] = "private-page";
    }  

    return $new_class;
}
add_filter("page_css_class", "fau_add_subnav_css_class", 10, 2);

/*-----------------------------------------------------------------------------------*/
/* Filter to replace the [caption] shortcode text with HTML5 compliant code
/* @return text HTML content describing embedded figure
/*-----------------------------------------------------------------------------------*/

function fau_img_caption_shortcode_filter($val, $attr, $content = null) {
    extract(shortcode_atts(array(
        'id'    => '',
        'align' => '',
        'width' => '',
        'caption' => ''
    ), $attr));

    if ( 1 > (int) $width || empty($caption) )
        return $val;
    $addmaxw = '';
    $capid = '';
    if ( $id ) {
        $id = esc_attr($id);
        $targetid =  $id . '-'.rand();
          // In case the same image is used more as one time in the website, we need to habe a uniq id, therfor add a rand() value here.
            $capid = 'id="figcaption_'.$targetid.'" ';
            $id = 'id="' . $targetid . '" aria-labelledby="figcaption_' . $targetid . '" ';


        if ($width) {
            $addmaxw = ' style="max-width: '.intval($width).'px"';
        }
    }

    return '<figure ' . $id . 'class="wp-caption ' . esc_attr($align) . '" '.$addmaxw.'>'
    . do_shortcode( $content ) . '<figcaption ' . $capid 
    . '>' . $caption . '</figcaption></figure>';
}
add_filter('img_caption_shortcode', 'fau_img_caption_shortcode_filter',10,3);

/*-----------------------------------------------------------------------------------*/
/* Remove type-String from link-reference to follow W3C Validator
/*-----------------------------------------------------------------------------------*/
function fau_remove_type_attr($tag, $handle) {
    return preg_replace( "/ type=['\"]text\/(javascript|css)['\"]/", '', $tag );
}
add_filter('style_loader_tag', 'fau_remove_type_attr', 10, 2);
add_filter('script_loader_tag', 'fau_remove_type_attr', 10, 2);


/*-----------------------------------------------------------------------------------*/
/* Add additional aria-label-attribut for special pages
/*-----------------------------------------------------------------------------------*/
function fau_add_aria_label_pages( $atts, $item, $args ) {  
    $arialabel_subnav = get_post_meta($item->object_id, 'fauval_aria-label', true);
    
    if (!fau_empty($arialabel_subnav)) {
        $atts['aria-label'] = $arialabel_subnav;
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'fau_add_aria_label_pages', 10, 3 );


/*-----------------------------------------------------------------------------------*/
/* Remove the option image size thumbal and medium in edit post
/*-----------------------------------------------------------------------------------*/
// function fau_remove_image_size_options($sizes) {
//    unset($sizes['thumbnail']);
  //  unset($sizes['medium']);
  //   deactivated the unset for medium yet, cause of discussion (11.05.2023, WW)
//    return $sizes;
// }
// add_filter('image_size_names_choose', 'fau_remove_image_size_options');
// deactivated yet, cause of users requests (14.06.2023, WW)

/*-----------------------------------------------------------------------------------*/
/* Remove the target in all links in content
/*-----------------------------------------------------------------------------------*/
 function fau_change_link_targets($content) {
    $pattern = '/<a(.*?)href=[\'"](.*?)[\'"](.*?)(target=[\'"](.*?)[\'"])?(.*?)>/i';
    $replacement = '<a$1href="$2"$3$6>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}
add_filter('the_content', 'fau_change_link_targets');


/*-----------------------------------------------------------------------------------*/
/* Change output for gallery
/*-----------------------------------------------------------------------------------*/
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
	    'id'	=> $post->ID,
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
            $output .= '<div class="flexgrid '.$gridclass.'">'."\n";
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
		    if (isset($attr['link']) && ('none' !== $attr['link'])) {
			    // Bei Bildern, die als Link fungieren beschreibt der alt das Linkziel, nicht das Bild.
			    if (!fau_empty($imgmeta['title'])) {
                    $linkalt = __('Bild','fau').' '.$imgmeta['title'].' '.__('öffnen','fau');
			    } else {
                    $linkalt = __('Bild','fau').' '.__('öffnen','fau');
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
		    if (isset($attr['link']) && ('none' !== $attr['link'])) {
                if ($attr['link']=='post') {
                    // Anhang Seite
                    $output .= '<a tabindex="-1" href="'.get_attachment_link( $id ).'">';		  
                } else {
                    // File
                    $output .= '<a tabindex="-1" href="'.fau_esc_url($img_full[0]).'" class="lightbox" rel="lightbox-'.$rand.'"'.$lightboxattr.'>';		  
                }
		    }

		    $output .= fau_get_image_htmlcode($id, 'gallery-full', $linkalt);
		    
		    if (isset($attr['link']) && ('none' !== $attr['link'])) {
                $output .= '</a>';
		    }

		    if(isset( $attr['captions']) && ($attr['captions']==1) && (!fau_empty($imgmeta['excerpt']))) {
                $output .= '<figcaption>'.$imgmeta['excerpt'].'</figcaption>';
		    }
		    $output .= '</figure>'."\n";
		    $i++;

		   
	    }

	    $output .= '</div>'."\n";
	    $output .= '</div>'."\n";


	} else {
	    if ((!$attr['captions']) && (get_theme_mod('galery_force_caption_onslick'))) {
            $attr['captions'] = 1;
	    }
	    wp_enqueue_script('fau-js-heroslider');

	    $rand = rand();	    
	    $output .= "<div id=\"slider-$rand\" class=\"gallery-slick\" role=\"presentation\">\n";	
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
add_filter('post_gallery', 'fau_post_gallery', 10, 2);

/*-----------------------------------------------------------------------------------*/
/* Blockeditor: Remove h1 from core/heading
/*-----------------------------------------------------------------------------------*/
function fau_coreblocks_remove_h1_heading( $args, $block_type ) {
	
	if ( 'core/heading' !== $block_type ) {
		return $args;
	}

	// Remove H1.
	$args['attributes']['levelOptions']['default'] = [ 2, 3, 4, 5, 6 ];
	
	return $args;
}

add_filter( 'register_block_type_args', 'fau_coreblocks_remove_h1_heading', 10, 2 );

/*-----------------------------------------------------------------------------------*/
/* Remove comments 
/*-----------------------------------------------------------------------------------*/
function fau_conditional_disable_comments() {
    // Kommentare nur aktivieren, wenn Option aktiv ist
    $enable_comments = get_theme_mod('advanced_activate_post_comments', false);

    if ($enable_comments) {
        return; 
    }

    // Kommentare und Trackbacks deaktivieren
    foreach (['post', 'page'] as $post_type) {
        remove_post_type_support($post_type, 'comments');
        remove_post_type_support($post_type, 'trackbacks');
    }

    // Kommentare und Pings komplett schließen
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);

    // Existierende Kommentare ausblenden
    add_filter('comments_array', '__return_empty_array', 10, 2);

    // Kommentare im Admin-Menü entfernen
    add_action('admin_menu', function () {
        remove_menu_page('edit-comments.php');
    });

    add_action('init', function () {
        if (is_admin_bar_showing()) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
        }
    });

    // Kommentar-Metaboxen im Editor entfernen
    add_action('add_meta_boxes', function () {
        remove_meta_box('commentsdiv', 'post', 'normal');
        remove_meta_box('trackbacksdiv', 'post', 'normal');
        remove_meta_box('commentstatusdiv', 'post', 'normal');
        remove_meta_box('commentsdiv', 'page', 'normal');
        remove_meta_box('trackbacksdiv', 'page', 'normal');
        remove_meta_box('commentstatusdiv', 'page', 'normal');
    }, 20);

    // Kommentar-Feed-Links entfernen
    add_filter('feed_links_show_comments_feed', '__return_false'); // deaktiviert Link im <head>
    add_filter('post_comments_feed_link', '__return_false');       // deaktiviert individuelle Feed-Links
}
add_action('init', 'fau_conditional_disable_comments');

/*-----------------------------------------------------------------------------------*/
/* EOF
/*-----------------------------------------------------------------------------------*/