<?php
/**
 * Navigation Menu template functions
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

/*-----------------------------------------------------------------------------------*/
/* Register FAU Menus in Theme
/*-----------------------------------------------------------------------------------*/
function fau_register_menus() {   
    global $defaultoptions;
    $website_type = get_theme_mod('website_type');
    if (!isset($website_type)) {
	$website_type = $defaultoptions['website_type'];
    }
    
	
    register_nav_menu( 'meta', __( 'Meta-Navigation oben: Links zu anderen Webauftritten und Portalen', 'fau' ) );
	// Meta Navigation oben im Header

    register_nav_menu( 'meta-footer', __( 'Navigation unten: Kontakt, Impressum und weitere Hinweise zum Webauftritt', 'fau' ) );
	// Meta Navigation unten im Footer

    register_nav_menu( 'main-menu', __( 'Haupt-Navigation', 'fau' ) );
	// Hauptnavigation

    if ($website_type==-1) {
	// Buehnennavigation Template Portal Startseite mit 4 Spalten
	register_nav_menu( 'quicklinks-1', __( 'Startseite FAU Portal: Bühne Spalte 1', 'fau' ) );
	register_nav_menu( 'quicklinks-2', __( 'Startseite FAU Portal: Bühne Spalte 2', 'fau' ) );
	register_nav_menu( 'quicklinks-3', __( 'Startseite FAU Portal: Bühne Spalte 3', 'fau' ) );
	register_nav_menu( 'quicklinks-4', __( 'Startseite FAU Portal: Bühne Spalte 4', 'fau' ) );
    } else {
	 // Buehnennavigation Template Portal Startseite mit 2 Spalten
	register_nav_menu( 'quicklinks-3', __( 'Startseite Fakultät: Bühne Spalte 1', 'fau' ) );
	register_nav_menu( 'quicklinks-4', __( 'Startseite Fakultät: Bühne Spalte 2', 'fau' ) );
    }

    
    register_nav_menu( $defaultoptions['socialmedia_menu_position'], $defaultoptions['socialmedia_menu_position_title'] );
	// Social Media Menu (seit 1.9.5)
    
    register_nav_menu( 'error-1', __( 'Fehler- und Suchseite: Vorschlagmenu Spalte 1', 'fau' ) );
	// Fehler und Suchseite: Vorschlagmenu Spalte 1
    register_nav_menu( 'error-2', __( 'Fehler- und Suchseite: Vorschlagmenu Spalte 2', 'fau' ) );
	// Fehler und Suchseite: Vorschlagmenu Spalte 2
    register_nav_menu( 'error-3', __( 'Fehler- und Suchseite: Vorschlagmenu Spalte 3', 'fau' ) );
	// Fehler und Suchseite: Vorschlagmenu Spalte 3
    register_nav_menu( 'error-4', __( 'Fehler- und Suchseite: Vorschlagmenu Spalte 4', 'fau' ) );
	// Fehler und Suchseite: Vorschlagmenu Spalte 4
	
}
/*-----------------------------------------------------------------------------------*/
/* Erstelle ein Social Media Menu, wenn es noch nicht vorhanden ist
/*-----------------------------------------------------------------------------------*/
function fau_create_socialmedia_menu() {
    global $defaultoptions;
    
    // Zuerst schauen wir, ob das Menu bereits existiert
    $menuitems = wp_get_nav_menu_items($defaultoptions['socialmedia_menu_name']);
    if (!is_array($menuitems)) {
	// Menu existiert noch nicht
	
	
	// Existieren bereits Einträge in der alten Options-Tabelle mit Social Media Angaben, die angezeigt werden sollen?
	// Wenn ja, dann fülle das Menu mit diesen; enn nein, fülle das Menu mit Default-Einträgen

	global $default_socialmedia_liste;
	ksort($default_socialmedia_liste);
	global $options;
	$olditems = false;
	
	$name = $defaultoptions['socialmedia_menu_name'];
	$menu_id = wp_create_nav_menu($name);
	$menu = get_term_by( 'name', $name, 'nav_menu' ); 
	 
	 
	foreach ( $default_socialmedia_liste as $entry => $listdata ) {  
	    $value = '';
	    $active = 0;
	    if (isset($options['sm-list'][$entry]['content'])) {
		$value = esc_url($options['sm-list'][$entry]['content']);
		if (isset($options['sm-list'][$entry]['active'])) {
		    $active = $options['sm-list'][$entry]['active'];
		} 
	    }
	    if (($active ==1) && ($value)) {
		$olditems = true;
		$title = esc_attr($default_socialmedia_liste[$entry]['name']);
		
		wp_update_nav_menu_item($menu->term_id, 0, array(
		    'menu-item-title'	=> $title,
		    'menu-item-url'	=> $value,
		    'menu-item-type'	=> 'custom',
		    'menu-item-status'	=> 'publish')
		);
	    }
	}
	if ($olditems==false) {
	    // Keine aktiven Social Media in dem alten Options vorhanden; Befülle daher Menü mit Defaults
	    foreach ( $default_socialmedia_liste as $entry => $listdata ) {
		$value = esc_url($default_socialmedia_liste[$entry]['content']);
		$active = $default_socialmedia_liste[$entry]['active'];
		$title = esc_attr($default_socialmedia_liste[$entry]['name']);
		
		if (($active ==1) && ($value)) {
		    wp_update_nav_menu_item($menu->term_id, 0, array(
			'menu-item-title'	=> $title,
			'menu-item-url'		=> $value,
			'menu-item-type'	=> 'custom',
			'menu-item-status'	=> 'publish')
		    );
		}
	    }
	}
	
	// Setze Menu nun an die Position
	$pos = $defaultoptions['socialmedia_menu_position']; 
	$locations = get_theme_mod('nav_menu_locations');
	$locations[$pos] = $menu->term_id;
	set_theme_mod( 'nav_menu_locations', $locations );

    }
    
}
/*-----------------------------------------------------------------------------------*/
/* returns child items by parent
/*-----------------------------------------------------------------------------------*/
function add_has_children_to_nav_items( $items ) {
    $parents = wp_list_pluck( $items, 'menu_item_parent');
    $out     = array ();

    foreach ( $items as $item ) {
        in_array( $item->ID, $parents ) && $item->classes[] = 'has-sub';
        $out[] = $item;
    }
    return $items;
}
add_filter( 'wp_nav_menu_objects', 'add_has_children_to_nav_items' );

