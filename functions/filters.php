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
	$title .= get_bloginfo( 'name' );

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
	if(isset($_GET['post_type'])) {
	    $types = (array) $_GET['post_type'];
	} else {
	    $types = get_theme_mod('search_post_types');
	  //  $types = array("person", "post", "page", "attachment");
	  //  $types = array("attachment","person");
	}
	$allowed_types = get_post_types(array('public' => true, 'exclude_from_search' => false));
	foreach($types as $type) {
	    if( in_array( $type, $allowed_types ) ) { $filter_type[] = $type; }
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
add_filter('the_content', 'remove_empty_p', 20, 1);
function remove_empty_p($content){
    $content = force_balance_tags($content);
    return preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
}

add_filter('the_content', 'remove_accordion_bad_br', 20, 1);
function remove_accordion_bad_br($content){
   // $content = force_balance_tags($content);
    return preg_replace('#<br\s*/*>\s*<div class="accordion#i', '<div class="accordion', $content);
}

add_filter('the_content', 'remove_bad_p', 20, 1);
function remove_bad_p($content){
   // $content = force_balance_tags($content);
    $content = preg_replace('#<p><div #i', '<div ', $content);
    return preg_replace('#</div></p>#i', '</div>', $content);
}

/*-----------------------------------------------------------------------------------*/
/* Filter for postcount
/*-----------------------------------------------------------------------------------*/
add_filter('wp_list_categories','categories_postcount_filter');
function categories_postcount_filter ($variable) {
   $variable = str_replace('(', '<span class="post_count">(', $variable);
   $variable = str_replace(')', ')</span>', $variable);
   return $variable;
}

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
/* Force srcset urls to be relative
 * Note: We are using our own function for relative links here (fau_make_link_relative),
 * cause this function will only make relative links to these urls, that are on the
 * same host. Therfor external images wont be changed.
 
function fau_make_srcset_relative_on_sitehost($sources, $size_array, $image_src, $image_meta, $attachment_id ) {
    foreach ( $sources as &$source ) {
        $source['url'] = fau_make_link_relative( $source['url'] );
    }
    return $sources;
}
 * Deactivated 14.06.2023, WW: Macht weiter Probleme mit externen Domains bei 
 * dem FAU Studium EMbed Plugin.
 * Die paar Bytes Performancegewinn lohnen nicht für die Ursachenfindung. 
 * Daher erstmal raus
 */
// add_filter( 'wp_calculate_image_srcset', 'fau_make_srcset_relative_on_sitehost', 10, 5 );

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

