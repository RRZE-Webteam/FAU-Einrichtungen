<?php
/**
* @package WordPress
* @subpackage FAU
* @since FAU 1.0
*/

class FAUShortcodes {
	function __construct() {
	   remove_filter( 'the_content', 'wpautop' );
	   add_filter( 'the_content', 'wpautop' , 12);
	   add_action( 'init', array( $this, 'add_shortcodes' ) );
	}

	function add_shortcodes() {
            add_shortcode('assistant', array( $this, 'fau_assistant' ));
            add_shortcode('organigram', array( $this, 'fau_organigram'));

            // Paragraphes and content regions
            add_shortcode('hr', array( $this, 'fau_hr'));

            // Ported and adapted by old bootstrap code
            add_shortcode('code', array( $this, 'bs_code' ));
            add_shortcode('span', array( $this, 'bs_span' ));
            add_shortcode('row', array( $this, 'bs_row' ));
            add_shortcode('table', array( $this, 'bs_table' ));

            // Blogroll und Artikellisten
            add_shortcode('blogroll', array( $this, 'fau_shortcode_blogroll'));
            add_shortcode('articlelist', array( $this, 'fau_shortcode_articlelist'));

            // Portalmenu
            add_shortcode( 'portalmenu', array( $this, 'fau_portalmenu'));

            add_shortcode('notice', array( $this, 'fau_notice' ));

            if ( !is_plugin_active( 'rrze-elements/rrze-elements.php' ) ) {

                add_shortcode('button', array( $this, 'bs_button' ));

                // Old Shortcodes for downwards compatibility
                add_shortcode('alert', array( $this, 'absatzklasse_attention' ));
                add_shortcode('attention', array( $this, 'absatzklasse_attention' ));
                add_shortcode('hinweis', array( $this, 'absatzklasse_hinweis' ));
                add_shortcode('baustelle', array( $this, 'absatzklasse_baustelle' ));
                add_shortcode('plus', array( $this, 'absatzklasse_plus' ));
                add_shortcode('minus', array( $this, 'absatzklasse_minus' ));
                add_shortcode('question', array( $this, 'absatzklasse_question' ));
                // New Shortcodes in defined syntax
                add_shortcode('notice-alert', array( $this, 'absatzklasse_attention' ));
                add_shortcode('notice-attention', array( $this, 'absatzklasse_attention' ));
                add_shortcode('notice-hinweis', array( $this, 'absatzklasse_hinweis' ));
                add_shortcode('notice-baustelle', array( $this, 'absatzklasse_baustelle' ));
                add_shortcode('notice-plus', array( $this, 'absatzklasse_plus' ));
                add_shortcode('notice-minus', array( $this, 'absatzklasse_minus' ));
                add_shortcode('notice-question', array( $this, 'absatzklasse_question' ));
                add_shortcode('notice-tipp', array( $this, 'absatzklasse_tipp' ));
                add_shortcode('notice-video', array( $this, 'absatzklasse_video' ));
                add_shortcode('notice-audio', array( $this, 'absatzklasse_audio' ));
                add_shortcode('notice-download', array( $this, 'absatzklasse_download' ));
                add_shortcode('notice-faubox', array( $this, 'absatzklasse_faubox' ));

                // Spalten
                add_shortcode( 'two_columns_one', array( $this, 'fau_shortcode_two_columns_one'));
                add_shortcode( 'two_columns_one_last', array( $this, 'fau_shortcode_two_columns_one_last'));
                add_shortcode( 'three_columns_one', array( $this, 'fau_shortcode_three_columns_one'));
                add_shortcode( 'three_columns_one_last', array( $this, 'fau_shortcode_three_columns_one_last' ));
            }
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


      function bs_button($atts, $content = null) {
	 extract(shortcode_atts(array(
	    "type" => false,
	    "size" => false,
	    "link" => '',
	    "xclass" => false
	 ), $atts));

	 $return  =  '<a href="' . $link . '" class="btn';
	 $return .= ($type) ? ' btn-' . $type : '';
	 $return .= ($size) ? ' btn-' . $size : '';
	 $return .= ($xclass) ? ' ' . $xclass : '';
	 $return .= '">' . do_shortcode( $content ) . '</a>';

	 return $return;
      }

      function bs_code($atts, $content = null) {
	 extract(shortcode_atts(array(
	    "type" => '',
	    "size" => '',
	    "link" => ''
	 ), $atts));
	 return '<pre><code>' . $content . '</code></pre>';
      }


      function bs_span( $atts, $content = null ) {
	extract(shortcode_atts(array(
	  "size" => 'size'
	), $atts));
	$size = $size ? ' ' . esc_attr( $size ) : '';
	return '<div class="span' . $size . '">' . do_shortcode( $content ) . '</div>';
      }


      function bs_row( $atts, $content = null ) {
	return '<div class="row">' . do_shortcode( $content ) . '</div>';
      }



      function bs_table( $atts ) {
	  extract( shortcode_atts( array(
	      'cols' => 'none',
	      'data' => 'none',
	      'type' => 'type'
	  ), $atts ) );
	  $cols = explode(',',$cols);
	  $data = explode(',',$data);
	  $total = count($cols);
	  $output = '';
	  $output .= '<table class="table table-'. $type .' table-bordered"><tr>';
	  foreach($cols as $col):
	      $output .= '<th>'.$col.'</th>';
	  endforeach;
	  $output .= '</tr><tr>';
	  $counter = 1;
	  foreach($data as $datum):
	      $output .= '<td>'.$datum.'</td>';
	      if($counter%$total==0):
		  $output .= '</tr>';
	      endif;
	      $counter++;
	  endforeach;
	      $output .= '</table>';
	  return $output;
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

    /*
     * Absatzklasse attention
     */
      function absatzklasse_attention($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="notice-attention">' . do_shortcode( $content ) . '</p>';
      }

      /*
       * Absatzklasse hinweis
       */
      function absatzklasse_hinweis($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="notice-hinweis">' . do_shortcode( $content ) . '</p>';
      }

      /*
       * Absatzklasse baustelle
       */
      function absatzklasse_baustelle($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="notice-baustelle">' . do_shortcode( $content ) . '</p>';
      }

      /*
       * Absatzklasse question
       */
      function absatzklasse_question($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="notice-question">' . do_shortcode( $content ) . '</p>';
      }

      /*
       * Absatzklasse plus
       */
      function absatzklasse_plus($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="notice-plus">' . do_shortcode( $content ) . '</p>';
      }
      /*
       * Absatzklasse minus
       */
      function absatzklasse_minus($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="notice-minus">' . do_shortcode( $content ) . '</p>';
      }

      /*
       * Absatzklasse tipp
       */
      function absatzklasse_tipp($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="notice-tipp">' . do_shortcode( $content ) . '</p>';
      }
      /*
       * Absatzklasse faubox
       */
      function absatzklasse_faubox($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="notice-faubox">' . do_shortcode( $content ) . '</p>';
      }
       /*
       * Absatzklasse video
       */
      function absatzklasse_video($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="notice-video">' . do_shortcode( $content ) . '</p>';
      }
        /*
       * Absatzklasse audio
       */
      function absatzklasse_audio($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="notice-audio">' . do_shortcode( $content ) . '</p>';
      }
        /*
       * Absatzklasse download
       */
      function absatzklasse_download($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="notice-download">' . do_shortcode( $content ) . '</p>';
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
/*-----------------------------------------------------------------------------------*/
/* Multi Columns Shortcodes
/* Don't forget to add "_last" behind the shortcode if it is the last column.
/*-----------------------------------------------------------------------------------*/

// Two Columns
function fau_shortcode_two_columns_one( $atts, $content = null ) {
    extract(shortcode_atts(array(
	'color'	=> '',
	'lighten'   => '',
    ), $atts));
    $addclass = '';
    if (isset($color)) {

	$setlighten = '';
	if ($lighten) {
	    $setlighten = ' lighten';
	}

	$color = fau_columns_checkcolor($color);
	if (!empty($color)) {
	    $addclass=' '.$color;
	    $addclass .= $setlighten;
	}
    }
    return '<div class="two-columns-one'.$addclass.'">' . do_shortcode( ($content) ) . '</div>';
}

function fau_shortcode_two_columns_one_last( $atts, $content = null ) {
    extract(shortcode_atts(array(
	'color'	=> '',
	'lighten'   => '',
    ), $atts));
    $addclass = '';
    if (isset($color)) {
	$setlighten = '';
	if ($lighten) {
	    $setlighten = ' lighten';
	}

	$color = fau_columns_checkcolor($color);
	if (!empty($color)) {
	    $addclass=' '.$color;
	    $addclass .= $setlighten;
	}
    }
   return '<div class="two-columns-one'.$addclass.' last">' . do_shortcode( ($content) ) . '</div>';
}

// Three Columns
function fau_shortcode_three_columns_one($atts, $content = null) {
    extract(shortcode_atts(array(
	'color'	=> '',
	'lighten'   => '',
    ), $atts));
    $addclass = '';
    if (isset($color)) {
	$setlighten = '';
	if ($lighten) {
	    $setlighten = ' lighten';
	}

	$color = fau_columns_checkcolor($color);
	if (!empty($color)) {
	    $addclass=' '.$color;
	    $addclass .= $setlighten;
	}
    }
    return '<div class="three-columns-one'.$addclass.'">' . do_shortcode( ($content) ) . '</div>';
}

function fau_shortcode_three_columns_one_last($atts, $content = null) {
    extract(shortcode_atts(array(
	'color'	=> '',
	'lighten'   => '',
    ), $atts));
    $addclass = '';
    if (isset($color)) {
	$setlighten = '';
	if ($lighten) {
	    $setlighten = ' lighten';
	}

	$color = fau_columns_checkcolor($color);
	if (!empty($color)) {
	    $addclass=' '.$color;
	    $addclass .= $setlighten;
	}
    }
   return '<div class="three-columns-one'.$addclass.' last">' . do_shortcode( ($content) ) . '</div>';
}




}
new FAUShortcodes();


?>
