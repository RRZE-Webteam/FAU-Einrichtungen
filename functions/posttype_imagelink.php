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
function fau_imagelink_metabox_content( $object, $box ) { 
    global $defaultoptions;
    global $post;

	
    wp_nonce_field( basename( __FILE__ ), 'fau_imagelink_metabox_content_nonce' ); 

    if ( !current_user_can( 'edit_page', $object->ID) )
	    // Oder sollten wir nach publish_pages  fragen? 
	    // oder nach der Rolle? vgl. http://docs.appthemes.com/tutorials/wordpress-check-user-role-function/ 
	return;

    
    $targeturl = get_post_meta( $object->ID, 'fauval_imagelink_url', true );
    $desc  = get_post_meta( $object->ID, 'portal_description', true );
    
    /* Old - deprecated since 1.7
	   
	    $protocol  = get_post_meta( $object->ID, 'protocol', true );
	    $link  = get_post_meta( $object->ID, 'link', true );

	    if (empty($targeturl) && isset($protocol) && isset($link)) {
		$targeturl = $protocol.$link;
	    }
    */			
    fau_form_url('fau_imagelink_url', $targeturl, __('Webadresse','fau'), '', $placeholder='https://');   
    fau_form_text('fau_imagelink_desc', $desc, __('Kurzbeschreibung','fau'));

    return;

}
add_action( 'add_meta_boxes', 'fau_imagelink_metabox' );



/*-----------------------------------------------------------------------------------*/
/* Save values of metabox
/*-----------------------------------------------------------------------------------*/
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
add_action( 'save_post', 'fau_imagelink_metabox_content_save' );

