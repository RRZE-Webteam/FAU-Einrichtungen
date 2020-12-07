<?php
/**
* @package WordPress
* @subpackage FAU
* @since FAU 1.0
*/

class FAUShortcodes {
	function __construct() {
	   remove_filter( 'the_content', 'wpautop' );
//	   add_filter( 'the_content', 'wpautop' , 10);
	   add_action( 'init', array( $this, 'add_shortcodes' ) );
	}

	function add_shortcodes() {
            add_shortcode('assistant', array( $this, 'fau_assistant' ));
            add_shortcode('organigram', array( $this, 'fau_organigram'));

            // Paragraphes and content regions
            add_shortcode('hr', array( $this, 'fau_hr'));


            // Blogroll und Artikellisten
            add_shortcode('blogroll', array( $this, 'fau_shortcode_blogroll'));
            add_shortcode('articlelist', array( $this, 'fau_shortcode_articlelist'));

            // Portalmenu
            add_shortcode( 'portalmenu', array( $this, 'fau_portalmenu'));

            add_shortcode('notice', array( $this, 'fau_notice' ));

	}


        /*-----------------------------------------------------------------------------------*/
        /* Portalmenus als Shortcode
        /*-----------------------------------------------------------------------------------*/
	function fau_portalmenu( $atts, $content = null) {
            extract(shortcode_atts(array(
                'menu'          => '',
                'showsubs'	=> true,
                'nothumbs'	=> false,
                'nofallback'    => false,
                'type'    => 1,
                'skewed'    => false,
            ), $atts));
            $out ='';
            $menu = $menu ?  esc_attr( $menu ) : '';
            $error = '<p class="box red-box">'.__("Es konnte kein Menu unter der angegebenen Bezeichnung gefunden werden",'fau').'</p>';
            $error .= "name=$menu";
            if (! fau_empty($menu)) {
                if ($menu == sanitize_key($menu)) {
                    $term = get_term_by('id', $menu, 'nav_menu');
                } else {
                    $term = get_term_by('name', $menu, 'nav_menu');
                }
                if ($term===false) {
                    $out = $error;
                } else {
		    $slug = $term->slug;
		    $subentries = get_theme_mod('default_submenu_entries');
		    $spalte = get_theme_mod('default_submenu_spalten');

		    $a_contentmenuclasses[] = 'contentmenu';
		    $thumbnail = 'rwd-480-2-1';
		    $type = intval($type);
                   
		    switch ($type) {
                    	case 1:
				$thumbnail = 'rwd-480-2-1';
                    		break;
                    	case 2:
                    		$showsubs = false;
                    		$a_contentmenuclasses[] = 'refresh';
                    		$a_contentmenuclasses[] = 'no-shadow';
                   		$thumbnail = 'rwd-480-3-2';
                    		break;
                    	case 3:
                    		$showsubs = false;
                    		$a_contentmenuclasses[] = 'refresh';
                    		$a_contentmenuclasses[] = 'no-shadow';
                    		$thumbnail = 'gallery-full';
                    		break;
                    	default:
				$thumbnail = 'rwd-480-2-1';
                    		$type = 1;
                    		break;
		    }
                    $a_contentmenuclasses[] = 'contentmenutype' . $type;

                    if($skewed) {
                    	$a_contentmenuclasses[] = 'skewed';
                    }
                    $out .= '<div class="'. implode(' ',$a_contentmenuclasses) . '" role="navigation">';
                    $out .= '<ul class="subpages-menu">';
                    $outnav = wp_nav_menu( array( 'menu' => $slug,
                        'echo'          => false,
                        'container'     => true,
												'items_wrap'     => '%3$s',
                        'link_before'   => '',
                        'link_after'    => '',
                        'item_spacing'  => 'discard',
                        'walker'        => new Walker_Content_Menu($slug,$showsubs,$spalte,$nothumbs,$nofallback,$thumbnail)));
                    $out .= $outnav;
                    $out .=  "</ul></div>";
                }
            } else {
                $out =  $error;
            }
            return $out;
        }


	function fau_organigram( $atts, $content = null) {
		extract(shortcode_atts(array(
			"menu" => 'menu'
			), $atts));

		return wp_nav_menu( array('menu' => $menu, 'container' => false, 'menu_id' => 'organigram', 'menu_class' => 'organigram', 'echo' => 0));
	}



	function fau_subpages( $atts, $content = null ) {
		return '<div class="row">' . do_shortcode( $content ) . '</div>';
	}


