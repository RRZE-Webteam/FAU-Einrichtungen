<?php

global $options;

// Register Custom Post Type
if  ( (isset($options['advanced_activate_synonyms'])) && ($options['advanced_activate_synonyms'] == true) && (! function_exists( 'synonym_post_type' ) )) :

function synonym_post_type() {	
	
	$labels = array(
		'name'                => _x( 'Synonyme', 'Post Type General Name', 'fau' ),
		'singular_name'       => _x( 'Synonym', 'Post Type Singular Name', 'fau' ),
		'menu_name'           => __( 'Synonyme', 'fau' ),
		'parent_item_colon'   => __( 'Übergeordnete Synonyme', 'fau' ),
		'all_items'           => __( 'Alle Synonyme', 'fau' ),
		'view_item'           => __( 'Synonyme ansehen', 'fau' ),
		'add_new_item'        => __( 'Synonym hinzufügen', 'fau' ),
		'add_new'             => __( 'Neues Synonym', 'fau' ),
		'edit_item'           => __( 'Synonym bearbeiten', 'fau' ),
		'update_item'         => __( 'Synonym aktualisieren', 'fau' ),
		'search_items'        => __( 'Synonym suchen', 'fau' ),
		'not_found'           => __( 'Keine Synonyme gefunden', 'fau' ),
		'not_found_in_trash'  => __( 'Keine Synonyme im Papierkorb gefunden', 'fau' ),
	);
	$rewrite = array(
		'slug'                => 'synonym',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
	);
	$args = array(
		'label'               => __( 'synonym', 'fau' ),
		'description'         => __( 'Synonym Informationen', 'fau' ),
		'labels'              => $labels,
		'supports'            => array( 'title' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'menu_icon'		=> 'dashicons-translation',

		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'query_var'           => 'synonym',
		'rewrite'             => $rewrite,
		
	);
	register_post_type( 'synonym', $args );

}
add_action( 'init', 'synonym_post_type', 0 );

endif;



if ( ! function_exists( 'synonym_restrict_manage_posts' ) ) :
function synonym_restrict_manage_posts() {
	global $typenow;

	if( $typenow == "synonym" ){
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
endif;
if ( (isset($options['advanced_activate_synonyms'])) && ($options['advanced_activate_synonyms'] == true)) {
    add_action( 'restrict_manage_posts', 'synonym_restrict_manage_posts' );
}

if ( ! function_exists( 'synonym_post_types_admin_order' ) ) :
function synonym_post_types_admin_order( $wp_query ) {
	if (is_admin()) {

		$post_type = $wp_query->query['post_type'];

		if ( $post_type == 'synonym') {

			if( ! isset($wp_query->query['orderby']))
			{
				$wp_query->set('orderby', 'title');
				$wp_query->set('order', 'ASC');
			}

		}
	}
}
add_filter('pre_get_posts', 'synonym_post_types_admin_order');

endif;



if ( ! function_exists( 'fau_synonym_metabox' ) ) :
function fau_synonym_metabox() {
    add_meta_box(
        'fau_synonym_metabox',
        __( 'Eigenschaften', 'fau' ),
        'fau_synonym_metabox_content',
        'synonym',
        'normal',
        'high'
    );
}
endif;

if  ( (isset($options['advanced_activate_synonyms'])) && ($options['advanced_activate_synonyms'] == true) &&  ( ! function_exists( 'fau_synonym_metabox_content' ) )):
function fau_synonym_metabox_content( $object, $box ) { 
    global $defaultoptions;
    global $post;

	
    wp_nonce_field( basename( __FILE__ ), 'fau_synonym_metabox_content_nonce' ); 

    if ( !current_user_can( 'edit_post', $object->ID) )
	    // Oder sollten wir nach publish_pages  fragen? 
	    // oder nach der Rolle? vgl. http://docs.appthemes.com/tutorials/wordpress-check-user-role-function/ 
	return;

    
   
    $desc  = get_post_meta( $object->ID, 'synonym', true );		
    fau_form_text('fau_synonym', $desc, __('Ausgeschriebene Form','fau'), __('Geben Sie hier die lange, ausgeschriebene Form des Synonyms ein. Mit diesem Text wird dann im späteren Gebrauch der verwendete Shortcode ersetzt.','fau'));
    
    if ($post->ID >0) {
	$helpuse = __('<p>Einbindung in Seiten und Beiträgen via: </p>','fau');
	$helpuse .= '<pre> [synonym id="'.$post->ID.'"] </pre>';
	if ($post->post_name) {
	    $helpuse .= ' oder <br> <pre> [synonym slug="'.$post->post_name.'"] </pre>';
	}
	echo $helpuse;
    }

    
    return;

}
add_action( 'add_meta_boxes', 'fau_synonym_metabox' );

endif;





if  ( (isset($options['advanced_activate_synonyms'])) && ($options['advanced_activate_synonyms'] == true) &&  ( ! function_exists( 'fau_synonym_metabox_content_save' ) )):
function fau_synonym_metabox_content_save( $post_id ) {
    global $options;
    if (  'synonym'!= get_post_type()  ) {
	return;
    }


    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;


    if ( !isset( $_POST['fau_synonym_metabox_content_nonce'] ) || !wp_verify_nonce( $_POST['fau_synonym_metabox_content_nonce'], basename( __FILE__ ) ) )
	    return $post_id;


    if ( !current_user_can( 'edit_post', $post_id ) )
    return;

    
    $newval = ( isset( $_POST['fau_synonym'] ) ? sanitize_text_field( $_POST['fau_synonym'] ) : 0 );
    $oldval =  get_post_meta( $post_id, 'synonym', true );
	
    if (!empty(trim($newval))) {
	if (isset($oldval)  && ($oldval != $newval)) {
	    update_post_meta( $post_id, 'synonym', $newval );
	} else {
	    add_post_meta( $post_id, 'synonym', $newval, true );
	}
    } elseif ($oldval) {
	delete_post_meta( $post_id, 'synonym', $oldval );	
    } 
    
	
}
add_action( 'save_post', 'fau_synonym_metabox_content_save' );

endif;



 
if  ( (isset($options['advanced_activate_synonyms'])) && ($options['advanced_activate_synonyms'] == true) && ( ! function_exists( 'fau_synonym' ) )) :  
    function fau_synonym( $atts, $content = null) {
            extract(shortcode_atts(array(
                    "slug" => 'slug',
		    "id" => 'id'
                    ), $atts));
	     $return = '';
	    if (isset($id) && intval($id) && $id>0) {
		// $post = get_posts(array('id' => $id, 'post_type' => 'synonym', 'post_status' => 'publish'));	
		 $return = get_post_meta( $id, 'synonym', true );
	    } else {
		$post = get_posts(array('name' => $slug, 'post_type' => 'synonym', 'post_status' => 'publish', 'numberposts' => 1));
		 if ($post)  {
			$id = $post[0]->ID;		
			$return = get_post_meta( $id, 'synonym', true );
		 }
	    }
	    return $return;
           
    }
 add_shortcode('synonym', 'fau_synonym' );
endif;

 if  ( (isset($options['advanced_activate_synonyms'])) && ($options['advanced_activate_synonyms'] == true) && ( ! function_exists( 'fau_get_synonym' ) )):  
    function fau_get_synonym($id =0) {

	    if (isset($id) && intval($id) && $id>0) {
		// $post = get_posts(array('id' => $id, 'post_type' => 'synonym', 'post_status' => 'publish'));	
		
		$title = get_the_title($id);
		$synonym = get_post_meta( $id, 'synonym', true );
		
		$return = '<div class="synonym">';
		$return .= '<h2 class="small">'.$title."</h2>\n";
		$return .= '<p>'.$synonym."</p>\n";
		$return .= "</div>\n";
		
	    } else {
		$posts = get_posts(array('post_type' => 'synonym', 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC', 'numberposts' => -1));
		 if ($posts)  {
		     $return = '<table class="synonym">';
		     foreach($posts as $post) {
			$synonym = get_post_meta( $post->ID, 'synonym', true );
			$return .= '<tr>';
			$return .= '<th>'.get_the_title($post->ID).'</th>';
			$return .= '<td>'.$synonym.'</td>';
			$return .= "</tr>\n";			
		     }
		    $return .= "</table>\n";
			
			
			
		 }
	    }
	    return $return;
           
    }
endif;

if  ( (isset($options['advanced_activate_synonyms'])) && ($options['advanced_activate_synonyms'] == true) && ( ! function_exists( 'fau_synonym_rte_add_buttons' ) )):
    function fau_synonym_rte_add_buttons( $plugin_array ) {
        $plugin_array['synonymrteshortcodes'] = get_template_directory_uri().'/js/tinymce-synonym.js';
        return $plugin_array;
    }
    add_filter( 'mce_external_plugins','fau_synonym_rte_add_buttons');

endif;
