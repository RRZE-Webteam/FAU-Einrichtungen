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
		add_shortcode('hr', array( $this, 'fau_hr'));
		
		add_shortcode('alert', array( $this, 'absatzklasse_attention' ));
		add_shortcode('attention', array( $this, 'absatzklasse_attention' ));
		add_shortcode('hinweis', array( $this, 'absatzklasse_hinweis' ));
		add_shortcode('baustelle', array( $this, 'absatzklasse_baustelle' ));
		add_shortcode('plus', array( $this, 'absatzklasse_plus' ));
		add_shortcode('minus', array( $this, 'absatzklasse_minus' ));
		add_shortcode('question', array( $this, 'absatzklasse_question' ));	
		
	}
	
	function fau_hr ( $atts, $content = null) {
	    extract(shortcode_atts(array(
			"size" => ''
			), $atts));
	    
	    $return = '<hr';
	    $return .= ($size) ? ' class="' . $size. '"' : '';
	    $return .= '>';
	    
	    return $return;
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
		    $return .= '<div class="accordion-group">';
			$return .= '<div class="accordion-heading">';
			    $return .= '<a class="accordion-toggle" data-toggle="collapse" data-parent="accordion-" href="#collapse_'.$page->ID.'000'.$i.'">'.$page->post_title.'</a>';
			$return .= '</div>';
			$return .= '<div id="collapse_'.$page->ID.'000'.$i.'" class="accordion-body">';
    			    $return .= '<div class="accordion-inner clearfix">';

					$subpages = get_pages(array('sort_order' => 'ASC', 'sort_column' => 'menu_order', 'parent' => $page->ID, 'hierarchical' => 0));

					if(count($subpages) > 0)  {
					    $return .= '<div class="assistant-tabs">';

						$return .= '<ul class="assistant-tabs-nav">';

						$j = 0;
						foreach($subpages as $subpage) {
							if($j == 0) $class = 'active';
							else $class = '';

							$return .= '<li><a href="#accordion-'.$page->ID.'-'.$i.'-tab-'.$j.'" class="accordion-tabs-nav-toggle '.$class.'">'.$subpage->post_title.'</a></li>';
							$j++;
						}

						$return .= '</ul>';

						$j = 0;
						foreach($subpages as $subpage) {
							if($j == 0) $class = 'assistant-tab-pane-active';
							else $class = '';

							$return .= '<div class="assistant-tab-pane '.$class.'" id="accordion-'.$page->ID.'-'.$i.'-tab-'.$j.'">';
								$return .= '<p>'.do_shortcode($subpage->post_content).'</p>';
							$return .= '</div>';

							$j++;
						}

					    $return .= '</div>';
					}  else {
						$return .= '<p>'.do_shortcode($page->post_content).'</p>';
					}


				    $return .= '</div>';
			    $return .= '</div>';
		    $return .= '</div>';

		    $i++;
		}
		
		
		$return .= '</div>';
		
		return $return;
	}
	
	
    /*
     * Absatzklasse attention
     */
      function absatzklasse_attention($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="attention">' . do_shortcode( $content ) . '</p>';
      }

      /*
       * Absatzklasse hinweis
       */
      function absatzklasse_hinweis($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="hinweis">' . do_shortcode( $content ) . '</p>';
      }

      /*
       * Absatzklasse baustelle
       */
      function absatzklasse_baustelle($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="baustelle">' . do_shortcode( $content ) . '</p>';
      }  

      /*
       * Absatzklasse question
       */
      function absatzklasse_question($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="question">' . do_shortcode( $content ) . '</p>';
      }  

      /*
       * Absatzklasse plus
       */
      function absatzklasse_plus($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="plus">' . do_shortcode( $content ) . '</p>';
      }  
      /*
       * Absatzklasse minus
       */
      function absatzklasse_minus($atts, $content = null) {
	 extract(shortcode_atts(array(), $atts));
	 return '<p class="minus">' . do_shortcode( $content ) . '</p>';
      }    

	
	
}
new FAUShortcodes();



class FAUShortcodesRTE
{
    public function __construct()  {
        add_action('admin_init', array($this, 'fau_shortcodes_rte_button'));
    }

    public function fau_shortcodes_rte_button()  {
        if( current_user_can('edit_posts') &&  current_user_can('edit_pages') ) {
            add_filter( 'mce_external_plugins', array($this, 'fau_rte_add_buttons' ));
            add_filter( 'mce_buttons', array($this, 'fau_rte_register_buttons' ));
        }
    }

    public function fau_rte_add_buttons( $plugin_array ) {
        $plugin_array['faurteshortcodes'] = get_template_directory_uri().'/js/tinymce-shortcodes.js';

        return $plugin_array;
    }

    public function fau_rte_register_buttons( $buttons )  {
        array_push( $buttons, 'separator', 'faurteshortcodes' );
        return $buttons;
    }

}

new FAUShortcodesRTE();



class BoostrapShortcodes {

  function __construct() {
    add_action( 'init', array( $this, 'add_shortcodes' ) ); 
  }


