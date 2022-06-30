<?php
/*-----------------------------------------------------------------------------------*/
/* Verwaltung der Bildlinks / Logos
/*-----------------------------------------------------------------------------------*/

/*
 * TODO/ISSUE: Als eigenen Plugin auslagern oder in Plugin RRZE Elements übernehmen
 */

global $imagelink_defaults;
$imagelink_defaults = array(
			'echo'			=> false, 
			'order'			=> 'ASC', 
			'dots'			=> true, 
			'autoplay'		=> true,
			'adaptiveHeight'	=> true,
			'slides'		=> 4, 
			'class'			=> '', 
			'type'			=> 'slide', 
			'size'			=> 'logo-thumb',
			'navtitle'		=> __('Partnerlogos', 'fau') 
);

global $imagelink_allowedsizes;
$imagelink_allowedsizes = array("logo-thumb", "post-thumbnails", "thumbnail", "page-thumb", "x120", "x240", "x360", "x480");
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
		global $imagelink_defaults;
		global $imagelink_allowedsizes;
		$imagelink_option = array_merge($imagelink_defaults, $atts);
		
		
		if (isset($atts['slides']) && intval($atts['slides']) && $atts['slides']>0) {
			$imagelink_option['slides']  = $atts['slides'];
		} 

		
		$imagelink_option['echo'] = ( isset($atts['echo'] ) ? filter_var( $atts['echo'], FILTER_VALIDATE_BOOLEAN ) : $imagelink_option['echo']  );
		$imagelink_option['dots'] = ( isset($atts['dots'] ) ? filter_var( $atts['dots'], FILTER_VALIDATE_BOOLEAN ) : $imagelink_option['dots']  );
		$imagelink_option['autoplay'] = ( isset($atts['autoplay'] ) ? filter_var( $atts['autoplay'], FILTER_VALIDATE_BOOLEAN ) : $imagelink_option['autoplay']  );

		$imagelink_option['navtitle'] = ( isset($atts['navtitle'] ) ? sanitize_text_field( $atts['navtitle'] ) : $imagelink_option['navtitle']  );
		$imagelink_option['class'] = ( isset($atts['class'] ) ? sanitize_text_field( $atts['class'] ) : '' );
		$imagelink_option['order'] = ( isset($atts['order'] ) ? sanitize_text_field( $atts['order'] ) : $imagelink_defaults['order']  );
		
		if  (isset($atts['size'] ) && (in_array(sanitize_text_field( $atts['size'] ), $imagelink_allowedsizes))) {
			$imagelink_option['size'] = sanitize_text_field( $atts['size'] ); 
		}
		
		if  (isset($atts['type'] ) && (in_array(sanitize_text_field( $atts['type'] ), array('slide', 'list')))) {
			$imagelink_option['type'] = sanitize_text_field( $atts['type'] ); 
		}
		
		$imagelink_option['catid'] = ( isset($atts['catid'] ) ? intval( $atts['catid'] ) : 0 );
		$imagelink_option['cat'] = ( isset($atts['cat'] ) ? esc_attr( $atts['cat'] ) : '' );



		if ( isset($imagelink_option['catid']) && $imagelink_option['catid'] >0) {
			$args = array(
			   'post_type'  => 'imagelink',
			   'nopaging'   => 1,
			   'orderby'    => 'name', 
			   'order'	    => $imagelink_option['order'],
			   'tax_query'  => array(
				array(
				   'taxonomy' => 'imagelinks_category',
				   'field' => 'id',
				   'terms' => $imagelink_option['catid'],
				   )
			   )
			);	 
		} elseif (!empty($imagelink_option['cat'])) {
			 $args = array(
			   'post_type'  => 'imagelink',
			   'nopaging'	  => 1,
			   'orderby'	  => 'name', 
			   'order'	  => $imagelink_option['order'],
			   'tax_query'  => array(
				array(
				   'taxonomy' => 'imagelinks_category',
				   'field' => 'slug', 
				   'terms' => $imagelink_option['cat'],
				   )
			   )
			);	
		} else {
			$args = array(
			   'post_type'	=> 'imagelink',
			   'nopaging'	=> 1,
			   'orderby'	=> 'name', 
			   'order'	=> $imagelink_option['order']
			);
		}



		$imagelist = get_posts($args); 
		$number =0;
		$item_output = $output = $mainclass = '';
		$rand = rand();	    


		foreach($imagelist as $item) {
			
			$currenturl  = get_post_meta( $item->ID, 'fauval_imagelink_url', true );
			$imageid = get_post_thumbnail_id( $item->ID ); 
			
			if ($imageid > 0) {
				$number++;
				$item_output .= '<div class="slick-item';
				if (!empty($imagelink_option['class'])) {
					$item_output .= ' '.$imagelink_option['class'];
				}
				$item_output .= '">';
				if (!empty($currenturl)) {
				    $item_output .= '<a';
				    if (fau_is_url_external($currenturl)) {
					    $item_output .= ' rel="nofollow"';
				    }

				    $item_output .= ' href="'.$currenturl.'">';
				} else {
				     $item_output .= '<span class="image">';
				}
				$alttext = get_the_title($item->ID);
				$alttext = esc_html($alttext);
				if (empty($alttext)) {
					$alttext = __("Zum Webauftritt: ", 'fau').$currenturl;
				}
				$item_output .= fau_get_image_htmlcode($imageid, 'rwd-480-3-2', $alttext);
		//		$item_output .= fau_get_image_htmlcode($imageid, $imagelink_option['size'], $alttext);
				if (!empty($currenturl)) {
				    $item_output .= '</a>';
				} else {
				    $item_output .= '</span>';
				}
				$item_output .= '</div>';
			}
		}

		if ($number>0) {
			$output .= '<nav aria-label="'.$imagelink_option['navtitle'].'" class="imagelink';
			$output .= " ".$imagelink_option['type'];
			
			if ($imagelink_option['dots']) {
				$output .= " dots";
			}
			if (($imagelink_option['adaptiveHeight']) && ($imagelink_option['type'] !== 'list')) {
				$output .= " adaptiveHeight";
			}
			$output .= '"';

			$output .= '>';
			$mainclass .= 'imagelink-container ';
			$mainclass .= $imagelink_option['size'];
			$mainclass .= ' slider-for-'.$rand;
			$output .= '<div class="'.$mainclass.'">';

			$output .= $item_output;
			$output .= '</div>';
			$output .= "</nav>";
			
			if ($imagelink_option['type'] == 'slide') {
				global $slickfunc;
				
				$str_autoplay = 'true';
				if (!$imagelink_option['autoplay']) {
					$str_autoplay = 'false';
				}
				$str_dots = 'true';
				if (!$imagelink_option['dots']) {
					$str_dots = 'false';
				}
				
				$slidesToShow = $imagelink_option['slides'];
				
				$slickfunc .= "$('.slider-for-$rand').slick({ slidesToScroll: 1, focusOnSelect: true";
				
				if ($imagelink_option['adaptiveHeight']) {
					$slickfunc .= ", adaptiveHeight: true";
				}
				
				$slickfunc .= ", slidesToShow: $slidesToShow, dots: $str_dots, autoplay: $str_autoplay";
				$slickfunc .= ", responsive: [{ breakpoint: 768, settings: {arrows: false,slidesToShow: 3 }},{breakpoint: 480,settings: {arrows: false,slidesToShow: 1 }}]";
				$slickfunc .= "});\n";
				
				wp_enqueue_script('fau-js-heroslider');
			}
			if ($imagelink_option['echo'] ) {
				echo $output;
				return;
			} else {	
				return $output;
			}
		}

    }
}
/*-----------------------------------------------------------------------------------*/
/* Add slider script into footer
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'fau_imagelink_addfooter_scripts' ) ) {
	function fau_imagelink_addfooter_scripts() {
		global $slickfunc;
		if (!empty($slickfunc)) {
			echo  "<script type=\"text/javascript\">jQuery(document).ready(function($) {";		
			echo $slickfunc;
			echo "});</script>";
		}
	}
}
add_action( 'wp_footer', 'fau_imagelink_addfooter_scripts');
				

/*-----------------------------------------------------------------------------------*/
/* Shortcode for image slider
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'fau_imagelink_shortcode' ) ) {
    function fau_imagelink_shortcode( $atts ) {
		global $imagelink_defaults;
		$args = shortcode_atts( $imagelink_defaults, $atts, 'imagelink' );

		if (isset($atts['slides']) && intval($atts['slides']) && $atts['slides']>0) {
			$args['slides']  = $atts['slides'];
		} 
		$args['autoplay'] = filter_var( $args['autoplay'], FILTER_VALIDATE_BOOLEAN );
		$args['echo'] = filter_var( $args['echo'], FILTER_VALIDATE_BOOLEAN );
		$args['dots'] = filter_var( $args['dots'], FILTER_VALIDATE_BOOLEAN );
		
		$args['navtitle'] = ( isset($atts['navtitle'] ) ? sanitize_text_field( $atts['navtitle'] ) : $imagelink_defaults['navtitle'] );
		$args['class'] = ( isset($atts['class'] ) ? sanitize_text_field( $atts['class'] ) : '' );
		$args['order'] = ( isset($atts['order'] ) ? sanitize_text_field( $atts['order'] ) : $imagelink_defaults['order'] );
		$args['size'] = ( isset($atts['size'] ) ? sanitize_text_field( $atts['size'] ) : $imagelink_defaults['size'] );
		$args['catid'] = ( isset($atts['catid'] ) ? intval( $atts['catid'] ) : 0 );
		$args['cat'] = ( isset($atts['cat'] ) ? esc_attr( $atts['cat'] ) : '' );
		
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