/*-----------------------------------------------------------------------------------*/
/* get menu title
/*-----------------------------------------------------------------------------------*/
function fau_get_menu_name($location){
	if(!has_nav_menu($location)) return false;
	$menus = get_nav_menu_locations();
	$menu_title = wp_get_nav_menu_object($menus[$location])->name;
	return $menu_title;
}

/*-----------------------------------------------------------------------------------*/
/*remove Menu Item IDs
/*-----------------------------------------------------------------------------------*/
add_filter('nav_menu_item_id', 'clear_nav_menu_item_id', 10, 3);
function clear_nav_menu_item_id($id, $item, $args) {
    return "";
}

/*-----------------------------------------------------------------------------------*/
/* returns top parent id
/*-----------------------------------------------------------------------------------*/
function get_top_parent_page_id($id, $offset = FALSE) {

	$parents = get_post_ancestors( $id );
	if( ! $offset) $offset = 1;
	
	$index = count($parents)-$offset;
	if ($index <0) {
	    $index = count($parents)-1;
	}
	return ($parents) ? $parents[$index]: $id;

}
/*-----------------------------------------------------------------------------------*/
/*Fallback, if no main menu is defined yet
/*-----------------------------------------------------------------------------------*/
function fau_main_menu_fallback() {
    global $defaultoptions;
    $output = '';
    $some_pages = get_pages(array('parent' => 0, 'number' => $defaultoptions['default_mainmenu_number'], 'hierarchical' => 0));
    if($some_pages) {
        foreach($some_pages as $page) {
            $output .= sprintf('<li class="menu-item level1"><a href="%1$s">%2$s</a></li>', get_permalink($page->ID), $page->post_title);
        }
        
        $output = sprintf('<ul role="navigation" aria-label="%1$s" id="nav">%2$s</ul>', __('Hauptnavigation', 'fau'), $output);
    }   
    return $output;
}

