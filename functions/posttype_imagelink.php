<?php
/*-----------------------------------------------------------------------------------*/
/* Verwaltung der Bildlinks / Logos
/*-----------------------------------------------------------------------------------*/

/*
 * TODO/ISSUE: Als eigenen Plugin auslagern oder in Plugin RRZE Elements übernehmen
 */


/*-----------------------------------------------------------------------------------*/
/* Register Imagelink Taxonomy 
/*-----------------------------------------------------------------------------------*/
function imagelink_taxonomy() {
	register_taxonomy(
		'imagelinks_category',  
		'imagelink',   		
		array(
			'hierarchical' 	=> true,
			'label' 	=> __('Bildlink-Kategorien', 'fau'), 
			'query_var' 	=> true,
			'rewrite'	=> array(
			    'slug' 		=> 'imagelinks',
			    'with_front' 	=> false
			)
		)
	);
}
add_action( 'init', 'imagelink_taxonomy');

/*-----------------------------------------------------------------------------------*/
/* Register Imagelink Custom Post Type 
/*-----------------------------------------------------------------------------------*/
function imagelink_post_type() {

	$labels = array(
		'name'                => _x( 'Bildlinks', 'Post Type General Name', 'fau' ),
		'singular_name'       => _x( 'Bildlink', 'Post Type Singular Name', 'fau' ),
		'menu_name'           => __( 'Bildlinks', 'fau' ),
		'parent_item_colon'   => __( 'Übergeordneter Bildlink', 'fau' ),
		'all_items'           => __( 'Alle Bildlinks', 'fau' ),
		'view_item'           => __( 'Bildlink anzeigen', 'fau' ),
		'add_new_item'        => __( 'Neuen Bildlink einfügen', 'fau' ),
		'add_new'             => __( 'Neuer Bildlink', 'fau' ),
		'edit_item'           => __( 'Bildlink bearbeiten', 'fau' ),
		'update_item'         => __( 'Bildlink aktualisieren', 'fau' ),
		'search_items'        => __( 'Bildlink suchen', 'fau' ),
		'not_found'           => __( 'Keine Bildlinks gefunden', 'fau' ),
		'not_found_in_trash'  => __( 'Keine Bildlinks im Papierkorb gefunden', 'fau' ),
	);
	$args = array(
		'label'               => __( 'imagelink', 'fau' ),
		'description'         => __( 'Bildlink-Eigenschaften', 'fau' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail' ),
		'taxonomies'          => array( 'imagelinks_category' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
  	 	'menu_icon'		=> 'dashicons-format-image',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'query_var'           => 'imagelink',
		'rewrite'             => false,
	
	);
	register_post_type( 'imagelink', $args );

}
add_action( 'init', 'imagelink_post_type', 0 );


/*-----------------------------------------------------------------------------------*/
/* Define Metabox
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'fau_imagelink_metabox' ) ) {
    function fau_imagelink_metabox() {
	add_meta_box(
	    'fau_imagelink_metabox',
	    __( 'Eigenschaften', 'fau' ),
	    'fau_imagelink_metabox_content',
	    'imagelink',
	    'normal',
	    'high'
	);
    }
}
if ( ! function_exists( 'fau_imagelink_metabox_content' ) ) {
    function fau_imagelink_metabox_content( $object, $box ) { 
	global $post;


	wp_nonce_field( basename( __FILE__ ), 'fau_imagelink_metabox_content_nonce' ); 

	if ( !current_user_can( 'edit_page', $object->ID) )
		// Oder sollten wir nach publish_pages  fragen? 
		// oder nach der Rolle? vgl. http://docs.appthemes.com/tutorials/wordpress-check-user-role-function/ 
	    return;


	$targeturl = get_post_meta( $object->ID, 'fauval_imagelink_url', true );
	$desc  = get_post_meta( $object->ID, 'portal_description', true );

			
	fau_form_url('fau_imagelink_url', $targeturl, __('Webadresse','fau'), '', $placeholder='https://');   
	fau_form_text('fau_imagelink_desc', $desc, __('Kurzbeschreibung','fau'));

	return;

    }
}
add_action( 'add_meta_boxes', 'fau_imagelink_metabox' );

/*-----------------------------------------------------------------------------------*/
/* Save values of metabox
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'fau_imagelink_metabox_content_save' ) ) {
    function fau_imagelink_metabox_content_save( $post_id ) {
	if (  'imagelink'!= get_post_type()  ) {
	    return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
	    return;

	if ( !isset( $_POST['fau_imagelink_metabox_content_nonce'] ) || !wp_verify_nonce( $_POST['fau_imagelink_metabox_content_nonce'], basename( __FILE__ ) ) )
	    return $post_id;


	if ( !current_user_can( 'edit_post', $post_id ) )
	    return;



	fau_save_standard('fauval_imagelink_url', $_POST['fau_imagelink_url'], $post_id, 'imagelink', 'url');
	fau_save_standard('portal_description', $_POST['fau_imagelink_desc'], $post_id, 'imagelink', 'text');

    }
}
add_action( 'save_post', 'fau_imagelink_metabox_content_save' );

/*-----------------------------------------------------------------------------------*/
/* Display imagelink slider
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'fau_imagelink_get' ) ) {
    function fau_imagelink_get( $atts = array()) {
	global $defaultoptions;
	
	$allowedsizes = array("logo-thumb", "post-thumbnails", "thumbnail", "x120", "x240", "x360", "x480");
	$dots =   (( isset($atts['dots'])  && ($atts['dots']===true)) ?  'true'  : 'false' );
	$echo =   (( isset($atts['echo'])  && ($atts['echo']===true)) ?  'true'  : 'false' );
	$autoplay =   (( isset($atts['autoplay'])  && ($atts['autoplay']==true)) ?  'true'  : 'false' );
	$slidesToShow = ( isset($atts['slidesToShow'] ) ? intval( $atts['slidesToShow'] ) : 4 );
	$catid = ( isset($atts['catid'] ) ? intval( $atts['catid'] ) : 0 );
	$cat = ( isset($atts['cat'] ) ? esc_attr( $atts['cat'] ) : '' );
	$order = ( isset($atts['order'] ) ? esc_attr( $atts['order'] ) : 'ASC' );
	$size = ( isset($atts['size'] ) ? esc_attr( $atts['size'] ) : 'logo-thumb' );

	$navtitle = ( isset($atts['navtitle'] ) ? sanitize_text_field( $atts['navtitle'] ) : '' );
	$class = ( isset($atts['class'] ) ? sanitize_text_field( $atts['class'] ) : '' );

	$type = ( isset($atts['type'] ) ? esc_attr( $atts['type'] ) : 'slide' );

	if (!in_array($size, $allowedsizes)) {
	    $size = 'thumbnail';
	}


	if ( isset($catid) && $catid >0) {
	    $args = array(
	       'post_type'  => 'imagelink',
	       'nopaging'   => 1,
	       'orderby'    => 'name', 
	       'order'	    => $order,
	       'tax_query'  => array(
			array(
			   'taxonomy' => 'imagelinks_category',
			   'field' => 'id', // can be slug or id - a CPT-onomy term's ID is the same as its post ID
			   'terms' => $catid,

		       )
	       )
	    );	 
	} elseif (!empty($cat)) {
	     $args = array(
	       'post_type'  => 'imagelink',
	       'nopaging'	  => 1,
	       'orderby'	  => 'name', 
	       'order'	  => $order,
	       'tax_query'  => array(
			array(
			   'taxonomy' => 'imagelinks_category',
			   'field' => 'slug', // can be slug or id - a CPT-onomy term's ID is the same as its post ID
			   'terms' => $cat,

		       )
	       )
	    );	
	} else {
	    $args = array(
	       'post_type'	=> 'imagelink',
	       'nopaging'	=> 1,
	       'orderby'	=> 'name', 
	       'order'	=> $order
	    );
	}



	$imagelist = get_posts($args); 
	$number =0;
	$item_output = $output = $mainclass = '';
	$rand = rand();	    


	foreach($imagelist as $item) {
	    $number++;
	    $currenturl  = get_post_meta( $item->ID, 'fauval_imagelink_url', true );

	    $item_output .= '<div class="slick-item';
	    if (!empty($class)) {
		$item_output .= ' '.$class;
	    }
	    $item_output .= '">';
	    $item_output .= '<a rel="nofollow" href="'.$currenturl.'">';
	    $alttext = get_the_title($item->ID);
	    $alttext = esc_html($alttext);
	    if (empty($alttext)) {
		$alttext = __("Zum Webauftritt: ", 'fau').$currenturl;
	    }

	    $id = get_post_thumbnail_id( $item->ID ); 
	    $item_output .= fau_get_image_htmlcode($id, 'rwd-480-3-2', $alttext);
	    
	    $item_output .= '</a>';
	    $item_output .= '</div>';
	}

	if ($number>0) {
	    $output .= '<nav aria-label="'.$navtitle.'" class="imagelink';

	    if (isset($type) && ($type == 'list')) {
		$output .= " list";
	    } elseif ($dots) {
		$output .= " dots";
	    }
	    $output .= '">';
	    $mainclass .= $size;
	    $mainclass .= ' slider-for-'.$rand;
	     $output .= '<div class="'.$mainclass.'">';

	    $output .= $item_output;
	    $output .= '</div>';
	    $output .= "</nav>";
	    if (isset($type) && ($type == 'slide')) {
		$output .= "<script type=\"text/javascript\"> jQuery(document).ready(function($) {";		
		$output .= "$('.slider-for-$rand').slick({ slidesToScroll: 1, focusOnSelect: true";
		$output .= ", variableWidth: true";		
		$output .= ", slidesToShow: $slidesToShow";
		$output .= ", dots: $dots";
		$output .= ", autoplay: $autoplay";
		$output .= ", responsive: [{
		      breakpoint: 768,
		      settings: {
			arrows: false,
			slidesToShow: 3
		      }
		    },
		    {
		      breakpoint: 480,
		      settings: {
			arrows: false,
			slidesToShow: 1
		      }
		    }]";
		$output .= "});";
		$output .= "});</script>";

		wp_enqueue_script('fau-js-heroslider');
	    }
	    if ($echo === true) {
		echo $output;
		return;
	    } else {
		return $output;
	    }
	}

    }
}
/*-----------------------------------------------------------------------------------*/
/* Shortcode for image slider
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'fau_imagelink_shortcode' ) ) {
    function fau_imagelink_shortcode( $atts ) {

	$args = shortcode_atts( array(
	    'catid'		=> 0,
	    'cat'		=> '',
	    'echo'		=> false, 
	    'order'		=> 'ASC', 
	    'dots'		=> true, 
	    'autoplay'		=> true, 
	    'slidesToShow'	=> 4, 
	    'class'		=> '', 
	    'type'		=> 'slide', 
	    'size'		=> 'thumbnail',
	    'navtitle'	=> __('Partnerlogos', 'fau') 
	    ), $atts, 'imagelink' );

	$args['autoplay'] = filter_var( $args['autoplay'], FILTER_VALIDATE_BOOLEAN );
	$args['echo'] = filter_var( $args['echo'], FILTER_VALIDATE_BOOLEAN );
	$args['dots'] = filter_var( $args['dots'], FILTER_VALIDATE_BOOLEAN );

	return fau_imagelink_get($args);   
    }
}
add_shortcode('imagelink', 'fau_imagelink_shortcode' );

/*-----------------------------------------------------------------------------------*/
/* Display imagelink slider (OLD)
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'fau_get_imagelinks' ) ) {
    function fau_get_imagelinks ( $catid, $echo = true ) {
	return fau_imagelink_get(array('size' => "logo-thumb", 'catid' => $catid, "autoplay" => true, "dots" => true, 'echo' => $echo));
    }
}
/*-----------------------------------------------------------------------------------*/
/* EOF
/*-----------------------------------------------------------------------------------*/