  /*--------------------------------------------------------------------------------------
    *
    * add_shortcodes
    *
    * @author Filip Stefansson
    * @since 1.0
    * 
    *-------------------------------------------------------------------------------------*/
  function add_shortcodes() {

    add_shortcode('button', array( $this, 'bs_button' ));    
    add_shortcode('code', array( $this, 'bs_code' ));
    add_shortcode('span', array( $this, 'bs_span' ));
    add_shortcode('row', array( $this, 'bs_row' ));

    add_shortcode('table', array( $this, 'bs_table' ));
    
    
    // Accordion
    add_shortcode('collapsibles', array( $this, 'bs_collapsibles' ));
    add_shortcode('accordion', array( $this, 'bs_collapsibles' ));
    add_shortcode('accordionsub', array( $this, 'bs_collapsibles' ));
	// Define more as one shortcode name to allow nestet accordions
    
    add_shortcode('collapse', array( $this, 'bs_collapse' ));
    add_shortcode('accordion-item', array( $this, 'bs_collapse' ));
	// Define more as one shortcode name to allow nestet accordions
    

    

    
  }



  /*--------------------------------------------------------------------------------------
    *
    * bs_button
    *
    * @author Filip Stefansson
    * @since 1.0
    * //DW mod added xclass var
    *-------------------------------------------------------------------------------------*/
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
  


  /*--------------------------------------------------------------------------------------
    *
    * bs_code
    *
    * @author Filip Stefansson
    * @since 1.0
    * 
    *-------------------------------------------------------------------------------------*/
  function bs_code($atts, $content = null) {
     extract(shortcode_atts(array(
        "type" => '',
        "size" => '',
        "link" => ''
     ), $atts));
     return '<pre><code>' . $content . '</code></pre>';
  }
  



  /*--------------------------------------------------------------------------------------
    *
    * bs_span
    *
    * @author Filip Stefansson
    * @since 1.0
    * 
    *-------------------------------------------------------------------------------------*/
  function bs_span( $atts, $content = null ) {
    extract(shortcode_atts(array(
      "size" => 'size'
    ), $atts));

    return '<div class="span' . $size . '">' . do_shortcode( $content ) . '</div>';

  }

  


  /*--------------------------------------------------------------------------------------
    *
    * bs_row
    *
    * @author Filip Stefansson
    * @since 1.0
    * 
    *-------------------------------------------------------------------------------------*/
  function bs_row( $atts, $content = null ) {
    
    return '<div class="row">' . do_shortcode( $content ) . '</div>';

  }
  
  
  /*--------------------------------------------------------------------------------------
    *
    * simple_table
    *
    * @author Filip Stefansson
    * @since 1.0
    * 
    *-------------------------------------------------------------------------------------*/
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
  





  /*--------------------------------------------------------------------------------------
    *
    * bs_collapsibles
    *
    * @author Filip Stefansson
    * @since 1.0
    * 
    *-------------------------------------------------------------------------------------*/
  function bs_collapsibles( $atts, $content = null ) {
    
    if( isset($GLOBALS['collapsibles_count']) )
      $GLOBALS['collapsibles_count']++;
    else
      $GLOBALS['collapsibles_count'] = 0;

    $defaults = array();
    extract( shortcode_atts( $defaults, $atts ) );
    
    // Extract the tab titles for use in the tab widget.
 //   preg_match_all( '/collapse title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );
    
//    $tab_titles = array();
//    if( isset($matches[1]) ){ $tab_titles = $matches[1]; }
    
    $output = '';
    
   // if( count($tab_titles) ){
      $output .= '<div class="accordion" id="accordion-' . $GLOBALS['collapsibles_count'] . '">';
      $output .= do_shortcode( $content );
      $output .= '</div>';
   // } else {
   //   $output .= do_shortcode( $content );
  //  }
    
    return $output;
  }
  



  /*--------------------------------------------------------------------------------------
    *
    * bs_collapse
    *
    * @author Filip Stefansson
    * @since 1.0
    * 
    *-------------------------------------------------------------------------------------*/
  function bs_collapse( $atts, $content = null ) {

    if( !isset($GLOBALS['current_collapse']) )
      $GLOBALS['current_collapse'] = 0;
    else 
      $GLOBALS['current_collapse']++;


    $defaults = array( 'title' => 'Tab', 'state' => '', 'color' => '', 'id' => '');
    extract( shortcode_atts( $defaults, $atts ) );
    
    if (!empty($state)) 
      $state = 'in';


    $color = $color ? ' ' . esc_attr( $color ) : '';
    $id = intval($id) ? intval($id) : 0;
    if ($id<1) {
	$id = $GLOBALS['current_collapse'];
    }
    
    $output = '<div class="accordion-group'.$color.'">';
    $output .= '<div class="accordion-heading"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-' . $GLOBALS['collapsibles_count'] . '" href="#collapse_' . $id .'">' . $title . '</a></div>'."\n";
    $output .= '<div id="collapse_' . $id . '" class="accordion-body ' . $state . '">';
    $output .= '<div class="accordion-inner clearfix">'."\n";
    $output .= do_shortcode($content);
    $output .= '</div>';
    $output .= '</div></div>';
    
    
    
    return $output;
  }

}

new BoostrapShortcodes()

?>