/*-----------------------------------------------------------------------------------*/
/* Display imagelink slider
/*-----------------------------------------------------------------------------------*/
function fau_imagelink_get( $atts = array()) {
    global $defaultoptions;
    
    $allowedsizes = array("logo-thumb", "page-thumb", "post-thumbnails");
    $slides = ( isset($atts['slides'] ) ? intval( $atts['slides'] ) : 5 );
    $dots =  (( isset($atts['dots'])  && ($atts['dots']==true)) ?  'true'  : 'false' );
    $autoplay = (( isset($atts['autoplay']) && ($atts['autoplay']==true)) ?  'true'  : 'false' );
    $catid = ( isset($atts['catid'] ) ? intval( $atts['catid'] ) : 0 );
    $order = ( isset($atts['order'] ) ? esc_attr( $atts['order'] ) : 'ASC' );
    $size = ( isset($atts['size'] ) ? esc_attr( $atts['size'] ) : 'logo-thumb' );
    $navtitle = sanitize_text_field( $atts['navtitle'] );
    if (!in_array($size, $allowedsizes)) {
	$size = 'logo-thumb';
    }
    // $size = "post-thumbnails";
    
    if ( isset($catid) && $catid >0) {
	$args = array(
	   'post_type'	=> 'imagelink',
	   'nopaging'	=> 1,
	   'orderby'	=> 'name', 
	   'order'	=> $order,
	   'tax_query'	=> array(
		    array(
		       'taxonomy' => 'imagelinks_category',
		       'field' => 'id', // can be slug or id - a CPT-onomy term's ID is the same as its post ID
		       'terms' => $catid,

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
    $item_output = $output = '';
    $rand = rand();	    
   /*
    $output .= 'cat: '.$catid.'<br>';
    $output .= 'order: '.$order.'<br>';
    $output .= 'echo: '.$echo.'<br>';
    $output .= 'navtitle: '.$navtitle.'<br>';
    $output .= 'slides: '.$slides.'<br>';
    $output .= 'size: '.$size.'<br>';
    $output .= 'dots: '.$dots.'<br>';
    $output .= 'autoplay: '.$autoplay.'<br>';
	*/
	
    
    $logowidth = $logoheight = 0;
        

    if ($size == 'post-thumbnails') {
	/* Default Thumb Size
	 'post-thumbnails'
	'default_thumb_width'		=> 300,
	'default_thumb_height'		=> 150,
	'default_thumb_crop'		=> false,
	*/
	$logowidth = $defaultoptions['default_thumb_width'];
	$logoheight = $defaultoptions['default_thumb_height'];
    } elseif ($size == 'logo-thumb') {
	/* Thumb for Image Menus in Content - Name: page-thumb
	    'default_submenuthumb_width'	    => 220,
	    'default_submenuthumb_height'	    => 110,    
	    'default_submenuthumb_crop'	    => false,
	*/
    	$logowidth = $defaultoptions['default_submenuthumb_width'];
	$logoheight = $defaultoptions['default_submenuthumb_height'];
    } else {
	 /* Thumb for Logos (used in carousel) - Name: logo-thumb 
	    'default_logo_carousel_width'	    => 140,
	    'default_logo_carousel_height'	    => 110,
	    'default_logo_carousel_crop'	    => false,   
	*/
	$logowidth = $defaultoptions['default_logo_carousel_width'];
	$logoheight = $defaultoptions['default_logo_carousel_height'];
    }
	

    foreach($imagelist as $item) {
	$number++;
	$currenturl  = get_post_meta( $item->ID, 'fauval_imagelink_url', true );

	$item_output .= '<div class="slick-item">';
	$item_output .= '<a class="'.$size.'" rel="nofollow" href="'.$currenturl.'">';
	$alttext = get_the_title($item->ID);
	$alttext = esc_html($alttext);
	$altattr = 'alt="'.$alttext.'"';

	$post_thumbnail_id = get_post_thumbnail_id( $item->ID ); 
	$sliderimage = wp_get_attachment_image_src( $post_thumbnail_id, $size );

	$item_output .= '<img src="'.fau_esc_url($sliderimage[0]).'" '.$altattr.' width="'.$sliderimage[1].'" height="'.$sliderimage[2].'">';
	$item_output .= '</a>';
	$item_output .= '</div>';
    }

    if ($number>0) {
	$output .= '<nav class="imagelink" aria-label="'.$navtitle. '">';
	$output .= '<div class="slick slider-for-'.$rand.'">';
	$output .= $item_output;
	$output .= '</div>';
	$output .= "</nav>\n";
	$output .= "<script type=\"text/javascript\"> jQuery(document).ready(function($) {";		
	$output .= "$('.slider-for-$rand').slick({ slidesToShow: $slides, slidesToScroll: 1, focusOnSelect: true";
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
	if ($echo==true) {
	    echo $output;
	    return;
	} else {
	    return $output;
	}
    }

}
/*-----------------------------------------------------------------------------------*/
/* Shortcode for image slider
/*-----------------------------------------------------------------------------------*/
function fau_imagelink_shortcode( $atts ) {
    
    $args = shortcode_atts( array(
	'catid'		=> 0,
	'echo'		=> false, 
	'order'		=> 'ASC', 
	'dots'		=> true, 
	'slides'	    	=> 5, 
	'autoplay'	=> true, 
	'size'		=> 'page-thumb',
	'navtitle'	=> __('Partnerlogos', 'fau') 
	), $atts, 'imagelink' );
    
    return fau_imagelink_get($args);   
}
add_shortcode('imagelink', 'fau_imagelink_shortcode' );

/*-----------------------------------------------------------------------------------*/
/* Display imagelink slider (OLD)
/*-----------------------------------------------------------------------------------*/
function fau_get_imagelinks ( $catid, $echo = true ) {
    global $usejslibs;
    
    if ( isset($catid) && $catid >0) {
		
	
	 $args = array(
		    'post_type'	=> 'imagelink',
		    'nopaging' => 1,
		    'orderby' => 'name', 
		    'order' => 'ASC',
                    'tax_query' => array(
			     array(
				'taxonomy' => 'imagelinks_category',
				'field' => 'id', // can be slug or id - a CPT-onomy term's ID is the same as its post ID
				'terms' => $catid,
			    
                            )
                    )
        );
	$output = $alttext = '';
	$imagelist = get_posts($args); 
	$item_output = '';
	$number =0;
        foreach($imagelist as $item) {
	    $number++;
	    $currenturl  = get_post_meta( $item->ID, 'fauval_imagelink_url', true );
	    if (!$currenturl) {
		    $protocol  = get_post_meta( $item->ID, 'protocol', true );
		    $link  = get_post_meta( $item->ID, 'link', true );
		    $currenturl = $protocol.$link;
	    }
	    $item_output .= '<li>';
	    $item_output .= '<a rel="nofollow" href="'.$currenturl.'">';
	    $alttext = get_the_title($item->ID);
	    $alttext = esc_html($alttext);
	    $altattr = 'alt="'.$alttext.'"';

	    $post_thumbnail_id = get_post_thumbnail_id( $item->ID ); 
	    $sliderimage = wp_get_attachment_image_src( $post_thumbnail_id, 'logo-thumb' );
	    
	    $item_output .= '<img src="'.$sliderimage[0].'" '.$altattr.' width="'.$sliderimage[1].'" height="'.$sliderimage[2].'">';
	    $item_output .= '</a>';
	    $item_output .= '</li>';

	}
	
	

	
	if ($number>0) {
	    $output .= '<nav class="imagelink_carousel" aria-label="'. __('Partnerlogos', 'fau') . '">';
		$output .= '<div class="container">';
		    $output .= '<div class="logos-menu-nav">';
			$output .= '<a id="logos-menu-prev" href="#"><em class="fa fa-chevron-left"></em><span class="screen-reader-text">'. __('Zurück', 'fau') . '</span></a>';
			$output .= '<a id="logos-menu-next" href="#"><em class="fa fa-chevron-right"></em><span class="screen-reader-text">'. __('Weiter', 'fau') . '</span></a>';
		    $output .= '</div>';
		$output .= "</div>\n";
		$output .= '<ul class="logos-menu">';
		    $output .= $item_output;    
		$output .= "</ul>\n";

		$output .= '<div class="container">';
		    $output .= '<div class="row logos-menu-settings">';
			$output .= '<a id="logos-menu-playpause" href="#">'
				. '<span class="play"><em class="fa fa-play"></em>'. __('Abspielen', 'fau') . '</span>'
				. '<span class="pause"><em class="fa fa-pause"></em>'. __('Pause', 'fau') . '</span></a>';
		    $output .= '</div>';
		$output .= '</div>';
	    $output .= "</nav>\n";

	    $usejslibs['caroufredsel'] = true;
	} 
	if ($echo==true) {
	    echo $output;
	    return;
	} else {
	    return $output;
	}
	
	
	
	
    } else {
	return;
    }
}
/*-----------------------------------------------------------------------------------*/
/* EOF
/*-----------------------------------------------------------------------------------*/