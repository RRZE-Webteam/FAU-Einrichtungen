<?php
/**
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.1
 */

load_theme_textdomain( 'fau', get_template_directory() . '/languages' );
require_once( get_template_directory() . '/functions/constants.php' );
$options = fau_initoptions();
require_once( get_template_directory() . '/functions/helper-functions.php' );
require_once( get_template_directory() . '/functions/theme-options.php' );     
require_once( get_template_directory() . '/functions/bootstrap.php');
require_once( get_template_directory() . '/functions/shortcodes.php');
require_once( get_template_directory() . '/functions/plugin-support.php' );
require_once( get_template_directory() . '/functions/menu.php');
require_once( get_template_directory() . '/functions/custom-fields.php' );
require_once( get_template_directory() . '/functions/posttype_imagelink.php' );
require_once( get_template_directory() . '/functions/posttype_ad.php' );
require_once( get_template_directory() . '/functions/widgets.php' );
require_once( get_template_directory() . '/functions/posttype-synonym.php');
require_once( get_template_directory() . '/functions/posttype-glossary.php');

function fau_setup() {
	global $options;
	

	if ( ! isset( $content_width ) ) $content_width = $options['content-width'];
	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css' ) );

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Switches default core markup for search form, comment form, and comments
	// to output valid HTML5.
//	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
	
	/*
	 * This theme supports all available post formats by default.
	 * See http://codex.wordpress.org/Post_Formats
	 */
//	add_theme_support( 'post-formats', array(
//		'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'
//	) );
	add_theme_support('title-tag');
	
	/*
	if ( ! function_exists( '_wp_render_title_tag' ) ) :
	    function theme_slug_render_title() {
	?>
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<?php
	    }
	    add_action( 'wp_head', 'theme_slug_render_title' );
	endif;
	 */
	
	
	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'meta', __( 'Meta-Navigation oben', 'fau' ) );
	register_nav_menu( 'meta-footer', __( 'Meta-Navigation unten', 'fau' ) );
	register_nav_menu( 'main-menu', __( 'Haupt-Navigation', 'fau' ) );
	

	register_nav_menu( 'quicklinks-3', __( 'Startseite Fakultät: Bühne Spalte 1', 'fau' ) );
	register_nav_menu( 'quicklinks-4', __( 'Startseite Fakultät: Bühne Spalte 2', 'fau' ) );
	
	register_nav_menu( 'error-1', __( 'Fehler- und Suchseite: Vorschlagmenu Spalte 1', 'fau' ) );
	register_nav_menu( 'error-2', __( 'Fehler- und Suchseite: Vorschlagmenu Spalte 2', 'fau' ) );
	register_nav_menu( 'error-3', __( 'Fehler- und Suchseite: Vorschlagmenu Spalte 3', 'fau' ) );
	register_nav_menu( 'error-4', __( 'Fehler- und Suchseite: Vorschlagmenu Spalte 4', 'fau' ) );
	
	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( $options['default_thumb_width'], $options['default_thumb_height'], $options['default_thumb_crop'] ); // 300:150:false
	
	/* Image Sizes for Slider, Name: hero */
	add_image_size( 'hero', $options['slider-image-width'], $options['slider-image-height'], $options['slider-image-crop']);	// 1260:350
	
	/* Banner fuer Startseiten */
	add_image_size( 'herobanner', $options['default_startseite-bannerbild-image_width'], $options['default_startseite-bannerbild-image_height'], $options['default_startseite-bannerbild-image_crop']);	// 1260:182
    
	
	/* Thumb for Main menu - Name: portalmenu-thumb */
	add_image_size( 'portalmenu-thumb', $options['default_mainmenuthumb_width'], $options['default_mainmenuthumb_height'], $options['default_mainmenuthumb_crop']);	// 370, 185, false
 
	
	/* Thumb for Posts in Lists - Name: post-thumb */
	add_image_size( 'post-thumb', $options['default_postthumb_width'], $options['default_postthumb_height'], $options['default_postthumb_crop']); // 3:2
	
	/* Thumb of Topevent in Sidebar - Name: topevent-thumb */
	add_image_size( 'topevent-thumb', $options['default_topevent_thumb_width'], $options['default_topevent_thumb_height'], $options['default_topevent_thumb_crop']); 
	
	/* Thumb for Image Menus in Content - Name: page-thumb */
	add_image_size( 'page-thumb', $options['default_submenuthumb_width'], $options['default_submenuthumb_height'], true); // 220:110
	
	/* Thumb for Posts, displayed in post/page single display - Name: post */
	add_image_size( 'post', $options['default_post_width'], $options['default_post_height'], $options['default_post_crop']);
	
	/* Thumb for person-type; small for sidebar - Name: person-thumb */
	add_image_size( 'person-thumb', $options['default_person_thumb_width' ], $options['default_person_thumb_height'], $options['default_person_thumb_crop'	]); // 60, 80, true
	
        /* Thumb for person-type; small for content - Name: person-thumb-bigger */
	add_image_size( 'person-thumb-bigger', $options['default_person_thumb_bigger_width'], $options[ 'default_person_thumb_bigger_height'], $options['default_person_thumb_bigger_crop']); // 90,120,true

	 /* Thumb for person-type; big for content - Name: person-thumb-page */
	add_image_size( 'person-thumb-page', $options['default_person_thumb_page_width'], $options[ 'default_person_thumb_page_height'], $options['default_person_thumb_page_crop']); // 200,300,true

	
	/* Thumb for Logos (used in carousel) - Name: logo-thumb */
	add_image_size( 'logo-thumb', $options['default_logo_carousel_width'], $options['default_logo_carousel_height'], $options['default_logo_carousel_crop']);

	/* 
	 * Größen für Bildergalerien: 
	 */
	/* Images for gallerys - Name: gallery-full */
	add_image_size( 'gallery-full', $options['default_gallery_full_width'], $options['default_gallery_full_height'], $options['default_gallery_full_crop']); // 940, 470, false
	//
	// Wird bei Default-Galerien verwendet als ANzeige des großen Bildes.
	add_image_size( 'gallery-thumb', $options['default_gallery_thumb_width'], $options['default_gallery_thumb_height'], $options['default_gallery_thumb_crop']); // 120, 80, true

	/* Grid-Thumbs for gallerys - Name: gallery-grid */
	add_image_size( 'gallery-grid', $options['default_gallery_grid_width'], $options['default_gallery_grid_height'], $options['default_gallery_grid_crop']); // 145, 120, false
	
	/* 2 column Imagelists for gallerys - Name: image-2-col */
	add_image_size( 'image-2-col', $options['default_gallery_grid2col_width'], $options['default_gallery_grid2col_height'], $options['default_gallery_grid2col_crop']); // 300, 200, true
	
	/* 4 column Imagelists for gallerys - Name: image-4-col */
	add_image_size( 'image-4-col', $options['default_gallery_grid4col_width'], $options['default_gallery_grid4col_height'], $options['default_gallery_grid4col_crop']);	// 140, 70, true


	
	
	
	/* Remove something out of the head */
	remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
	remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed	
	remove_action( 'wp_head', 'post_comments_feed_link ', 2 ); // Display the links to the general feeds: Post and Comment Feed
	remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
	remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
	remove_action( 'wp_head', 'index_rel_link' ); // index link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
	remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
	//remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0);
	

}
add_action( 'after_setup_theme', 'fau_setup' );

