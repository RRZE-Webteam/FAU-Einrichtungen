<?php
/**
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

class FAUShortcodes {
	function __construct()	{
		add_action('init', array($this, 'add_shortcodes'));
	}

	function add_shortcodes()	{
		add_shortcode('organigram', array($this, 'fau_organigram'));

        // Blogroll und Artikellisten
		add_shortcode('blogroll', array($this, 'fau_shortcode_blogroll'));
		add_shortcode('articlelist', array($this, 'fau_shortcode_articlelist'));

		// Portalmenu
		add_shortcode('portalmenu', array($this, 'fau_portalmenu'));

	}

	/*-----------------------------------------------------------------------------------*/
	/* Portalmenus als Shortcode
	/*-----------------------------------------------------------------------------------*/
	function fau_portalmenu($atts, $content = null)	{
        global $defaultoptions;
		extract(shortcode_atts(
			array(
				'menu' => '',
				'meganav' => false,
				'showsubs' => true,
				'nothumbs' => false,
				'nofallback' => false,
				'type' => 1,
				'listview' => false,
				'hoverzoom' => false,
				'hoverblur' => false,
			), $atts));

		$out = '';

		$menu = $menu ? esc_attr($menu) : '';
		$error = '<p>' . __("Es konnte kein Menü unter der angegebenen Bezeichnung gefunden werden, oder das Menü enthielt keine Inhalte.", 'fau') . '</p>';
		if (!fau_empty($menu)) {

			$global_hoverzoom = get_theme_mod('portalmenus_hover_zoom', $defaultoptions['portalmenus_hover_zoom']);
			$global_hoverzoom = filter_var($global_hoverzoom, FILTER_VALIDATE_BOOLEAN);
			$global_hoverblur = get_theme_mod('portalmenus_hover_blur', $defaultoptions['portalmenus_hover_blur']);
			$global_hoverblur = filter_var($global_hoverblur, FILTER_VALIDATE_BOOLEAN);

			$meganav = filter_var($meganav, FILTER_VALIDATE_BOOLEAN);
			$listview = filter_var($listview, FILTER_VALIDATE_BOOLEAN);
			$showsubs = filter_var($showsubs, FILTER_VALIDATE_BOOLEAN);
			$nothumbs = filter_var($nothumbs, FILTER_VALIDATE_BOOLEAN);
			$nofallback = filter_var($nofallback, FILTER_VALIDATE_BOOLEAN);
			$hoverzoom = filter_var($hoverzoom, FILTER_VALIDATE_BOOLEAN);
			$hoverblur = filter_var($hoverblur, FILTER_VALIDATE_BOOLEAN);

			$hoverzoom = $hoverzoom ? $hoverzoom : $global_hoverzoom;
			$hoverblur = $hoverblur ? $hoverblur : $global_hoverblur;

			if ($menu == sanitize_key($menu)) {
				$term = get_term_by('id', $menu, 'nav_menu');
			} else {
				$term = get_term_by('name', $menu, 'nav_menu');
			}
            
            $menu_items = wp_get_nav_menu_items($term->term_id);
            
            
			if  ((empty($menu_items)) || ($term === false)) {
				$out = $error;
			} else {
				$slug = $term->slug;
				$subentries = get_theme_mod('default_submenu_entries');
				if ($showsubs === false) {
					$subentries = 0;
				}

				$a_contentmenuclasses[] = 'contentmenu';
				$thumbnail = 'rwd-480-2-1';
				$type = intval($type);

				switch ($type) {
					case 1:
						$thumbnail = 'rwd-480-2-1';
						$a_contentmenuclasses[] = 'size_2-1';
						break;
					case 2:
						$a_contentmenuclasses[] = 'size_3-2';
						$thumbnail = 'full';
						break;
					case 3:
						$a_contentmenuclasses[] = 'size_3-4';
						$thumbnail = 'full';
						break;
					default:
						$thumbnail = 'rwd-480-2-1';
						$type = 1;
						break;
				}

				if ($meganav === true) {
					$a_contentmenuclasses[] = 'meganav';
				}
				if ($showsubs === false) {
					$a_contentmenuclasses[] = 'no-sub';
				}
				if ($nofallback === true) {
					$a_contentmenuclasses[] = 'no-fallback';
				}
				if ($nothumbs === true) {
					$a_contentmenuclasses[] = 'no-thumb';
				}
				if ($listview === true) {
					$a_contentmenuclasses[] = 'listview';
				}
				if ($hoverzoom === true) {
					$a_contentmenuclasses[] = 'hover-zoom';
				}
				if ($hoverblur === true) {
					$a_contentmenuclasses[] = 'hover-blur';
				}
				$out .= '<div class="' . implode(' ', $a_contentmenuclasses) . '" role="navigation" aria-label="' . __('Inhaltsmenü', 'fau') . '">';
				$outnav = wp_nav_menu(
					array(
						'menu' => $slug,
						'echo' => false,
						'container' => true,
						'items_wrap' => '%3$s',
						'link_before' => '',
						'link_after' => '',
						'item_spacing' => 'discard',

						'walker' => new Walker_Content_Menu($slug, $showsubs, $subentries, $nothumbs, $nofallback, $thumbnail, $listview, $meganav)
					)
				);
				if ($listview === true) {
					$out .= $outnav;
				} else {
					$out .= '<ul class="subpages-menu">';
					$out .= $outnav;
					$out .= '</ul>';				}
				$out .= '</div>';
			}
		} else {
			$out = $error;
		}
		return $out;
	}

	/*-----------------------------------------------------------------------------------*/
	/* Display a menu as organigram
	/*-----------------------------------------------------------------------------------*/
	function fau_organigram($atts, $content = null) {
		extract(shortcode_atts(
			array(
				"menu" => 'menu'
			), $atts));

		return wp_nav_menu(array('menu' => $menu, 'container' => false, 'menu_id' => 'organigram', 'menu_class' => 'organigram', 'echo' => 0));
	}

	/*-----------------------------------------------------------------------------------*/
	/* Shortcodes to display default blogroll
	/*-----------------------------------------------------------------------------------*/
	function fau_shortcode_blogroll($atts, $content = null)	{
		extract(shortcode_atts(
			array(
				'cat' => '',
				'tag' => '',
				'num' => '',
				'divclass' => '',
				'hidemeta' => false,
				'hstart' => 2,
			), $atts));

		$cat = ($cat) ? $cat : '';
		$tag = ($tag) ? $tag : '';
		$num = ($num) ? intval($num) : 4;
		$hstart = ($hstart) ? intval($hstart) : 2;
		$divclass = $divclass ? esc_attr($divclass) : '';


		if (!is_page()) {
			$out = '<p class="attention">' . __("Der Shortcode darf nur auf Seiten verwendet werden.", 'fau') . '</p>';
			return $out;
		}

		$out = fau_blogroll($tag, $cat, $num, $divclass, $hstart, $hidemeta);

		if (empty($out)) {
			$out = '<p class="attention">' . __("Es konnten keine Artikel gefunden werden", 'fau') . '</p>';
		}
		return $out;
	}
	/*-----------------------------------------------------------------------------------*/
	/* Shortcodes to display articlelist
	/*-----------------------------------------------------------------------------------*/
	function fau_shortcode_articlelist($atts, $content = null)	{
		extract(shortcode_atts(
			array(
				'cat' => '',
				'tag' => '',
				'num' => '',
				'class' => '',
				'title' => '',
			), $atts));
		$title = esc_attr($title);
		$cat = ($cat) ? $cat : '';
		$tag = ($tag) ? $tag : '';
		$num = ($num) ? intval($num) : 5;
		$class = ($class) ? $class : '';

		if (!is_page()) {
			$out = '<p class="attention">' . __("Der Shortcode darf nur auf Seiten verwendet werden.", 'fau') . '</p>';
			return $out;
		}

		$out = fau_articlelist($tag, $cat, $num, $class, $title);

		if (empty($out)) {
			$out = '<p class="attention">' . __("Es konnten keine Artikel gefunden werden", 'fau') . '</p>';
		}
		return $out;
	}

}
new FAUShortcodes();

