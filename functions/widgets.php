<?php
/**
* @package WordPress
* @subpackage FAU
* @since FAU 1.2
*/


/* Tagcloud Menu Widget */
global $options;


/*-----------------------------------------------------------------------------------*/
/* Registers our main widget area and the front page widget areas.
/*-----------------------------------------------------------------------------------*/
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
		'description' => __( 'Widgetbereich neben den Social Media Icons im Footer.', 'fau' ),
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
		    'before_widget' => '<div class="meta-widget">',
		    'after_widget' => '</div>',
		    'before_title' => '<h3>',
		    'after_title' => '</h3>',
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

add_action('widgets_init', function() {
if (function_exists('get_field'))
        return register_widget( 'FAUMenuTagcloudWidget' );
});

/*-----------------------------------------------------------------------------------*/
/* Walker für eine bessere Tagcloud
/*-----------------------------------------------------------------------------------*/
class Walker_Tagcloud_Menu extends Walker_Nav_Menu {
	
	function start_lvl( &$output, $depth = 0, $args = array() ) {

	}
	
	function end_lvl( &$output, $depth = 0, $args = array() ) {

	}
	
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$classes[] = 'span2';

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts['class'] = 'logo-item';

		$post = get_post($item->object_id);

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args->before;
		
		$item_output .= '<li class="tag">';
		if($post->post_type == 'imagelink')
		{
			$item_output .= '<a'. $attributes .' href="'.get_field('protocol', $item->object_id).get_field('link', $item->object_id).'">';
		}
		else
		{
			$item_output .= '<a'. $attributes .'>';
		}

		$item_output .= $item->title;
		
		$item_output .= '</a>';
		$item_output .= '</li>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	
	function end_el(&$output, $item, $depth=0, $args=array()) {      

    }  
    
}

/*-----------------------------------------------------------------------------------*/
/* Auswahl des Menus für eine Tagcloud als Widget
/*-----------------------------------------------------------------------------------*/
class FAUMenuTagcloudWidget extends WP_Widget {
	public function __construct() {
	    parent::__construct(
		'FAUMenuTagcloudWidget', __('Tagcloud-Menü', 'fau'), array(
		'description' => __('Tagcloud-Menü', 'fau'),
		'class' => 'FAUMenuTagcloudWidget',
		)
	    ); 
	}
 

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'menu-slug' => '' ) );
		$slug = $instance['menu-slug'];
		if (isset($instance['title'])) {
		    $title = $instance['title'];
		} else {
		    $title = '';
		}
		
		$menus = get_terms('nav_menu');
		
		echo '<p>';
			echo '<label for="'.$this->get_field_id('title').'">'. __('Titel', 'fau'). ': </label>';
			echo '<input type="text" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.$title.'">';
		echo '</p>';
		
		echo '<p>';
			echo '<label for="'.$this->get_field_id('menu-slug').'">' . __('Menü', 'fau') . ': ';
				echo '<select id="'.$this->get_field_id('menu-slug').'" name="'.$this->get_field_name('menu-slug').'">';
					foreach($menus as $item)
					{
						echo '<option value="'.$item->slug.'"';
							if($item->slug == esc_attr($slug)) echo ' selected';
						echo '>'.$item->name.'</option>';
					}
				echo '</select>';
			echo '</label>';
		echo '</p>';

	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['menu-slug'] = $new_instance['menu-slug'];
		$instance['title'] = $new_instance['title'];
		return $instance;
	}

	function widget($args, $instance)
	{
		extract($args, EXTR_SKIP);

		echo $before_widget;
		
		if(!empty($instance['title']))	echo '<h2 class="small">'.$instance['title'].'</h2>';
		
		echo '<ul class="tagcloud">';
		
		$slug = empty($instance['menu-slug']) ? ' ' : $instance['menu-slug'];

		if (!empty($slug))
		{
			wp_nav_menu( array( 'menu' => $slug, 'container' => false, 'items_wrap' => '%3$s', 'link_before' => '', 'link_after' => '', 'walker' => new Walker_Tagcloud_Menu));
		}
		
		echo '</ul>';
		echo $after_widget;
	}
}