function fau_initoptions() {
   global $defaultoptions;
    
    $oldoptions = get_option('fau_theme_options');
    if (isset($oldoptions) && (is_array($oldoptions))) {
        $newoptions = array_merge($defaultoptions,$oldoptions);	  
    } else {
        $newoptions = $defaultoptions;
    }    
    return $newoptions;
}



/**
 * Enqueues scripts and styles for front end.
 */
function fau_register_scripts() {
    global $options;
    wp_register_script( 'fau-scripts', get_fau_template_uri() . '/js/scripts.min.js', array('jquery'), $options['js-version'], true );
    wp_register_script( 'fau-libs-plugins', get_fau_template_uri() . '/js/libs/plugins.min.js', array('jquery'), $options['js-version'], true );	

    wp_register_script( 'fau-libs-jquery-flexslider', get_fau_template_uri() . '/js/libs/jquery.flexslider.js', array('jquery'), $options['js-version'], true );
	// Flexslider für Startseite und für Galerien.  
    wp_register_script( 'fau-libs-jquery-hoverintent', get_fau_template_uri() . '/js/libs/jquery.hoverintent.js', array(), $options['js-version'], true );
//	wp_register_script( 'fau-libs-jquery-fluidbox', get_fau_template_uri() . '/js/libs/jquery.fluidbox.js', array(), $options['js-version'], true );
	// wird nirgends verwendet
    wp_register_script( 'fau-libs-jquery-fancybox', get_fau_template_uri() . '/js/libs/jquery.fancybox.js', array('jquery'), $options['js-version'], true );  
	// Fuer bessere Lightboxen
    wp_register_script( 'fau-libs-jquery-caroufredsel', get_fau_template_uri() . '/js/libs/jquery.caroufredsel.js', array('jquery'), $options['js-version'], true );
    wp_register_script( 'fau-js-caroufredsel', get_fau_template_uri() . '/js/usecaroufredsel.min.js', array('jquery','fau-libs-jquery-caroufredsel'), $options['js-version'], true );
	// Slidende Logos
    
}
add_action('init', 'fau_register_scripts');

function fau_custom_init() {
	/* Keine verwirrende Abfrage nach Kommentaren im Page-Editor */
	remove_post_type_support( 'page', 'comments' );
}
add_action( 'init', 'fau_custom_init' );
	

function fau_basescripts_styles() {
    global $options;
    global $usejslibs;
    wp_enqueue_style( 'fau-style', get_stylesheet_uri(), array(), $options['js-version'] );	
    wp_enqueue_script( 'fau-scripts');
    wp_enqueue_script( 'fau-libs-plugins' );	

    wp_enqueue_script('fau-libs-jquery-hoverintent');
	// wird für die Navigationen mit <nav> verwendet

     // wp_enqueue_script('fau-libs-jquery-fluidbox');
	// macht eine ALternative zu lightbox. http://terrymun.github.io/Fluidbox/ 
	// Wird nicht verwendet?

    wp_enqueue_script('fau-libs-jquery-fancybox');
	// wird für Bilder verwendet, die mit Lightbox vergrößert werden,
	//  dazu muss bei dem Bild eine Klasse .lightbox im Link gesetzt
	//   werden: <a class="lightbox" ..>
}
add_action( 'wp_enqueue_scripts', 'fau_basescripts_styles' );