/*-----------------------------------------------------------------------------------*/
/* Shortcodes to display relevant articlelist
/*-----------------------------------------------------------------------------------*/
function relevant_posts_shortcode($atts){
	// Parse shortcode attributes
	$atts = shortcode_atts(
		array(
			'tag' => '',
			'max' => 5,
		), $atts);

	// Get tag slugs
	$tag_slugs = array();
	if (!empty($atts['tag'])) {
		$tag_slugs = explode(',', $atts['tag']);
		$tag_slugs = array_map('trim', $tag_slugs);
	} else {
		$tags = wp_get_post_tags(get_the_ID());
		if (!$tags) {
			// If no tags, return empty
			return '<div class="relevant-posts empty">'.__('Keine relevanten Beiträge gefunden','fau').'</div>';
		}
		foreach ($tags as $tag) {
			$tag_slugs[] = $tag->slug;
		}
	}

	// Build query arguments
	$args = array(
		'tag_slug__in' => $tag_slugs,
		'post__not_in' => array(get_the_ID()),
		'posts_per_page' => $atts['max'],
		'ignore_sticky_posts' => true,
	);

	// Run the query
	$query = new WP_Query($args);

	// Build the output
	$output = '';
	if ($query->have_posts()) {
		$output .= '<ul>';
		while ($query->have_posts()) {
			$query->the_post();
			$output .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
		}
		$output .= '</ul>';
	}

	// Clean up
	wp_reset_postdata();

	// Return the output wrapped in a <div> with the relevant-posts class
	if (!empty($output)) {
		return '<div class="relevant-posts">' . $output . '</div>';
	} else {
		return '<div class="relevant-posts empty">'.__('Keine relevanten Beiträge gefunden','fau').'</div>';
	}
}

add_shortcode('relevant-posts', 'relevant_posts_shortcode');

/*-----------------------------------------------------------------------------------*/
/* Shortcodes to display row outide the margin for 25%
/*-----------------------------------------------------------------------------------*/
function row_outside_box( $atts, $content = null ) {
    return '<div class="fau-row-outisde-div" ><div style="fau-row-outisde">' . do_shortcode( $content ) . '</div></div>';
  }
  
  add_shortcode( 'row_outside_box', 'row_outside_box' );



  

?>
