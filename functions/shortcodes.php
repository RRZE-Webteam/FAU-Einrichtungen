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
            add_shortcode('organigram', array( $this, 'fau_organigram'));

            // Paragraphes and content regions
            add_shortcode('hr', array( $this, 'fau_hr'));


            // Blogroll und Artikellisten
            add_shortcode('blogroll', array( $this, 'fau_shortcode_blogroll'));
            add_shortcode('articlelist', array( $this, 'fau_shortcode_articlelist'));

            // Portalmenu
            add_shortcode( 'portalmenu', array( $this, 'fau_portalmenu'));


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
                'type'          => 1,
                'skewed'        => false,
            ), $atts));
            
            $out ='';
	    
            $menu = $menu ?  esc_attr( $menu ) : '';
            $error = '<p>'.__("Es konnte kein Menu unter der angegebenen Bezeichnung gefunden werden",'fau').'</p>';
            $error .= "name=$menu";
            if (! fau_empty($menu)) {
		
		$showsubs = filter_var($showsubs, FILTER_VALIDATE_BOOLEAN);
		$nothumbs = filter_var($nothumbs, FILTER_VALIDATE_BOOLEAN);
		$nofallback = filter_var($nofallback, FILTER_VALIDATE_BOOLEAN);
		$skewed = filter_var($skewed, FILTER_VALIDATE_BOOLEAN);
		
		
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
                    if ($showsubs===false) {
                        $subentries = 0;
                    }
		//    $spalte = get_theme_mod('default_submenu_spalten');

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
                    
                    

                    if ($skewed===true) {
                    	$a_contentmenuclasses[] = 'skewed';
                    }
                    if ($showsubs===false) {
                    	$a_contentmenuclasses[] = 'no-sub';
                    }
                    if ($nofallback === true) {
						$a_contentmenuclasses[] = 'no-fallback';
					}
                    if ($nothumbs === true) {
						$a_contentmenuclasses[] = 'no-thumb';
					}
                    $out .= '<div class="'. implode(' ',$a_contentmenuclasses) . '" role="navigation" aria-label="'.__('Inhaltsmenü','fau').'">';
                    $out .= '<ul class="subpages-menu">';
                    $outnav = wp_nav_menu( array( 'menu' => $slug,
                        'echo'          => false,
                        'container'     => true,
			'items_wrap'     => '%3$s',
                        'link_before'   => '',
                        'link_after'    => '',
                        'item_spacing'  => 'discard',
                       
                        'walker'        => new Walker_Content_Menu($slug,$showsubs,$subentries,$nothumbs,$nofallback,$thumbnail)));
                    $out .= $outnav;
                    $out .=  "</ul></div>";
                }
            } else {
                $out =  $error;
            }
            return $out;
        }

	/*-----------------------------------------------------------------------------------*/
      /* Display a menu as organigram
      /*-----------------------------------------------------------------------------------*/
	function fau_organigram( $atts, $content = null) {
		extract(shortcode_atts(array(
			"menu" => 'menu'
			), $atts));

		return wp_nav_menu( array('menu' => $menu, 'container' => false, 'menu_id' => 'organigram', 'menu_class' => 'organigram', 'echo' => 0));
	}

	/*-----------------------------------------------------------------------------------*/
      /* Special HRs for FAU
      /*-----------------------------------------------------------------------------------*/

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
