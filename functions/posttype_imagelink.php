<?php

/*
 * Verwaltung der Bildlinks / Logos
 */

function imagelink_taxonomy() {
	register_taxonomy(
		'imagelinks_category',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
		'imagelink',   		 //post type name
		array(
			'hierarchical' 		=> true,
			'label' 			=> __('Bildlink-Kategorien', 'fau'),  //Display name
			'query_var' 		=> true,
			'rewrite'			=> array(
					'slug' 			=> 'imagelinks', // This controls the base slug that will display before each term
					'with_front' 	=> false // Don't display the category base before
					)
			)
		);
}
add_action( 'init', 'imagelink_taxonomy');

// Register Custom Post Type
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
	/*	'capability_type'     => 'imagelink',
		'capabilities' => array(
		    'edit_post' => 'edit_imagelink',
		    'read_post' => 'read_imagelink',
		    'delete_post' => 'delete_imagelink',
		    'edit_posts' => 'edit_imagelinks',
		    'edit_others_posts' => 'edit_others_imagelinks',
		    'publish_posts' => 'publish_imagelinks',
		    'read_private_posts' => 'read_private_imagelinks',
		    'delete_posts' => 'delete_imagelinks',
		    'delete_private_posts' => 'delete_private_imagelinks',
		    'delete_published_posts' => 'delete_published_imagelinks',
		    'delete_others_posts' => 'delete_others_imagelinks',
		    'edit_private_posts' => 'edit_private_imagelinks',
		    'edit_published_posts' => 'edit_published_imagelinks'
		),
		'map_meta_cap' => true
	 
	 */
	);
	register_post_type( 'imagelink', $args );

}

// Hook into the 'init' action
add_action( 'init', 'imagelink_post_type', 0 );


function imagelink_restrict_manage_posts() {
	global $typenow;

	if( $typenow == "imagelink" ){
		$filters = get_object_taxonomies($typenow);
		
		foreach ($filters as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			wp_dropdown_categories(array(
			'show_option_all' => sprintf(__('Alle %s anzeigen', 'fau'), $tax_obj->label),
			'taxonomy' => $tax_slug,
			'name' => $tax_obj->name,
			'orderby' => 'name',
			'selected' => isset($_GET[$tax_slug]) ? $_GET[$tax_slug] : '',
			'hierarchical' => $tax_obj->hierarchical,
			'show_count' => true,
			'hide_if_empty' => true
		    ));
		}

	}
}
add_action( 'restrict_manage_posts', 'imagelink_restrict_manage_posts' );



function imagelink_post_types_admin_order( $wp_query ) {
	if ((is_admin()) && ($wp_query)) {
	        if (isset($wp_query->query['post_type'])) {
		    $post_type = $wp_query->query['post_type'];
		    if ( $post_type == 'imagelink') {

			    if( ! isset($wp_query->query['orderby']))
			    {
				    $wp_query->set('orderby', 'title');
				    $wp_query->set('order', 'ASC');
			    }

		    }
		}
	}
}
add_filter('pre_get_posts', 'imagelink_post_types_admin_order');



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
   
    /* Old values */
    $desc  = get_post_meta( $object->ID, 'portal_description', true );
    $protocol  = get_post_meta( $object->ID, 'protocol', true );
    $link  = get_post_meta( $object->ID, 'link', true );
	
    if (empty($targeturl) && isset($protocol) && isset($link)) {
	$targeturl = $protocol.$link;
    }
				
    fau_form_url('fau_imagelink_url', $targeturl, __('Webadresse','fau'), '', $placeholder='http://');   
    fau_form_text('fau_imagelink_desc', $desc, __('Kurzbeschreibung','fau'));

    return;

}


add_action( 'add_meta_boxes', 'fau_imagelink_metabox' );