/*-----------------------------------------------------------------------------------*/
/* Walker for main menu
/*-----------------------------------------------------------------------------------*/
class Walker_Main_Menu extends Walker_Nav_Menu {
	private $currentID;

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$level = $depth + 2;		
		
		$nothumbnail  = get_post_meta( $this->currentID, 'menuquote_nothumbnail', true );
		if ($nothumbnail==1) {
		    $thumbregion = '';
		} else {
		    $thumbregion = get_the_post_thumbnail($this->currentID,'post');
		}
		$quote  = get_post_meta( $this->currentID, 'zitat_text', true );
	        $author =  get_post_meta( $this->currentID, 'zitat_autor', true );
		
		if (empty($thumbregion) && fau_empty($quote)) {
		    $output .= $indent.'<div class="nav-flyout"><div class="container"><div class="row"><div class="flyout-entries-full"><ul class="sub-menu level'.$level.'">';
		}  else {
		    $output .= $indent.'<div class="nav-flyout"><div class="container"><div class="row"><div class="flyout-entries"><ul class="sub-menu level'.$level.'">';
		}
		
		
	}
	
	function end_lvl( &$output, $depth = 0, $args = array() ) {		
		$indent = str_repeat("\t", $depth);
		$output .= $indent.'</ul>';
		$output .= '<a href="'.get_permalink($this->currentID).'" class="button-portal">';
		$pretitle = get_theme_mod('menu_pretitle_portal');
		if (!fau_empty($pretitle)) {
		    $output .=  $pretitle.' ';
		}
		$output .= get_the_title($this->currentID);
		$posttitle = get_theme_mod('menu_aftertitle_portal');
		if (!fau_empty($posttitle)) {
		    $output .=  ' '.$posttitle;
		}
		$output .= '</a>';
		$output .= '</div>';
			
		$nothumbnail  = get_post_meta( $this->currentID, 'menuquote_nothumbnail', true );	

		if ($nothumbnail==1) {
		    $thumbregion = '';
		} else {
		    $thumbregion = '';
		    $thumbnail_id = get_post_thumbnail_id($this->currentID); 
		    
		    
		    if ($thumbnail_id) {
			$image_attributes = wp_get_attachment_image_src( $thumbnail_id, 'post' );
			
			
			$thumbregion .= '<figure>';
			$thumbregion .= '<img src="'.fau_esc_url($image_attributes[0]).'" width="'.$image_attributes[1].'" height="'.$image_attributes[1].'" alt="">';
				
			$imgdata = fau_get_image_attributs($thumbnail_id);
			$displaycredits = get_theme_mod("advanced_display_portalmenu_thumb_credits");
			$info = "";
			$credits = '';										
			if (isset($imgdata) && ($displaycredits==true)) {
			    $credits = trim(strip_tags(  $imgdata['credits']));  
			   if (!empty($credits)) {
			        $thumbregion .=  '<figcaption>';
				$thumbregion .=  $credits;
				$thumbregion .=  '</figcaption>';
			   }
			}
			
			$thumbregion .= '</figure>';			
		    }
		}
	        $quote  = get_post_meta( $this->currentID, 'zitat_text', true );
	        $author =  get_post_meta( $this->currentID, 'zitat_autor', true );
		$val  = get_post_meta( $this->currentID, 'menuquote_texttype', true );	
		$texttype = ( isset( $val ) ? intval( $val ) : 0 );
		
		if (empty($thumbregion) && fau_empty($quote)) {
		    // keine spalten 
		} else {
		    if (!empty($thumbregion)) {
			$output .= '<div class="hide-mobile introtext">';
			if($quote) {
			    if ($texttype==0) {
				$output .= '<blockquote>';
				$output .= '<p>'.$quote.'</p>';
				if($author) $output .= '<cite>'.$author.'</cite>'; 
				$output .= '</blockquote>';

			    } elseif ($texttype==1) {
				 $output .= '<p>'.$quote.'</p>';
			    }

			}
			$output .= '</div>';
			$output .= '<div class="hide-mobile introthumb">';		
			$output .= $thumbregion;
			$output .= '</div>';	
		    } else {
			$output .= '<div class="hide-mobile introtext-full">';

			 if($quote) {
			    if ($texttype==0) {
				$output .= '<blockquote>';
				$output .= '<p>'.$quote.'</p>';
				 if($author) $output .= '<cite>'.$author.'</cite>';
				$output .= '</blockquote>';

			    } elseif ($texttype==1) {
				 $output .= '<p>'.$quote.'</p>';
			    }

			}
			$output .= '</div>';	

		    }
		}
		$output .= '</div></div></div>';
	}
	
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$level = $depth + 1;

		$class_names = $value = '';
		$force_cleanmenu = 0;

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		if ($level<2) {
		    $classes[] = 'menu-item-' . $item->ID;
		    $classes[] = 'level' . $level;
		}	
		$rellink = fau_make_link_relative($item->url);
		if (substr($rellink,0,4) == 'http') {
		    // absoluter Link auf externe Seite
		    $classes[] = 'external';
		    $force_cleanmenu= 1;
		} elseif ($rellink == '/') {
		    // Link auf Startseite
		    $classes[] = 'homelink';
		    $force_cleanmenu = 2;
		}
		$forceclean_externlink = get_theme_mod('advanced_forceclean_externlink');
		$forceclean_homelink =    get_theme_mod('advanced_forceclean_homelink');
		if (($forceclean_homelink==true) && ($force_cleanmenu==2)) {
		    // Ignore homelink
		} elseif (($forceclean_externlink==true) && ($force_cleanmenu==1)) {    
		    // Ignore external link in Main menu
		} else {
		   

		 //   $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		 //   $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
	    
		    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		    $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		    
		    $iscurrent =0;
		    if (in_array("current_page_item",$item->classes)) {
			$iscurrent = 1;
		    }
		    if ($level>1) {
			 $class_names = str_replace("has-sub","",$class_names);   
		    }
		    $output .= $indent . '<li' . $value . $class_names .'>';
		    
		    $atts = array();
		    $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		    $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		    $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		    $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
		    if ($iscurrent==1) {
			$atts['aria-current'] = "page";
		    }
		    $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		    if($level == 1) $this->currentID = $item->object_id;		

		    $attributes = '';
		    foreach ( $atts as $attr => $value ) {
			    if ( ! empty( $value ) ) {
				    $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				    $attributes .= ' ' . $attr . '="' . $value . '"';
			    }
		    }
		    

		    $item_output = $args->before;
		    $item_output .= '<a'. $attributes .'>';
		    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		    $item_output .= '</a>';
		    $item_output .= $args->after;

		    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}
	function end_el(&$output, $item, $depth=0, $args=array()) {      
	    $rellink = fau_make_link_relative($item->url);
	    $force_cleanmenu = 0;
	    if (substr($rellink,0,4) == 'http') {
		    // absoluter Link auf externe Seite
		    $force_cleanmenu= 1;
	    } elseif ($rellink == '/') {
		// Link auf Startseite
		$force_cleanmenu = 2;
	    }
	    $forceclean_externlink = get_theme_mod('advanced_forceclean_externlink');
	    $forceclean_homelink =    get_theme_mod('advanced_forceclean_homelink');
	    if (($forceclean_homelink==true) && ($force_cleanmenu==2)) {
		// Ignore homelink
	    } elseif (($forceclean_externlink==true) && ($force_cleanmenu==1)) {    
		// Ignore external link in Main menu
	    } else {
		 $output .= "</li>";
	    }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Create submenu icon/grid in content
/*-----------------------------------------------------------------------------------*/
function fau_get_contentmenu($menu, $submenu = 1, $subentries =0, $nothumbs = 0, $nodefthumbs = 0) {   
    
    if (empty($menu)) {
	echo '<!-- no id and empty slug for menu -->';
	return;
    }
    if ($menu == sanitize_key($menu)) {
	$term = get_term_by('id', $menu, 'nav_menu');
    } else {
	$term = get_term_by('name', $menu, 'nav_menu');
    }
    if ($term===false) {
	echo '<!-- invalid menu -->';
	return;   
    }
    $slug = $term->slug;     
    
    if ($subentries==0) {
	$subentries =  get_theme_mod('default_submenu_entries');
    }

    echo '<div class="contentmenu" role="navigation" aria-label="'.__('Inhaltsmenu','fau').'">';   
    echo '<ul class="subpages-menu">';
    wp_nav_menu( array( 'menu' => $slug, 
        'container' => false, 
        'items_wrap' => '%3$s', 
        'link_before' => '', 
        'link_after' => '', 
        'walker' => new Walker_Content_Menu($menu,$submenu,$subentries,$nothumbs,$nodefthumbs)));
    echo '</ul>';
    echo "</div>\n";

    return;
}

/*-----------------------------------------------------------------------------------*/
/* Walker for subnav
/*-----------------------------------------------------------------------------------*/
class Walker_Content_Menu extends Walker_Nav_Menu {
	private $level = 1;
	private $count = array();
	private $element;
	private $showsub = 1;
	
	
	function __construct($menu,$showsub=1,$maxsecondlevel=6,$noshowthumb=0,$nothumbnailfallback=0) {	   
	    $this->showsub              = $showsub;
	    $this->maxsecondlevel       = $maxsecondlevel;
	    $this->nothumbnail          = $noshowthumb;
	    $this->nothumbnailfallback  = $nothumbnailfallback;
        }
        function __destruct( ) {
		// $output .= '</ul> <!-- destruct -->';
	}
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$this->level++;
		$this->count[$this->level] = 0;
                if ($this->level == 2 && $this->count[$this->level] <= $this->maxsecondlevel && $this->showsub == 1)
                    $output .= '<ul class="sub-menu">';         
	}
	
	function end_lvl( &$output, $depth = 0, $args = array() ) {		
               if ($this->level == 2 && $this->count[$this->level] <= $this->maxsecondlevel && $this->showsub == 1) {
                    $output .= '</ul>';
               } elseif(($this->level == 2) && ($this->count[$this->level] == ($this->maxsecondlevel+1)) && ($this->showsub == 1)) {
                    $output .= '<li class="more"><a href="'.$this->element->url.'">'. __('Mehr', 'fau').' ...</a></li>';
		    $output .= '</ul>';
		   
               } elseif (($this->level == 2)  && ($this->showsub == 1)) {
                    $output .= '</ul>';
               }
              
                $this->level--;
	}
	
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
	    global $options;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		if (isset($this->count[$this->level])) {   
		    $this->count[$this->level]++;
		} else {
		     $this->count[$this->level] =1;
		}
		
		if($this->level == 1) {
		    $this->element = $item;
		}
		$item_output = '';
		// Only show elements on the first level and only five on the second level, but only if showdescription == FALSE
		if($this->level == 1 || 
                        ($this->level == 2 && $this->count[$this->level] <= $this->maxsecondlevel && $this->showsub == 1)) {
			$class_names = $value = '';
			$externlink = false;
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;
	//		if($this->level == 1 && (isset($this->count[$this->level])) && (($this->count[$this->level]-1) % $this->maxspalten==0) ) {
	//		    $classes[] = 'clear';
	//		}    
			if($this->level == 1) {
			    $classes[] = 'menubox';
			}

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
			$iscurrent =0;
			if (in_array("current_page_item",$item->classes)) {
			    $iscurrent = 1;
			}
	//		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
	//		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			if($this->level == 1) {
				$output .= $indent . '<li' . $class_names .'>';
			} else	{
				$output .= '<li>';
			}

			$atts = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
			$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
			if ($iscurrent==1) {
			    $atts['aria-current'] = "page";
			}
		
			$post = get_post($item->object_id);
			if ($post && $post->post_type != 'imagelink') {
			    $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
			    $targeturl = $atts['href'];
			} else {
			    $targeturl = '';
			}

			if($this->level == 1) $atts['class'] = 'subpage-item';
			
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			
			$item_output = $args->before;
			if($post && $post->post_type == 'imagelink') {
                            $protocol  = get_post_meta( $item->object_id, 'protocol', true );
                            $link  = get_post_meta( $item->object_id, 'link', true );
                            $targeturl = get_post_meta( $item->object_id, 'fauval_imagelink_url', true );

                            if (empty($targeturl) && isset($protocol) && isset($link)) {
                                $targeturl = $protocol.$link;
                            }
                            $externlink = true; 	
                            $link = '<a class="ext-link" data-wpel-link="internal"  href="'.$targeturl.'">';
			} else {
                            $link = '<a'. $attributes .' >';
			}

			if($this->level == 1) {
                            if (!$this->nothumbnail) {
				$item_output .= '<div role="presentation" aria-hidden="true" tabindex="-1">';
                                $item_output .= '<a ';

                                if ($externlink) {
                                     $item_output .= 'data-wpel-link="internal" ';
                                }
                                $item_output .= 'class="image';
                                if ($externlink) {
                                     $item_output .= ' ext-link';
                                }
                                $item_output .= '" href="'.$targeturl.'">';
                                $post_thumbnail_id = get_post_thumbnail_id( $item->object_id, 'page-thumb' ); 
                                $imagehtml = '';
                                $imageurl = '';
				
				
				$pretitle = $options['advanced_contentmenu_thumblink_alt_pretitle'];
				$posttitle = $options['advanced_contentmenu_thumblink_alt_posttitle'];
				$alttext = $pretitle.apply_filters( 'the_title', $item->title, $item->ID ).$posttitle;
				$alttext = esc_html($alttext);
				$altattr = 'alt="'.$alttext.'"';
				
				
                                if ($post_thumbnail_id) {
                                    $thisimage = wp_get_attachment_image_src( $post_thumbnail_id,  'page-thumb');
                                    $imageurl = $thisimage[0]; 	
                                    $item_output .= '<img src="'.fau_esc_url($imageurl).'" width="'.$thisimage[1].'" height="'.$thisimage[2].'" '.$altattr.'>';
                                }
                                if ((!isset($imageurl) || (strlen(trim($imageurl)) <4 )) && (!$this->nothumbnailfallback))  {
                                    $imageurl = $options['default_submenuthumb_src'];
                                    $item_output .= '<img src="'.fau_esc_url($imageurl).'" width="'.$options['default_submenuthumb_width'].'" height="'.$options['default_submenuthumb_height'].'" '.$altattr.'>';
                                }
                                $item_output .= '</a>';
				$item_output .= '</div>';
                                // Anmerkung: Leeres alt="", da dieses ansonsten redundant wäre zum darunter stehenden Titel.
                            }
                            $item_output .= $args->link_before.'<span class="portaltop">';
                            $item_output .= $link;
                            $item_output .=  apply_filters( 'the_title', $item->title, $item->ID );
                            $item_output .= '</a>';
                            $item_output .= '</span>'. $args->link_after;
			} else {
				$item_output .= $link;
				$item_output .= $args->link_before.apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				$item_output .= '</a>';
			}

			
			$item_output .= $args->after;
			
			
			if(!($this->showsub==1) && ($this->level == 1)) {
			     $desc  = get_post_meta( $item->object_id, 'portal_description', true );
			     // Wird bei Bildlink definiert
			     if ($desc) {
				$item_output .= '<p>'.$desc.'</p>';
			     }	
			}
		}
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	
	function end_el(&$output, $item, $depth=0, $args=array()) {      
	    
	    if($this->level == 1 || 
                        ($this->level == 2 && $this->count[$this->level] <= $this->maxsecondlevel && $this->showsub == 1)) {
		 $output .= "</li>"; 
	  
	    }

	}     
}