	function fau_assistant( $atts, $content = null) {
		extract(shortcode_atts(array(
			"id" => 'id'
			), $atts));

		$return = '';
		$return .= '<div class="accordion">';

		$pages = get_pages(array('sort_order' => 'ASC', 'sort_column' => 'menu_order', 'parent' => $id, 'hierarchical' => 0));
		$i = 0;
		foreach($pages as $page) {

		    $inner = '';
		    $subpages = get_pages(array('sort_order' => 'ASC', 'sort_column' => 'menu_order', 'parent' => $page->ID, 'hierarchical' => 0));

		    if(count($subpages) > 0)  {
			$inner .= '<div class="assistant-tabs">';

			    $inner .= '<ul class="assistant-tabs-nav">';

			    $j = 0;
			    foreach($subpages as $subpage) {
				    if($j == 0) $class = 'active';
				    else $class = '';

				    $inner .= '<li><a href="#accordion-'.$page->ID.'-'.$i.'-tab-'.$j.'" class="accordion-tabs-nav-toggle '.$class.'">'.$subpage->post_title.'</a></li>';
				    $j++;
			    }
			    $inner .= '</ul>';

			    $j = 0;
			    foreach($subpages as $subpage) {
				    if($j == 0) $class = 'assistant-tab-pane-active';
				    else $class = '';
				    $inner .= '<div class="assistant-tab-pane '.$class.'" id="accordion-'.$page->ID.'-'.$i.'-tab-'.$j.'">';
				    $inner .= do_shortcode($subpage->post_content);
				    $inner .= '</div>';
				    $j++;
			    }
			$inner .= '</div>';
		    }  else {
			$inner .= do_shortcode($page->post_content);
		    }


		    $thisid = $page->ID.'000'.$i;
		    $return .= getAccordionbyTheme($thisid, $page->post_title, '', '','', $inner);
		    $i++;
		}

		$return .= '</div>';
		if ( is_plugin_active( 'rrze-elements/rrze-elements.php' ) ) {
		    wp_enqueue_script('rrze-accordions');
		}
		return $return;
	}


	function fau_hr ( $atts, $content = null) {
	    extract(shortcode_atts(array(
			"size" => '',
			"class" => ''
			), $atts));


	    $size = fau_sanitize_hr_shortcode($size);
	    $class = fau_sanitize_hr_shortcode($class);

	    $classes = "";
	    if (!fau_empty($size)) {
		$classes .= $size;
	    }
	    if (!fau_empty($class)) {
		 if (!fau_empty($classes)) {
		     $classes .= " ";
		 }
		$classes .= $class;
	    }

	    $return = '<hr';
	    $return .= ($classes) ? ' class="' . $classes. '"' : '';
	    $return .= '>';

	    return $return;
	}








      /*
       * Not for productive use yet
       */
    function fau_notice($atts, $content = null) {
	 extract(shortcode_atts(array(
	    "type" => 'hinweis',
	    "block" => ''
	 ), $atts));


	$block = $block ? esc_attr( $block ) : 'p';
	$type = $type ? 'notice-'.$type  : 'notice-hinweis';

	$return  =  '<p ';
	$return .= 'class="'.$type.'"';
	$return .= '>' . do_shortcode( $content ) . '</p>';

	return $return;
      }









  /*-----------------------------------------------------------------------------------*/
/* Shortcodes to display default blogroll
/*-----------------------------------------------------------------------------------*/
function fau_shortcode_blogroll( $atts, $content = null ) {
    extract(shortcode_atts(array(
    'cat'	=> '',
    'tag'	=> '',
    'num'	=> '',
    'divclass'	=> '',
    'hidemeta'	=> false,
    'hstart'	=> 2,
    ), $atts));

    $cat = ($cat) ? $cat : '';
    $tag = ($tag) ? $tag : '';
    $num = ($num) ? intval($num) : 4;
    $hstart = ($hstart) ? intval($hstart) : 2;
    $divclass = $divclass ? esc_attr( $divclass ) : '';


    if (!is_page()) {
	$out =  '<p class="attention">'.__("Der Shortcode darf nur auf Seiten verwendet werden.",'fau').'</p>';
	return $out;
    }

    $out = fau_blogroll($tag, $cat, $num, $divclass, $hstart,$hidemeta);

    if (empty($out)) {
	$out =  '<p class="attention">'.__("Es konnten keine Artikel gefunden werden",'fau').'</p>';
    }
    return $out;
}
/*-----------------------------------------------------------------------------------*/
/* Shortcodes to display articlelist
/*-----------------------------------------------------------------------------------*/
function fau_shortcode_articlelist( $atts, $content = null ) {
    extract(shortcode_atts(array(
    'cat'	=> '',
    'tag'	=> '',
    'num'	=> '',
    'class'     => '',
    'title'	=> '',
    ), $atts));
    $title =  esc_attr($title);
    $cat = ($cat) ? $cat : '';
    $tag = ($tag) ? $tag : '';
    $num = ($num) ? intval($num) : 5;
    $class = ($class) ? $class : '';

    if (!is_page()) {
	$out =  '<p class="attention">'.__("Der Shortcode darf nur auf Seiten verwendet werden.",'fau').'</p>';
	return $out;
    }

    $out = fau_articlelist($tag, $cat, $num,$class, $title);

    if (empty($out)) {
	$out = '<p class="attention">'.__("Es konnten keine Artikel gefunden werden",'fau').'</p>';
    }
    return $out;
}



}
new FAUShortcodes();


?>