function fau_imagelink_metabox_content_save( $post_id ) {
    global $options;
    if (  'imagelink'!= get_post_type()  ) {
	return;
    }


	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
	return;
	
	
	if ( !isset( $_POST['fau_imagelink_metabox_content_nonce'] ) || !wp_verify_nonce( $_POST['fau_imagelink_metabox_content_nonce'], basename( __FILE__ ) ) )
		return $post_id;



	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
		return;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
		return;
	}
	
    /* Old values */	
    $targeturl = get_post_meta( $post_id, 'fauval_imagelink_url', true );
    $protocol  = get_post_meta( $post_id, 'protocol', true );
    $link  = get_post_meta( $post_id, 'link', true );

    
    $newval = ( isset( $_POST['fau_imagelink_desc'] ) ? sanitize_text_field( $_POST['fau_imagelink_desc'] ) : 0 );
    $oldval =  get_post_meta( $post_id, 'portal_description', true );
	
	if (!empty(trim($newval))) {
	    if (isset($oldval)  && ($oldval != $newval)) {
		update_post_meta( $post_id, 'portal_description', $newval );
	    } else {
		add_post_meta( $post_id, 'portal_description', $newval, true );
	    }
	} elseif ($oldval) {
	    delete_post_meta( $post_id, 'portal_description', $oldval );	
	} 
    
	
    if (empty($targeturl) && isset($protocol) && isset($link)) {
	$targeturl2 = $protocol.$link;
    }
    
    if (filter_var($_POST['fau_imagelink_url'], FILTER_VALIDATE_URL)) {
	$newval = $_POST['fau_imagelink_url'];
    }
    if (!empty($newval)) {
	    if (isset($targeturl)  && ($targeturl != $newval)) {
		update_post_meta( $post_id, 'fauval_imagelink_url', $newval );
	    } else {
		add_post_meta( $post_id, 'fauval_imagelink_url', $newval, true );
	    }
    } else {
	    if ($targeturl) {
		delete_post_meta( $post_id, 'fauval_imagelink_url', $oldval );	
	    }    
    } 
    if (isset($protocol) && isset($link)) {
	delete_post_meta( $post_id, 'protocol' );	
	delete_post_meta( $post_id, 'link' );	
    }
}
add_action( 'save_post', 'fau_imagelink_metabox_content_save' );



function fau_get_imagelinks ( $catid, $echo = true ) {
    global $options;
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
	$output = '';
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
	    
	    
	    $item_output .= get_the_post_thumbnail($item->ID, 'logo-thumb');
	    /*
	     * Falls ich bei den ALT-Tag lieber auf Empty wechseln will, dann diesen Code
	     * aktivieren: 
	     * 
	    $post_thumbnail_id = get_post_thumbnail_id( $item->ID ); 
	    $sliderimage = wp_get_attachment_image_src( $post_thumbnail_id, 'logo-thumb' );
	    $slidersrcset =  wp_get_attachment_image_srcset($post_thumbnail_id, 'logo-thumb');
	    
	    $item_output .= '<img src="'.$sliderimage[0].'" alt="" width="'.$sliderimage[1].'" height="'.$sliderimage[2].'"';
	    if ($slidersrcset) {
		$item_output .= 'srcset="'.$slidersrcset.'"';
	    }
	    $item_output .= '>';
	    */
	    
	    $item_output .= '</a>';
	    $item_output .= '</li>';
   
	    
	    
	}
	if ($number>0) {
	    $output .= '<div class="imagelink_carousel">';
		$output .= '<div class="container">';
		    $output .= '<div class="logos-menu-nav">';
			$output .= '<a id="logos-menu-prev" href="#"><i class="fa fa-chevron-left"></i><span class="screen-reader-text">'. __('Zurück', 'fau') . '</span></a>';
			$output .= '<a id="logos-menu-next" href="#"><i class="fa fa-chevron-right"></i><span class="screen-reader-text">'. __('Weiter', 'fau') . '</span></a>';
		    $output .= '</div>';
		$output .= "</div>\n";
		$output .= '<ul class="logos-menu">';
		    $output .= $item_output;    
		$output .= "</ul>\n";

		$output .= '<div class="container">';
		    $output .= '<div class="row logos-menu-settings">';
			$output .= '<a id="logos-menu-playpause" href="#">'
				. '<span class="play"><i class="fa fa-play"></i>'. __('Abspielen', 'fau') . '</span>'
				. '<span class="pause"><i class="fa fa-pause"></i>'. __('Pause', 'fau') . '</span></a>';
		    $output .= '</div>';
		$output .= '</div>';
	    $output .= "</div>\n";

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