/*
 * Scripts, die nur abhaengig von Funktionen, die auf den content wirken, im Footer aktiviert werden.
 */
function fau_enqueuefootercripts() {
    global $options;
    global $usejslibs;
   
    
     if ((isset($usejslibs['flexslider']) && ($usejslibs['flexslider'] == true))) {
	 // wird bei Startseite Slider und auch bei gallerien verwendet
	wp_enqueue_script('fau-libs-jquery-flexslider');	     
     }	 

     if ((isset($usejslibs['caroufredsel']) && ($usejslibs['caroufredsel'] == true))) {
	// wird bei Logo-Menus verwendet
	wp_enqueue_script('fau-libs-jquery-caroufredsel');
	wp_enqueue_script('fau-js-caroufredsel');
    }
}

add_action( 'wp_footer', 'fau_enqueuefootercripts' );

/* 
 * Scripts und CSS fuer Adminbereich 
 */
function fau_admin_header_style() {
    wp_register_style( 'themeadminstyle', get_fau_template_uri().'/css/admin.css' );	   
    wp_enqueue_style( 'themeadminstyle' );	
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-datepicker');
    wp_register_script('themeadminscripts', get_fau_template_uri().'/js/admin.js', array('jquery'));    
    wp_enqueue_script('themeadminscripts');	   
}
add_action( 'admin_enqueue_scripts', 'fau_admin_header_style' );


function fau_addmetatags() {
    global $options;

    $output = "";
    $output .= '<meta http-equiv="Content-Type" content="text/html; charset='.get_bloginfo('charset').'" />'."\n";
  //  $output .= '<!--[if IE]> <meta http-equiv="X-UA-Compatible" content="IE=9"> <![endif]-->'."\n";
    $output .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">'."\n";    
    
    // $output .= '<meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">'."\n";    

    $output .= fau_get_rel_alternate();
            
    if ((isset( $options['google-site-verification'] )) && ( strlen(trim($options['google-site-verification']))>1 )) {
        $output .= '<meta name="google-site-verification" content="'.$options['google-site-verification'].'">'."\n";
    }
	
    if ((isset($options['favicon-file'])) && ($options['favicon-file_id']>0 )) {	 
        $output .=  '<link rel="shortcut icon" href="'.$options['favicon-file'].'">'."\n";
    } else {
        $output .=  '<link rel="apple-touch-icon" href="'.get_fau_template_uri().'/img/apple-touch-icon.png">'."\n";
        $output .=  '<link rel="shortcut icon" href="'.get_fau_template_uri().'/img/favicon.ico">'."\n";
    }
    echo $output;
}

add_action('wp_head', 'fau_addmetatags',1);



/**
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since FAU 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
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


/**
 * Resets the Excerpt More
 */

function fau_excerpt_more( $more ) {
    global $options;
    return $options['default_excerpt_morestring'];
}
add_filter('excerpt_more', 'fau_excerpt_more');

/**
 * Resets the Excerpt More
 */
function fau_excerpt_length( $length ) {
    global $options;
    return $options['default_excerpt_length'];
}
add_filter( 'excerpt_length', 'fau_excerpt_length', 999 );



/* Header Setup */
function fau_custom_header_setup() {
    global $default_header_logos;
    global $options;
	$args = array(
	    'default-image'          => $options['default_logo_src'],
	    'height'                 => $options['default_logo_height'],
	    'width'                  => $options['default_logo_width'],
	    'admin-head-callback'    => 'fau_admin_header_style',
	);
	add_theme_support( 'custom-header', $args );

	register_default_headers( $default_header_logos );
}
add_action( 'after_setup_theme', 'fau_custom_header_setup' );




/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since FAU 1.0
 */
