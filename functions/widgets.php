<?php
/**
* @package WordPress
* @subpackage FAU
* @since FAU 1.2
*/


/* Tagcloud Menu Widget */
global $wp_embed, $options;


/*-----------------------------------------------------------------------------------*/
/* Registers our main widget area and the front page widget areas.
/*-----------------------------------------------------------------------------------*/
function fau_sidebars_init() {
    global $defaultoptions;
	register_sidebar( array(
		'name' => __( 'News Sidebar', 'fau' ),
		'id' => 'news-sidebar',
		'description' => __( 'Sidebar auf der News-Kategorieseite', 'fau' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Suche Sidebar', 'fau' ),
		'id' => 'search-sidebar',
		'description' => __( 'Sidebar auf der Such-Ergebnisseite links', 'fau' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s" aria-label="'.__('Suche', 'fau').' Sidebar">',
		'after_widget' => '</aside>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	) );
	register_sidebar( array(
		'name' => __( 'Footer Sidebar', 'fau' ),
		'id' => 'startpage-socialmediainfo',
		'description' => __( 'Widgetbereich oberhalb des Seitenendes (Footer).', 'fau' ),
		'before_widget' => '<div class="social-media-widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	) );
	
    // Wenn CMS-Workflow vorhanden und aktiviert ist.
    if (is_workflow_translation_active()) {
        register_sidebar( array(
            'name' => __( 'Sprachwechsler', 'fau' ),
            'id' => 'language-switcher',
            'description' => __( 'Sprachwechsler im Header der Seite', 'fau' ),
            'before_widget' => '<div class="meta-widget cms-workflow-widget">',
            'after_widget' => '</div>',
        ) );
    }
    // Wenn das Widget des RRZE-Multilang-Plugins vorhanden ist.
    elseif (apply_filters('rrze_multilang_widget_enabled', false)) {
        register_sidebar( array(
            'name' => __( 'Sprachwechsler', 'fau' ),
            'id' => 'language-switcher',
            'description' => __( 'Sprachwechsler im Header der Seite', 'fau' ),
            'before_widget' => '<div class="meta-widget rrze-multilang-widget">',
            'after_widget' => '</div>',

        ) );
    }

	$website_type = get_theme_mod('website_type');
	if ($website_type==3) {
	    register_sidebar( array(
		'name' => __( 'Footer Logo Bereich Position 1', 'fau' ),
		'id' => 'footer-block1',
		'description' => __( 'Erster Widgetbereich im Footer, links unten.', 'fau' ),
		'before_title' => '<h3 class="screen-reader-text">',
		'after_title' => '</h3>',
		'before_widget' => '<div class="footer-block1">',
		'after_widget' => '</div>',
	    ) );
	    register_sidebar( array(
		'name' => __( 'Footer Logo Bereich Position 2', 'fau' ),
		'id' => 'footer-block2',
		'description' => __( 'Zweiter Widgetbereich im Footer, links unten. ', 'fau' ),
		'before_title' => '<h3 class="screen-reader-text">',
		'after_title' => '</h3>',
		'before_widget' => '<div class="footer-block2">',
		'after_widget' => '</div>',
	    ) );
	}
	$page_sidebar = get_theme_mod('advanced_page_sidebar_wpsidebar');
	if ($page_sidebar) {
	    register_sidebar( array(
		'name' => __( 'Seiten Sidebar', 'fau' ),
		'id' => $defaultoptions['advanced_page_sidebar_wpsidebar_id'],
		'description' => __('Widgets die auf allen Seiten angezeigt werden sollen. Sollten Seitenspezifische Inhalte in der Sidebar angegeben worden sein, wird diese Sidebar darunter folgen. Diese Sidebar-Inhalte werden nicht auf Beiträgen gezeigt.', 'fau' ),
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		'before_widget' => '<div class="page-sidebar-widgets">',
		'after_widget' => '</div>',
	    ) );
	}
	
	
}
add_action( 'widgets_init', 'fau_sidebars_init' );

/*
 * Format Widgets
 */
add_filter( 'widget_text', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'widget_text', array( $wp_embed, 'autoembed'), 8 );
add_filter('widget_text','do_shortcode');