/*-----------------------------------------------------------------------------------*/
/* Create breadcrumb
/*-----------------------------------------------------------------------------------*/
function fau_breadcrumb($lasttitle = '') {
  global $defaultoptions;
  
  $delimiter	= $defaultoptions['breadcrumb_delimiter']; // = ' / ';
  $home		= $defaultoptions['breadcrumb_root']; // __( 'Startseite', 'fau' ); // text for the 'Home' link
  $before	= $defaultoptions['breadcrumb_beforehtml']; // '<span class="current">'; // tag before the current crumb
  $after	= $defaultoptions['breadcrumb_afterhtml']; // '</span>'; // tag after the current crumb
  $showcurrent	= $defaultoptions['breadcrumb_showcurrent'];
  
  $pretitletextstart   = '<span>';
  $pretitletextend     = '</span>';
  

  echo '<nav aria-labelledby="bc-title" class="breadcrumbs">'; 
  echo '<h2 class="screen-reader-text" id="bc-title">'.__('Breadcrumb','fau').'</h2>';
    if (get_theme_mod('breadcrumb_withtitle')) {
	echo '<p class="breadcrumb_sitetitle" role="presentation">'.get_bloginfo( 'title' ).'</p>';
	echo "\n";
    }

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
	    echo '<a href="' . $homeLink . '/' . $slug['slug'] . '">' . $post_type->labels->singular_name . '</a>' .$delimiter;
	    if ($showcurrent) {
		echo $before . get_the_title() . $after;
	    }
	} else {
	    
	    $cat = get_the_category(); 
	    if (get_theme_mod('breadcrumb_uselastcat')) {
		$last = array_pop($cat);
	    } else {
		$last = $cat[0];
	    }
	    $catid = $last->cat_ID;

	    echo get_category_parents($catid, TRUE,  $delimiter );
	    if ($showcurrent) {
		echo $before . get_the_title() . $after;
	    }

	} 
    } elseif ( !is_single() && !is_page() && !is_search() && get_post_type() != 'post' && !is_404() ) {
	$post_type = get_post_type_object(get_post_type());
	echo $before . $post_type->labels->singular_name . $after;
    } elseif ( is_attachment() ) {
	$parent = get_post($post->post_parent);
	echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>'. $delimiter;
	if ($showcurrent) {
	    echo $before . get_the_title() . $after;
	}
	
    } elseif ( is_page() && !$post->post_parent ) {
	if ($showcurrent) {
	    echo $before . get_the_title() . $after;
	}
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
	if ($showcurrent) {
	    echo $before . get_the_title() . $after; 
	}
    } elseif ( is_search() ) {

	    $searchstring = esc_attr(get_search_query());
	    if (!fau_empty($searchstring)) {
		 echo $before .$pretitletextstart. __( 'Suche nach', 'fau' ).$pretitletextend.' "' . $searchstring . '"' . $after; 
	    } else {
		 echo $before .$pretitletextstart. __( 'Suche', 'fau' ).$pretitletextend. $after; 
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
/*-----------------------------------------------------------------------------------*/
/* Create Social Media Menu
/*-----------------------------------------------------------------------------------*/
function fau_get_socialmedia_menu($name = '', $ulclass = '', $withog = true) {
    
    if (!isset($name)) {
	return;
    }
    $menu = wp_get_nav_menu_object($name);
    $thislist = '';
    if (isset($menu)) {
	    $thislist = '<ul';
	    if ($ulclass) {
		$thislist .= ' class="'.$ulclass.'"';
	    }
	    $thislist .= '>';
	    
		$menu_items = wp_get_nav_menu_items($menu->term_id);
		foreach ( (array) $menu_items as $key => $menu_item ) {
		    $title = esc_attr($menu_item->title);
		    $url = esc_url($menu_item->url);
		    $class_names = '';
		    $social = fau_sanitize_socialmedia_classes($title);
		    if ($social) {
			$class_names = 'social-'.$social;
			$thislist .= '<li class="'.$class_names.'">';
		    } else {
			$thislist .= '<li class="social-iconbyurl">';
		    }
		    
		    $thislist .= '<a data-wpel-link="internal" ';
		    if ($withog) {
			 $thislist .= 'itemprop="sameAs" ';
		    }
		    $thislist .= 'href="' . $url . '">' . $title . '</a></li>';
		}
	 $thislist .= '</ul>';	
	   
    }
    return $thislist;
}

/*-----------------------------------------------------------------------------------*/
/* EOF menu.php
/*-----------------------------------------------------------------------------------*/