function fau_sidebars_init() {

	register_sidebar( array(
		'name' => __( 'News Sidebar', 'fau' ),
		'id' => 'news-sidebar',
		'description' => __( 'Sidebar auf der News-Kategorieseite', 'fau' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h2 class="small">',
		'after_title' => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Suche Sidebar', 'fau' ),
		'id' => 'search-sidebar',
		'description' => __( 'Sidebar auf der Such-Ergebnisseite links', 'fau' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h2 class="small">',
		'after_title' => '</h2>',
	) );
	register_sidebar( array(
		'name' => __( 'Social Media Infobereich (Footer)', 'fau' ),
		'id' => 'startpage-socialmediainfo',
		'description' => __( 'Widgetbereich neben den Social Media Icons im Footer der Startseite.', 'fau' ),
		'before_widget' => '<div class="span3">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="small">',
		'after_title' => '</h2>',
	) );
	
    // Wenn CMS-Workflow vorhanden und aktiviert ist
	if (is_workflow_translation_active()) {
	    register_sidebar( array(
		    'name' => __( 'Sprachwechsler', 'fau' ),
		    'id' => 'language-switcher',
		    'description' => __( 'Sprachwechsler im Header der Seite', 'fau' ),
		    'before_widget' => '',
		    'after_widget' => '',
		    'before_title' => '',
		    'after_title' => '',
	    ) );
	}
	
}
add_action( 'widgets_init', 'fau_sidebars_init' );




add_filter( 'widget_text', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'widget_text', array( $wp_embed, 'autoembed'), 8 );



function add_video_embed_note($html, $url, $attr) {
	return '<div class="oembed">'.$html.'</div>';
}
add_filter('embed_oembed_html', 'add_video_embed_note', 10, 3);



function fau_protected_attribute ($classes, $item) {
	if($item->post_password != '')
	{
		$classes[] = 'protected-page';
	}
	return $classes;
}
add_filter('page_css_class', 'fau_protected_attribute', 10, 3);


function custom_error_pages()
{
    global $wp_query;
 
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 403)
    {
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
 
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 401)
    {
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
 
function custom_error_title($title='',$sep='')
{
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 403)
        return "Forbidden ".$sep." ".get_bloginfo('name');
 
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 401)
        return "Unauthorized ".$sep." ".get_bloginfo('name');
}
 
function custom_error_class($classes)
{
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 403)
    {
        $classes[]="error403";
        return $classes;
    }
 
    if(isset($_REQUEST['status']) && $_REQUEST['status'] == 401)
    {
        $classes[]="error401";
        return $classes;
    }
}
 
add_action('wp','custom_error_pages');



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

/*
 * Make URLs relative; Several functions
 */
function fau_relativeurl($content){
        return preg_replace_callback('/<a[^>]+/', 'fau_relativeurl_callback', $content);
}
function fau_relativeurl_callback($matches) {
        $link = $matches[0];
        $site_link =  wp_make_link_relative(home_url());  
        $link = preg_replace("%href=\"$site_link%i", 'href="', $link);                 
        return $link;
    }
 add_filter('the_content', 'fau_relativeurl');
 
 function fau_relativeimgurl($content){
        return preg_replace_callback('/<img[^>]+/', 'fau_relativeimgurl_callback', $content);
}
function fau_relativeimgurl_callback($matches) {
        $link = $matches[0];
        $site_link =  wp_make_link_relative(home_url());  
        $link = preg_replace("%src=\"$site_link%i", 'src="', $link);                 
        return $link;
    }
 add_filter('the_content', 'fau_relativeimgurl');
 
 /*
  * Replaces esc_url, but also makes URL relative
  */
 function fau_esc_url( $url) {
     if (!isset($url)) {
	 $url = home_url("/");
     }
     return wp_make_link_relative(esc_url($url));
 }
 
 function get_fau_template_uri () {
     return wp_make_link_relative(get_template_directory_uri());
 }
 function fau_get_template_uri () {
     return wp_make_link_relative(get_template_directory_uri());
 } 

add_action('template_redirect', 'rw_relative_urls');
function rw_relative_urls() {
    // Don't do anything if:
    // - In feed
    // - In sitemap by WordPress SEO plugin
    if (is_admin() || is_feed() || get_query_var('sitemap')) {
        return;
    }
    $filters = array(
    //    'post_link',
        'post_type_link',
        'page_link',
        'attachment_link',
        'get_shortlink',
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

function fau_make_link_relative($url) {
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
    return $url; 
}

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
	    $thislist .= '<a href="'.$entry['content'].'">';
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

function fau_get_toplinks() {
    global $options;
    global $default_link_liste;
    
    $uselist =  $default_link_liste['meta'];
    $result = '';
    

    if (isset($uselist['_title'])) {
	$result .= '<h3>'.$uselist['_title'].'</h3>';	
	$result .= "\n";
    }
    
	// website_type: 0 = Fakultaetsportal; 1 = Lehrstuehle, Einrichtungen, etc unter Fakultaet; 2 = Sonstige
	    
    if ($options['website_type']==0) {
	$options['default_display_fauhomelink'] = true;
	$options['default_display_facultyhomelink'] = false;
	$options['fauhome_useimg'] = false;
  /*  } elseif ($options['website_type']==1) {
	$options['default_display_fauhomelink'] = true;
	$options['default_display_facultyhomelink'] = true;	
	$options['fauhome_useimg'] = true; */
    } else {
	$options['default_display_fauhomelink'] = true;
	$options['default_display_facultyhomelink'] = false;
	$options['fauhome_useimg'] = true;
    }
    
    
    $thislist = '';
    if (($options['default_display_fauhomelink']==true) && isset($options['fauhome_url'])) {
	$thislist .= '<li class="fauhome">';
	$thislist .= '<a href="'.$options['fauhome_url'].'">';
	    			
	if ($options['fauhome_useimg']) {
	    $thislist .= '<img src="'.fau_esc_url($options['fauhome_imgsrc']).'" alt="'.$options['fauhome_title'].'">'; 
	} else {
	    $thislist .= $options['fauhome_linktext']; 
	}	
	$thislist .= '</a>';
	$thislist .= '</li>'."\n";	
    }
    if (($options['default_display_facultyhomelink']==true) && isset($options['facultyhome_url'])) {
	$thislist .= '<li class="facultyhome">';
	$thislist .= '<a href="'.$options['facultyhome_url'].'">';
	$thislist .= $options['facultyhome_title']; 
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
		    $thislist .= '<li'.$class_names.'><a href="' . $url . '">' . $title . '</a></li>';
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
	       $thislist .= '<a href="'.$entry['content'].'">';
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



function fau_main_menu_fallback() {
    global $options;
    $output = '';
    $some_pages = get_pages(array('parent' => 0, 'number' => $options['default_mainmenu_number'], 'hierarchical' => 0));
    if($some_pages) {
        foreach($some_pages as $page) {
            $output .= sprintf('<li class="menu-item level1"><a href="%1$s">%2$s</a></li>', get_permalink($page->ID), $page->post_title);
        }
        
        $output = sprintf('<ul role="navigation" aria-label="%1$s" id="nav">%2$s</ul>', __('Navigation', 'fau'), $output);
    }   
    return $output;
}



function fau_custom_excerpt($id = 0, $length = 0, $withp = true, $class = '', $withmore = false, $morestr = '', $continuenextline=false) {
  global $options;
    
    if ($length==0) {
	$length = $options['default_excerpt_length'];
    }
    
    if (empty($morestr)) {
	$morestr = $options['default_excerpt_morestring'];
    }
    
    $excerpt = get_post_field('post_excerpt',$id);
 
    if (mb_strlen(trim($excerpt))<5) {
	$excerpt = get_post_field('post_content',$id);
    }

    $excerpt = preg_replace('/\s+(https?:\/\/www\.youtube[\/a-z0-9\.\-\?&;=_]+)/i','',$excerpt);
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt, $options['custom_excerpt_allowtags']); 

  
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
	if (isset($class)) {
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

/**
 * CMS-Workflow Plugin
 */
function is_workflow_translation_active() {
    global $cms_workflow;
    if ((class_exists('Workflow_Translation')) && (isset($cms_workflow->translation) && $cms_workflow->translation->module->options->activated)) {
        return true;
    }
    return false;
}

function fau_get_rel_alternate() {
    if ((class_exists('Workflow_Translation')) && (function_exists('get_rel_alternate')) && (is_workflow_translation_active())) {
        return Workflow_Translation::get_rel_alternate();
    } else {
        return '';
    }
}



/* Refuse spam-comments on media */
function filter_media_comment_status( $open, $post_id ) {
	$post = get_post( $post_id );
	if( $post->post_type == 'attachment' ) {
		return false;
	}
	return $open;
}
add_filter( 'comments_open', 'filter_media_comment_status', 10 , 2 );





/* Newsseiten */
function fau_display_news_teaser($id = 0, $withdate = false) {
    if ($id ==0) return;   
    global $options;
    
    $post = get_post($id);
    $output = '';
    if ($post) {
	$output .= '<article class="news-item">';
	
	$link = get_post_meta( $post->ID, 'external_link', true );
	$external = 0;
	if (isset($link) && (filter_var($link, FILTER_VALIDATE_URL))) {
	    $external = 1;
	} else {
	    $link = get_permalink($post->ID);
	}
	
	$output .= "\t<h2>";  
	$output .= '<a ';
	if ($external==1) {
	    $output .= 'class="ext-link" ';
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
	    $output .= '<span class="news-meta-date"> '.get_the_date('',$post->ID)."</span>\n";
	    $output .= '</div>'."\n";
	}

	
	$output .= "\t".'<div class="row">'."\n";  
	
	if ((has_post_thumbnail( $post->ID )) ||($options['default_postthumb_always']))  {
	    $output .= "\t\t".'<div class="span3">'."\n"; 
	    $output .= '<a href="'.$link.'" class="news-image';
	    if ($external==1) {
		$output .= ' ext-link';
	    }
	    $output .= '">';

	    $post_thumbnail_id = get_post_thumbnail_id( $post->ID, 'post-thumb' ); 
	    $imagehtml = '';
	    $imgwidth = $options['default_postthumb_width'];
	    $imgheight = $options['default_postthumb_height'];
	    if ($post_thumbnail_id) {
		$sliderimage = wp_get_attachment_image_src( $post_thumbnail_id,  'post-thumb');
		$imageurl = $sliderimage[0]; 	
		$imgwidth = $sliderimage[1];
		$imgheight = $sliderimage[2];
	    }
	    if (!isset($imageurl) || (strlen(trim($imageurl)) <4 )) {
		$imageurl = $options['default_postthumb_src'];
	    }
	    $output .= '<img src="'.fau_esc_url($imageurl).'" width="'.$imgwidth.'" height="'.$imgheight.'" alt="">';
	    $output .= '</a>';
	    
	    $output .= "\t\t".'</div>'."\n"; 
	    $output .= "\t\t".'<div class="span5">'."\n"; 
	} else {
	    $output .= "\t\t".'<div class="span8">'."\n"; 
	}
	$output .= "\t\t\t".'<p>'."\n"; 
	
	
	
	$abstract = get_post_meta( $post->ID, 'abstract', true );
	if (strlen(trim($abstract))<3) {
	   $abstract =  fau_custom_excerpt($post->ID,$options['default_anleser_excerpt_length'],false,'',true);
	}
	$output .= $abstract;

	
	$output .= '<a class="read-more-arrow';
	if ($external==1) {
	    $output .= ' ext-link';
	}
	$output .= '" href="'.$link.'">›</a>'; 
	$output .= "\t\t\t".'</p>'."\n"; 
	
	
	$output .= "\t\t".'</div>'."\n"; 
	$output .= "\t</div> <!-- /row -->\n";	
	$output .= "</article> <!-- /news-item -->\n";	
    }
    return $output;
}



/* 
 * Suchergebnisse 
 */
function fau_display_search_resultitem() {
    global $post;
    global $options;
    
    $output = '';
    $withthumb = $options['search_display_post_thumbnails'];
    $withcats =  $options['search_display_post_cats'];
    if (isset($post) && isset($post->ID)) {
	
	$link = get_post_meta( $post->ID, 'external_link', true );
	$external = 0;
	if (isset($link) && (filter_var($link, FILTER_VALIDATE_URL))) {
	    $external = 1;
	} else {
	    $link = fau_make_link_relative(get_permalink($post->ID));
	}
	
	
	$output .= '<article class="search-result">'."\n";
	$output .= "\t<h3><a ";
	if ($external==1) {
	    $output .= 'class="ext-link" ';
	}
	 $output .= "href=\"".$link."\">".get_the_title()."</a></h3>\n";
	$type = get_post_type();
	if ( $type == 'post') {
	     $typestr = '<div class="search-meta">';

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
	    $topevent_date = get_post_meta( $post->ID, 'topevent_date', true );
	    if ($topevent_date) {
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
	    
	} elseif ($type == 'event') {
	    $typestr = '<div class="search-meta">';
	    $typestr .= '<span class="post-meta-event"> ';
	    $typestr .= __('Veranstaltungshinweis', 'fau');
	    $typestr .= '</span>';
	    $typestr .= '</div>'."\n";
	} else  {
	     $typestr = '';
	}

	if (!empty($typestr)) { 
	     $output .= "\t".$typestr."\n"; 
	}
	$output .= "\t".'<div class="row">'."\n";  
	
	
	if (($withthumb==true) && (has_post_thumbnail( $post->ID )) )  {
	    $output .= "\t\t".'<div class="span3">'."\n"; 
	    $output .= '<a href="'.$link.'" class="news-image';
	    if ($external==1) {
		$output .= ' ext-link';
	    }
	    $output .= '">';

	    $post_thumbnail_id = get_post_thumbnail_id( $post->ID, 'post-thumb' ); 
	    $imagehtml = '';
	    if ($post_thumbnail_id) {
		$sliderimage = wp_get_attachment_image_src( $post_thumbnail_id,  'post-thumb');
		$imageurl = $sliderimage[0]; 	
	    }
	    if (!isset($imageurl) || (strlen(trim($imageurl)) <4 )) {
		$imageurl = $options['default_postthumb_src'];
	    }
	    $output .= '<img src="'.fau_esc_url($imageurl).'" width="'.$options['default_postthumb_width'].'" height="'.$options['default_postthumb_height'].'" alt="">';
	    $output .= '</a>';
	    
	    $output .= "\t\t".'</div>'."\n"; 
	    $output .= "\t\t".'<div class="span5">'."\n"; 
	} else {
	    $output .= "\t\t".'<div class="span8">'."\n"; 
	}
	
	
	
	$output .= "\t\t".'<p>'."\n"; 
	$output .= fau_custom_excerpt($post->ID,$options['default_search_excerpt_length'],false,'',true,$options['search_display_excerpt_morestring']);	
	if ($options['search_display_continue_arrow']) {
	    $output .= '<a class="read-more-arrow';
	    if ($external==1) {
		$output .= ' ext-link';
	    }
	    $output .= '" href="'.$link.'">›</a>'; 
	}
	$output .= "\t\t\t".'</p>'."\n"; 
	$output .= "\t</div> <!-- /row -->\n";	
	$output .= "</article>\n";
    } else {
	$output .= "<!-- empty result -->\n";
    }
    return $output;						     
							
}

function fau_breadcrumb($lasttitle = '') {
  global $options;
  
  $delimiter	= $options['breadcrumb_delimiter']; // = ' / ';
  $home		= $options['breadcrumb_root']; // __( 'Startseite', 'fau' ); // text for the 'Home' link
  $before	= $options['breadcrumb_beforehtml']; // '<span class="current">'; // tag before the current crumb
  $after	= $options['breadcrumb_afterhtml']; // '</span>'; // tag after the current crumb
  $pretitletextstart   = '<span>';
  $pretitletextend     = '</span>';
  
  if ($options['breadcrumb_withtitle']) {
	echo '<h3 class="breadcrumb_sitetitle" role="presentation">'.get_bloginfo( 'title' ).'</h3>';
	echo "\n";
    }
  echo '<nav aria-labelledby="bc-title" class="breadcrumbs">'; 
  echo '<h4 class="screen-reader-text" id="bc-title">'.__('Sie befinden sich hier:','fau').'</h4>';
  if ( !is_home() && !is_front_page() || is_paged() ) { 
    
    global $post;
    
    $homeLink = home_url('/');
    echo '<a href="' . $homeLink . '">' . $home . '</a>' . $delimiter;
 
    if ( is_category() ) {
	global $wp_query;
	$cat_obj = $wp_query->get_queried_object();
	$thisCat = $cat_obj->term_id;
	$thisCat = get_category($thisCat);
	$parentCat = get_category($thisCat->parent);
	if ($thisCat->parent != 0) 
	    echo(get_category_parents($parentCat, TRUE, $delimiter ));
	echo $before . single_cat_title('', false) .  $after;
 
    } elseif ( is_day() ) {
	echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' .$delimiter;
	echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a>' .$delimiter;
	echo $before . get_the_time('d') . $after; 
    } elseif ( is_month() ) {
	echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter;
	echo $before . get_the_time('F') . $after;
    } elseif ( is_year() ) {
	echo $before . get_the_time('Y') . $after; 
    } elseif ( is_single() && !is_attachment() ) {
	 
	if ( get_post_type() != 'post' ) {
	    $post_type = get_post_type_object(get_post_type());
	    $slug = $post_type->rewrite;
	    echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>' .$delimiter;
	    echo $before . get_the_title() . $after; 
	} else {
	    
	$cat = get_the_category(); 
	if ($options['breadcrumb_uselastcat']) {
	    $last = array_pop($cat);
	} else {
	    $last = $cat[0];
	}
	$catid = $last->cat_ID;

	echo get_category_parents($catid, TRUE,  $delimiter );
	echo $before . get_the_title() . $after;

	} 
    } elseif ( !is_single() && !is_page() && !is_search() && get_post_type() != 'post' && !is_404() ) {
	$post_type = get_post_type_object(get_post_type());
	echo $before . $post_type->labels->singular_name . $after;
    } elseif ( is_attachment() ) {
	$parent = get_post($post->post_parent);
	echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>'. $delimiter;
	echo $before . get_the_title() . $after;
    } elseif ( is_page() && !$post->post_parent ) {
	echo $before . get_the_title() . $after;
 
    } elseif ( is_page() && $post->post_parent ) {
	$parent_id  = $post->post_parent;
	$breadcrumbs = array();
	while ($parent_id) {
	    $page = get_page($parent_id);
	    $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
	    $parent_id  = $page->post_parent;
	}
	$breadcrumbs = array_reverse($breadcrumbs);
	foreach ($breadcrumbs as $crumb) echo $crumb . $delimiter;
	echo $before . get_the_title() . $after; 
    } elseif ( is_search() ) {
	if (isset($lasttitle) && (strlen(trim($lasttitle))>1)) {
	    echo $before . $lasttitle. $after; 
	} else {
	    echo $before .$pretitletextstart. __( 'Suche nach', 'fau' ).$pretitletextend.' "' . get_search_query() . '"' . $after; 
	}
    } elseif ( is_tag() ) {
	echo $before .$pretitletextstart. __( 'Schlagwort', 'fau' ).$pretitletextend. ' "' . single_tag_title('', false) . '"' . $after; 
    } elseif ( is_author() ) {
	global $author;
	$userdata = get_userdata($author);
	echo $before .$pretitletextstart. __( 'Beiträge von', 'fau' ).$pretitletextend.' '.$userdata->display_name . $after;
    } elseif ( is_404() ) {
	echo $before . '404' . $after;
    }

  } elseif (is_front_page())  {
	echo $before . $home . $after;
  } elseif (is_home()) {
	echo $before . get_the_title(get_option('page_for_posts')) . $after;
  }
   echo '</nav>'; 
   
  
   
}


function fau_wp_link_query_args( $query ) {
     // check to make sure we are not in the admin
   //  if ( !is_admin() ) {
          $query['post_type'] = array( 'post', 'page', 'person'  ); // show only posts and pages
   //  }
     return $query;
}
add_filter( 'wp_link_query_args', 'fau_wp_link_query_args' ); 



 if ( ! function_exists( 'fau_get_person_index' ) ) :  
    function fau_get_person_index($id=0) {
     global $options;
	$honorificPrefix = get_post_meta($id, 'fau_person_honorificPrefix', true);
	$givenName = get_post_meta($id, 'fau_person_givenName', true);
	$familyName = get_post_meta($id, 'fau_person_familyName', true);
	$honorificSuffix = get_post_meta($id, 'fau_person_honorificSuffix', true);
	$jobTitle = get_post_meta($id, 'fau_person_jobTitle', true);
	$telephone = get_post_meta($id, 'fau_person_telephone', true);
	$email = get_post_meta($id, 'fau_person_email', true);
	$worksFor = get_post_meta($id, 'fau_person_worksFor', true);
        $faxNumber = get_post_meta($id, 'fau_person_faxNumber', true);
        $type = get_post_meta($id, 'fau_person_typ', true);

	
	$fullname = '';
	if($honorificPrefix) 	$fullname .= '<span itemprop="honorificPrefix">'.$honorificPrefix.'</span> ';
	if($givenName) 	$fullname .= '<span itemprop="givenName">'.$givenName.'</span> ';
	if($familyName) 		$fullname .= '<span itemprop="familyName">'.$familyName.'</span>';
	if($honorificSuffix) 	$fullname .= ' '.$honorificSuffix;
	
	if (empty($fullname)) {
	    $fullname = get_the_title($id);
	}
     ?>
     
    <div class="person content-person" itemscope="" itemtype="http://schema.org/Person">
	<div class="row">
	    <div class="span1 span-small">		
		<?php 
		if (has_post_thumbnail()) {
		    echo get_the_post_thumbnail($id, 'person-thumb-bigger'); 
		} else {
		    if ($type == 'realmale') {
			$url = $options['plugin_fau_person_malethumb'];     
		    } elseif ($type == 'realfemale') {
			$url = $options['plugin_fau_person_femalethumb']; 
		    } else {
			$url = '';     
		    }
		    if ($url) {
			echo '<img src="'.$url.'" width="90" height="120" alt="">';
		    }
		}
		
		?>
	    </div>
	    <div class="span3">
		<h3><?php echo $fullname; ?></h3>
		<ul class="person-info">
		    <?php if ($jobTitle) { ?>
		    <li class="person-info-position"><span class="screen-reader-text">Tätigkeit: </span><span itemprop="jobTitle"><?php echo $jobTitle; ?></span></li>
		    <?php } ?>
		     <?php if ($telephone) { ?>
		    <li class="person-info-phone"><span class="screen-reader-text">Telefonnummer: </span><span itemprop="telephone"><?php echo $telephone; ?></span></li>
		    <?php } ?>
		
		    <?php if ($email) { ?>
		    <li class="person-info-email"><span class="screen-reader-text">E-Mail: </span><a itemprop="email" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></li>
		    <?php } ?>
		</ul>
	    </div>
	    <div class="span3">
		<div class="person-info-more"><a title="Weitere Informationen zu <?php echo $givenName; echo " ".$familyName;?> aufrufen" class="person-read-more" href="<?php the_permalink($id); ?>">Mehr ›</a></div>
	    </div>
	</div>
    </div>
    <?php 
}
endif;


if ( ! function_exists( 'fau_comment' ) ) :
/**
 * Template for comments and pingbacks.
 */
function fau_comment( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;
        global $options;         
        
        switch ( $comment->comment_type ) :
                case '' :
        ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
          <div id="comment-<?php comment_ID(); ?>">
            <article itemprop="comment" itemscope itemtype="http://schema.org/UserComments">
              <header>  
                <div class="comment-details">
                    
                <span class="comment-author vcard" itemprop="creator" itemscope itemtype="http://schema.org/Person">
                    <?php if ($options['advanced_comments_avatar']) {
                        echo '<div class="avatar" itemprop="image">';
                        echo get_avatar( $comment, 48); 
                        echo '</div>';   
                    } 
                    printf( __( '%s <span class="says">schrieb am</span>', 'fau' ), sprintf( '<cite class="fn" itemprop="name">%s</cite>', get_comment_author_link() ) ); 
                    ?>
                </span><!-- .comment-author .vcard -->
              

                <span class="comment-meta commentmetadata"><a itemprop="url" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time itemprop="commentTime" datetime="<?php comment_time('c'); ?>">
                    <?php
                          /* translators: 1: date, 2: time */
                       printf( __( '%1$s um %2$s Uhr', 'fau' ), get_comment_date(),  get_comment_time() ); ?></time></a> <?php echo __('folgendes','fau');?>:
                  
                </span><!-- .comment-meta .commentmetadata -->
                </div>
              </header>
		     <?php if ( $comment->comment_approved == '0' ) : ?>
                        <em><?php _e( 'Kommentar wartet auf Freischaltung.', 'fau' ); ?></em>
                        <br />
                <?php endif; ?>
                <div class="comment-body" itemprop="commentText"><?php comment_text(); ?></div>
		 <?php edit_comment_link( __( '(Bearbeiten)', 'fau' ), ' ' ); ?>
            </article>
          </div><!-- #comment-##  -->

        <?php
                        break;
                case 'pingback'  :
                case 'trackback' :
        ?>
        <li class="post pingback">
                <p><?php _e( 'Pingback:', 'fau' ); ?> <?php comment_author_link(); edit_comment_link( __('Bearbeiten', 'fau'), ' ' ); ?></p>
        <?php
                        break;
        endswitch;
}
endif;



function revealid_add_id_column( $columns ) {
   $columns['revealid_id'] = 'ID';
   return $columns;
}

function revealid_id_column_content( $column, $id ) {
  if( 'revealid_id' == $column ) {
    echo $id;
  }
}

if ($options['advanced_reveal_pages_id']) {
    add_filter( 'manage_pages_columns', 'revealid_add_id_column', 5 );
    add_action( 'manage_pages_custom_column', 'revealid_id_column_content', 5, 2 );
}

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
	    $result['orig_width'] = $data['width'];
	    $result['orig_height'] = $data['height'];
	    $result['orig_file'] = $data['file'];
	    
        }
	
        $attachment = get_post($id);
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
	$result['credits'] = '';
	
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


add_filter('the_content', 'remove_empty_p', 20, 1);
function remove_empty_p($content){
    $content = force_balance_tags($content);
    return preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
}
