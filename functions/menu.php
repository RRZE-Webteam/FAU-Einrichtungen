<?php
/**
 * Navigation Menu template functions
 *
 * @package    WordPress
 * @subpackage FAU
 * @since      FAU 1.0
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


    register_nav_menu('meta', __('Meta-Navigation oben: Links zu anderen Webauftritten und Portalen', 'fau'));
    // Meta Navigation oben im Header

    register_nav_menu('meta-footer',
        __('Navigation unten: Kontakt, Impressum und weitere Hinweise zum Webauftritt', 'fau'));
    // Meta Navigation unten im Footer

    register_nav_menu('main-menu', __('Hauptmenü', 'fau'));
    // Hauptnavigation


    register_nav_menu($defaultoptions['socialmedia_menu_position'], $defaultoptions['socialmedia_menu_position_title']);
    // Social Media Menu (seit 1.9.5)

    register_nav_menu('error-helper', __('Fehler- und Suchseite: Vorschlagmenu', 'fau'));
    // Fehler und Suchseite
    // Mit V2. nur noch ein Menü, welches als Portalmenu angezeigt wird.
    

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
        // Wenn ja, dann fülle das Menu mit diesen; wenn nein, fülle das Menu mit Default-Einträgen

        global $default_socialmedia_liste;
        ksort($default_socialmedia_liste);
        global $options;
        $olditems = false;

        $name    = $defaultoptions['socialmedia_menu_name'];
        $menu_id = wp_create_nav_menu($name);
        $menu    = get_term_by('name', $name, 'nav_menu');


        foreach ($default_socialmedia_liste as $entry => $listdata) {
            $value  = '';
            $active = 0;
            if (isset($options['sm-list'][$entry]['content'])) {
                $value = esc_url($options['sm-list'][$entry]['content']);
                if (isset($options['sm-list'][$entry]['active'])) {
                    $active = $options['sm-list'][$entry]['active'];
                }
            }
            if (($active == 1) && ($value)) {
                $olditems = true;
                $title    = esc_attr($default_socialmedia_liste[$entry]['name']);

                wp_update_nav_menu_item($menu->term_id, 0, array(
                        'menu-item-title'  => $title,
                        'menu-item-url'    => $value,
                        'menu-item-type'   => 'custom',
                        'menu-item-status' => 'publish'
                    )
                );
            }
        }
        if ($olditems == false) {
            // Keine aktiven Social Media in dem alten Options vorhanden; Befülle daher Menü mit Defaults
            foreach ($default_socialmedia_liste as $entry => $listdata) {
                $value  = esc_url($default_socialmedia_liste[$entry]['content']);
                $active = $default_socialmedia_liste[$entry]['active'];
                $title  = esc_attr($default_socialmedia_liste[$entry]['name']);

                if (($active == 1) && ($value)) {
                    wp_update_nav_menu_item($menu->term_id, 0, array(
                            'menu-item-title'  => $title,
                            'menu-item-url'    => $value,
                            'menu-item-type'   => 'custom',
                            'menu-item-status' => 'publish'
                        )
                    );
                }
            }
        }

        // Setze Menu nun an die Position
        $pos             = $defaultoptions['socialmedia_menu_position'];
        $locations       = get_theme_mod('nav_menu_locations');
        $locations[$pos] = $menu->term_id;
        set_theme_mod('nav_menu_locations', $locations);

    }

}

/*-----------------------------------------------------------------------------------*/
/* returns child items by parent
/*-----------------------------------------------------------------------------------*/
function add_has_children_to_nav_items($items) {
    $parents = wp_list_pluck($items, 'menu_item_parent');
    $out     = array();

    foreach ($items as $item) {
        in_array($item->ID, $parents) && $item->classes[] = 'has-sub';
        $out[] = $item;
    }

    return $items;
}

add_filter('wp_nav_menu_objects', 'add_has_children_to_nav_items');

/*-----------------------------------------------------------------------------------*/
/* get menu title
/*-----------------------------------------------------------------------------------*/
function fau_get_menu_name($location) {
    if (!has_nav_menu($location)) {
        return false;
    }
    $menus      = get_nav_menu_locations();
    $menu_title = wp_get_nav_menu_object($menus[$location])->name;

    return $menu_title;
}

/*-----------------------------------------------------------------------------------*/
/*remove Menu Item IDs
/*-----------------------------------------------------------------------------------*/
function clear_nav_menu_item_id($id, $item, $args) {
    return "";
}
add_filter('nav_menu_item_id', 'clear_nav_menu_item_id', 10, 3);

/*-----------------------------------------------------------------------------------*/
/* returns top parent id
/*-----------------------------------------------------------------------------------*/
function get_top_parent_page_id($id, $offset = false) {

    $parents = get_post_ancestors($id);
    if (!$offset) {
        $offset = 1;
    }

    $index = count($parents) - $offset;
    if ($index < 0) {
        $index = count($parents) - 1;
    }

    return ($parents) ? $parents[$index] : $id;

}


/*-----------------------------------------------------------------------------------*/
/* Walker for main menu 
/*-----------------------------------------------------------------------------------*/

class Walker_Main_Menu_Plainview extends Walker_Nav_Menu {
    private $currentID;
    private $level = 1;
    private $count = array();
    private $element;

    function start_lvl(&$output, $depth = 0, $args = array())  {
        $this->level++;
    
        $this->count[$this->level] = 0;

        $child_count = 0;
        $children = get_posts(array(
            'post_type' => 'nav_menu_item',
            'nopaging' => true,
            'numberposts' => -1,
            'meta_key' => '_menu_item_menu_item_parent',
            'meta_value' => $this->element->ID,
            'order' => 'ASC',
            'orderby' => 'menu_order',
        ));
        if (!empty($children)) {
            foreach ($children as $child) {
                $grand_children = get_posts(array(
                    'post_type' => 'nav_menu_item',
                    'nopaging' => true,
                    'numberposts' => -1,
                    'meta_key' => '_menu_item_menu_item_parent',
                    'meta_value' => $child->ID,
                    'order' => 'ASC',
                    'orderby' => 'menu_order',
                ));
                $child_count += count($grand_children) + 1; // add 1 for the child itself
            }
        }
        $child_count_html = '<span class="child-count">' . $child_count . '</span>';

        if ($this->level == 2) {
            $columnclass='';

            if ($child_count<8){
                $columnclass='1';
            }else   if($child_count<16){
                $columnclass='2';
            }else if($child_count<=24){
                $columnclass='3';
            }
            else if($child_count>24){
                $columnclass='4';
            }
        
            $output .= '<div class="nav-flyout"><div class="container"><div class="row">';
            $output .= '<div class="flyout-entries-full column-count-'. $columnclass.'">';
            
           
           
        }


        $output .= '<ul class="sub-menu level'.$this->level.'">';
        
        
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        if ($this->level == 2) {
            $output       .= '</ul>';
            $currenttitle = fau_get_the_title($this->currentID);
            if (!empty($currenttitle)) {
                $output   .= '<a href="'.get_permalink($this->currentID).'" class="button-portal">';
                $pretitle = get_theme_mod('menu_pretitle_portal');
                if (!fau_empty($pretitle)) {
                    $output .= $pretitle.' ';
                }
                $output    .= $currenttitle;
                $posttitle = get_theme_mod('menu_aftertitle_portal');
                if (!fau_empty($posttitle)) {
                    $output .= ' '.$posttitle;
                }
                $output .= '</a>';
       
            }
            $output .= '</div>';
            $output .= '</div></div></div>';

        } else {
            $output .= '</ul>';
        }
        $this->level--;
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $level       = $this->level;
        $iscurrent   = 0;
        $class_names = $value = '';

        // Generate Classes. Dont use WordPress default, cause i dont want to
        // get all those unused data filled up my html
        
        $classes = array();
          if ($level < 2) {
            
            //	$classes[] = 'menu-item-' . $item->ID;
            $classes[] = 'level'.$level;
        }
        if (in_array("menu-item-has-children", $item->classes)) {
            $classes[] = 'has-sub';
        }
        if (in_array("current-menu-item", $item->classes)) {
            $classes[] = 'current-menu-item';
        }
        if (in_array("current-menu-parent", $item->classes)) {
            $classes[] = 'current-menu-parent';
        }
        if (in_array("current-page-item", $item->classes)) {
            $iscurrent = 1;
            $classes[] = 'current-page-item';
        }
        $rellink = fau_make_link_relative($item->url);
        if (substr($rellink, 0, 4) == 'http') {
            // absoluter Link auf externe Seite
            $classes[] = 'external';
        }
        $this->element = $item; // Store the current element to be used in start_lvl


        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="'.esc_attr($class_names).'"' : '';
        


        $output .= '<li'.$value.$class_names.'>';

        $atts           = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';
    
        $item_classes  = empty($item->classes) ? array() : (array)$item->classes;
        $item_classes  = fau_cleanup_menuclasses($item_classes);
        $item_class    = implode(' ', $item_classes);
        $atts['class'] = !empty($item_class) ? $item_class : '';
    
    
        if ($iscurrent == 1) {
            $atts['aria-current'] = "page";
        }
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);
    
        if ($this->level == 1) {
            $this->currentID = $item->object_id;
        }
    
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value      = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' '.$attr.'="'.$value.'"';
            }
        }
    
        // Add child count for level one and two
        if ($level <= 1 && isset($args->walker)) {
            $child_count = 0;
            $children = get_posts(array(
                'post_type' => 'nav_menu_item',
                'nopaging' => true,
                'numberposts' => -1,
                'meta_key' => '_menu_item_menu_item_parent',
                'meta_value' => $item->ID,
                'order' => 'ASC',
                'orderby' => 'menu_order',
            ));
            if (!empty($children)) {
                foreach ($children as $child) {
                    $grand_children = get_posts(array(
                        'post_type' => 'nav_menu_item',
                        'nopaging' => true,
                        'numberposts' => -1,
                        'meta_key' => '_menu_item_menu_item_parent',
                        'meta_value' => $child->ID,
                        'order' => 'ASC',
                        'orderby' => 'menu_order',
                    ));
                    $child_count += count($grand_children) + 1; // add 1 for the child itself
                }
            }
            $child_count_html = '<span class="child-count">' . $child_count . '</span>';
        } else {
            $child_count_html = '';
        }
        
    
        $item_output = $args->before;
        $item_output .= '<a'.$attributes.'>';
        $item_output .= $args->link_before.apply_filters('the_title', $item->title, $item->ID).$args->link_after; // Append child count
        $item_output .= '</a>';
        $item_output .= $args->after;
    
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    function end_el(&$output, $item, $depth = 0, $args = array())  {
        $output .= "</li>";
    }
}





/*-----------------------------------------------------------------------------------*/
/* Walker for main menu  SMALL
/*-----------------------------------------------------------------------------------*/

class Walker_Main_Menu_Plainview_Small extends Walker_Nav_Menu {
    private $currentID;
    private $level = 1;
    private $count = array();
    private $element;

    function start_lvl(&$output, $depth = 0, $args = array())  {
        $this->level++;
    
        $this->count[$this->level] = 0;

        $child_count = 0;
        $children = get_posts(array(
            'post_type' => 'nav_menu_item',
            'nopaging' => true,
            'numberposts' => -1,
            'meta_key' => '_menu_item_menu_item_parent',
            'meta_value' => $this->element->ID,
            'order' => 'ASC',
            'orderby' => 'menu_order',
        ));
        if (!empty($children)) {
            foreach ($children as $child) {
                $grand_children = get_posts(array(
                    'post_type' => 'nav_menu_item',
                    'nopaging' => true,
                    'numberposts' => -1,
                    'meta_key' => '_menu_item_menu_item_parent',
                    'meta_value' => $child->ID,
                    'order' => 'ASC',
                    'orderby' => 'menu_order',
                ));
                $child_count += count($grand_children) + 1; // add 1 for the child itself
            }
        }
        $child_count_html = '<span class="child-count">' . $child_count . '</span>';

        if ($this->level == 2) {
            $columnclass='';

            if ($child_count<8){
                $columnclass='1';
            }else   if($child_count<16){
                $columnclass='2';
            }else if($child_count<=24){
                $columnclass='3';
            }
            else if($child_count>24){
                $columnclass='4';
            }
        
            $output .= '<div class="nav-flyout"><div class="container"><div class="row">';
            $output .= '<div class="flyout-entries-full column-count-'. $columnclass.'">';
            
           
           
        }


        $output .= '<ul class="sub-menu level'.$this->level.'">';
        
        
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        if ($this->level == 2) {
            $output       .= '</ul>';
            $currenttitle = fau_get_the_title($this->currentID);
            if (!empty($currenttitle)) {
                $output   .= '<a href="'.get_permalink($this->currentID).'" class="button-portal">';
                $pretitle = get_theme_mod('menu_pretitle_portal');
                if (!fau_empty($pretitle)) {
                    $output .= $pretitle.' ';
                }
                $output    .= $currenttitle;
                $posttitle = get_theme_mod('menu_aftertitle_portal');
                if (!fau_empty($posttitle)) {
                    $output .= ' '.$posttitle;
                }
                $output .= '</a>';
       
            }
            $output .= '</div>';
            $output .= '</div></div></div>';

        } else {
            $output .= '</ul>';
        }
        $this->level--;
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $level       = $this->level;
        $iscurrent   = 0;
        $class_names = $value = '';

        // Generate Classes. Dont use WordPress default, cause i dont want to
        // get all those unused data filled up my html
        
        $classes = array();
          if ($level < 2) {
            
            //	$classes[] = 'menu-item-' . $item->ID;
            $classes[] = 'level'.$level;
           
        }
        if (in_array("menu-item-has-children", $item->classes)) {
            $classes[] = 'has-sub';
        }
        if (in_array("current-menu-item", $item->classes)) {
            $classes[] = 'current-menu-item';
        }
        if (in_array("current-menu-parent", $item->classes)) {
            $classes[] = 'current-menu-parent';
        }
        if (in_array("current-page-item", $item->classes)) {
            $iscurrent = 1;
            $classes[] = 'current-page-item';
        }
        $rellink = fau_make_link_relative($item->url);
        if (substr($rellink, 0, 4) == 'http') {
            // absoluter Link auf externe Seite
            $classes[] = 'external';
        }
        $this->element = $item; // Store the current element to be used in start_lvl


        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="'.esc_attr($class_names).'"' : '';
        


        $output .= '<li'.$value.$class_names.'>';

        $atts           = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';
    
        $item_classes  = empty($item->classes) ? array() : (array)$item->classes;
        $item_classes  = fau_cleanup_menuclasses($item_classes);
        $item_class    = implode(' ', $item_classes);
        $atts['class'] = !empty($item_class) ? $item_class : '';
    
    
        if ($iscurrent == 1) {
            $atts['aria-current'] = "page";
        }
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);
   
        
        if ($this->level == 1) {
            $this->currentID = $item->object_id;
        }
    
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value      = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' '.$attr.'="'.$value.'"';
            }
        }
    
        // Add child count for level one and two
        if ($level <= 1 && isset($args->walker)) {
            $child_count = 0;
            $children = get_posts(array(
                'post_type' => 'nav_menu_item',
                'nopaging' => true,
                'numberposts' => -1,
                'meta_key' => '_menu_item_menu_item_parent',
                'meta_value' => $item->ID,
                'order' => 'ASC',
                'orderby' => 'menu_order',
            ));
            if (!empty($children)) {
                foreach ($children as $child) {
                    $grand_children = get_posts(array(
                        'post_type' => 'nav_menu_item',
                        'nopaging' => true,
                        'numberposts' => -1,
                        'meta_key' => '_menu_item_menu_item_parent',
                        'meta_value' => $child->ID,
                        'order' => 'ASC',
                        'orderby' => 'menu_order',
                    ));
                    $child_count += count($grand_children) + 1; // add 1 for the child itself
                }
            }
            $child_count_html = '<span class="child-count">' . $child_count . '</span>';
        } else {
            $child_count_html = '';
        }
        
    
        $item_output = $args->before;
        $item_output .= '<a'.$attributes.'>';
        $item_output .= $args->link_before.apply_filters('the_title', $item->title, $item->ID).$args->link_after; // Append child count
        $item_output .= '</a>';
        $item_output .= $args->after;
    
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    function end_el(&$output, $item, $depth = 0, $args = array())  {
        $output .= "</li>";
    }
}







/*-----------------------------------------------------------------------------------*/
/* Create submenu icon/grid in content
/*-----------------------------------------------------------------------------------*/
function fau_get_contentmenu($menu, $submenu = 1, $subentries = 0, $nothumbs = 0, $nodefthumbs = 0) {

    if (empty($menu)) {
        echo '<!-- no id and empty slug for menu -->';
        return;
    }
    if ($menu == sanitize_key($menu)) {
        $term = get_term_by('id', $menu, 'nav_menu');
    } else {
        $term = get_term_by('name', $menu, 'nav_menu');
    }
    if ($term === false) {
        echo '<!-- invalid menu -->';
        return;
    }
    $slug = $term->slug;

    if ($subentries == 0) {
        $subentries = get_theme_mod('default_submenu_entries');
    }

    echo '<div class="contentmenu" role="navigation" aria-label="'.__('Inhaltsmenü', 'fau').'">';
    echo '<ul class="subpages-menu">';
    wp_nav_menu(array(
        'menu'        => $slug,
        'container'   => false,
        'items_wrap'  => '%3$s',
        'link_before' => '',
        'link_after'  => '',
        'walker'      => new Walker_Content_Menu($menu, $submenu, $subentries, $nothumbs, $nodefthumbs)
    ));
    echo '</ul>';
    echo "</div>\n";

    return;
}

/*-----------------------------------------------------------------------------------*/
/* Walker for Contentmenus (Portalmenus)
/*-----------------------------------------------------------------------------------*/
class Walker_Content_Menu extends Walker_Nav_Menu {
    private $level = 1;
    private $count = array();
    private $element;
    private $showsub = true;
    private $listview = false;
    private $meganav = false;

    function __construct( $menu, $showsub = true, $maxsecondlevel = 0, $noshowthumb = false,$nothumbnailfallback = false, $thumbnail = 'rwd-480-2-1', $listview = false, $meganav = false ) {
        $this->showsub             = $showsub && !$listview;
        
        if ($maxsecondlevel==0) {
            $maxsecondlevel = get_theme_mod('default_submenu_entries');
        }
        
        $this->maxsecondlevel      = $maxsecondlevel;
        $this->nothumbnail         = $noshowthumb || $listview || $meganav;
        $this->nothumbnailfallback = $nothumbnailfallback;
        $this->thumbnail           = $thumbnail;
        $this->listview            = $listview;
        $this->meganav             = $meganav;
    }

    function __destruct() {
        // $output .= '</ul> <!-- destruct -->';
    }

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $this->level++;
        
        $this->count[$this->level] = 0;
        if ($this->level == 2 && $this->count[$this->level] <= $this->maxsecondlevel && $this->showsub) {
            $output .= '<ul class="sub-menu">';
        }
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        
        if ($this->showsub) {
            
            if ($this->level == 2 ) {
                 if ($this->count[$this->level] <= $this->maxsecondlevel ) {
                    $output .= '</ul>';
                } elseif ( ($this->count[$this->level] >= ($this->maxsecondlevel + 1)) ) {
                    $output .= '<li class="more">';
                    $output .= '<a href="'.$this->element->url.'">'.__('Mehr', 'fau').' ...</a></li>';
                    $output .= '</ul>';
                } else {
                    $output .= '</ul>';
                }
            }
        }
       

        $this->level--;
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        if (isset($this->count[$this->level])) {
            $this->count[$this->level]++;
        } else {
            $this->count[$this->level] = 1;
        }

        if ($this->level == 1) {
            $this->element = $item;
        }
        $item_output = '';
        // Only show elements on the first level and only five on the second level, but only if showdescription == FALSE
        if ($this->level == 1 ||
            ($this->level == 2 && $this->count[$this->level] <= $this->maxsecondlevel && $this->showsub)) {
            $class_names = $value = '';
            $externlink  = false;
            $classes     = empty($item->classes) ? array() : (array)$item->classes;
            $classes[]   = 'menu-item-'.$item->ID;

            if ($this->level == 1) {
                $classes[] = 'menubox';
            }

            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
            $class_names = $class_names ? ' class="'.esc_attr($class_names).'"' : '';
            $iscurrent   = 0;
            if (in_array("current_page_item", $item->classes)) {
                $iscurrent = 1;
            }

            if (!$this->listview) {
                if ($this->level == 1) {
                    $output .= $indent.'<li'.$class_names.'>';
                } else {
                    $output .= '<li>';
                }
            }

            $atts           = array();
            $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
            $atts['target'] = !empty($item->target) ? $item->target : '';
            $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
            $atts['href']   = !empty($item->url) ? $item->url : '';
            $targeturl      = $atts['href'];
            if ($iscurrent == 1) {
                $atts['aria-current'] = "page";
            }

            $post = get_post($item->object_id);

            if ($this->level == 1) {
                $atts['class'] = 'subpage-item';
            }

            if (fau_is_url_external($atts['href'])){      
                if (isset($atts['class'])) {
                    $atts['class'] .= ' ext-link';
                } else {
                    $atts['class'] = 'ext-link';
                }
            }
            
            $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

            $attributes = '';
            foreach ($atts as $attr => $value) {
                if (!empty($value)) {
                    $value      = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                    $attributes .= ' '.$attr.'="'.$value.'"';
                }
            }

            $item_output = $args->before;
            if ($post && $post->post_type == 'imagelink') {
                $targeturl  = get_post_meta($item->object_id, 'fauval_imagelink_url', true);
                $externlink = true;
                $link       = '<a class="ext-link" data-wpel-link="internal" href="'.$targeturl.'">';
            } else {
                if ($post && $post->post_type == 'page') {
                    $titlelangcode = get_post_meta($post->ID, 'fauval_pagetitle_langcode', true);
                    if (!fau_empty($titlelangcode)) {
                        $sitelang = fau_get_language_main();
                        if ($titlelangcode != $sitelang) {
                            $attributes .= ' lang="'.$titlelangcode.'"';
                        }
                    }
                }

                $link = '<a'.$attributes.'>';
            }


            if ($this->level == 1) {
                if (!$this->nothumbnail) {
                    $post_thumbnail_id = get_post_thumbnail_id($item->object_id);
                    $imagehtmlout       = '';
                    $imagehtml          = '';
                    $imageurl           = '';

                    $pretitle  = __('Zur Seite: ','fau');
                    $alttext   = $pretitle.apply_filters('the_title', $item->title, $item->ID);
                    $alttext   = esc_html($alttext);
                    $altattr   = 'alt="'.$alttext.'"';

                    if ($post_thumbnail_id) {
                        $imagehtml   = fau_get_image_htmlcode($post_thumbnail_id, $this->thumbnail, $alttext);
                        $imagehtmlout .= $imagehtml;
                    }
                    if ((fau_empty($imagehtml)) && (!$this->nothumbnailfallback)) {
                        $imagehtmlout .= fau_get_image_fallback_htmlcode('fallback_submenu_image', $alttext, 'fallback');
                    }

                    if ($imagehtmlout != '') {
                        $item_output .= '<div class="thumb" role="presentation" aria-hidden="true" tabindex="-1">';
                        $item_output .= '<a tabindex="-1" ';

                        if ($externlink) {
                            $item_output .= 'data-wpel-link="internal" ';
                        }
                        $item_output .= 'class="image';
                        if ($externlink) {
                            $item_output .= ' ext-link';
                        }
                        $item_output       .= '" href="'.$targeturl.'">';

                        $item_output .= $imagehtmlout;

                        $item_output .= '</a>';
                        $item_output .= '</div>';
                    }

                }
                $item_output .= $this->listview || $this->meganav ? '' : $args->link_before.'<span class="portaltop">';
                $item_output .= $link;
                $item_output .= apply_filters('the_title', $item->title, $item->ID);
                $item_output .= '</a>';
                $item_output .= $this->listview || $this->meganav ? '' : '</span>'.$args->link_after;
            } else {
                $item_output .= $link;
                $item_output .= $args->link_before.apply_filters('the_title', $item->title, $item->ID).$args->link_after;
                $item_output .= '</a>';
            }


            $item_output .= $args->after;


            if (!($this->showsub ) && ($this->level == 1)) {
                $desc = get_post_meta($item->object_id, 'portal_description', true);
                // Wird bei Bildlink definiert
                if ($desc) {
                    $item_output .= '<p>'.$desc.'</p>';
                }
            }
        }
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    function end_el(&$output, $item, $depth = 0, $args = array())  {

        if (!$this->listview && ($this->level == 1 ||
            ($this->level == 2 && $this->count[$this->level] <= $this->maxsecondlevel && $this->showsub))) {
            $output .= "</li>";

        }

    }
}

/*-----------------------------------------------------------------------------------*/
/* Cleanup Menu Classes from unwanted garbage :)
/*-----------------------------------------------------------------------------------*/
function fau_cleanup_menuclasses($currentarray = array()) {
    $menugarbage = array(
        "menu-item-type-post_type",
        "menu-item-object-page",
        "menu-item-has-children"
    );

    return array_diff($currentarray, $menugarbage);
}
/*-----------------------------------------------------------------------------------*/
/* Check if the current post/page is a endpoint
/*-----------------------------------------------------------------------------------*/
function fau_is_endpoint() {
    global $wp_query;
    global $wp_rewrite;
    global $wp_the_query;
     
    if (!isset($wp_query)) {
        return false;
    }
    if (!isset($wp_rewrite)) {
        return false;
    }
    $endpoints = $wp_rewrite->endpoints;
    $res = false;    
    foreach ($endpoints as $num => $endpoint) {
        if(isset($wp_the_query->query_vars[$endpoint[1]])) {
            $res = $endpoint[1];
            break;
        }
    }
    return $res;    
}
/*-----------------------------------------------------------------------------------*/
/* Create breadcrumb
/*-----------------------------------------------------------------------------------*/
function fau_breadcrumb($lasttitle = '', $echo = true, $noNav = false) {
    global $defaultoptions;
    global $post;

    $home        = $defaultoptions['breadcrumb_root'];
    $showcurrent = $defaultoptions['breadcrumb_showcurrent'];
    $before      = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    $after       = '</li>';
    $position    = 1;
    $endpoint_slug = fau_is_endpoint();

    $res = '';

    if (!$noNav) {
        $res .= '<nav aria-label="'.__('Breadcrumb', 'fau').'" class="breadcrumbs">';
    }
    if (get_theme_mod('breadcrumb_withtitle')) {
        $res .= '<p class="breadcrumb_sitetitle" role="presentation">'.get_bloginfo('title', 'display' ).'</p>'."\n";
    }

    $res .= '<ol class="breadcrumblist" itemscope itemtype="https://schema.org/BreadcrumbList">';
	
    if (is_front_page()) {
        $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.$home.'</span><meta itemprop="position" content="'.$position.'" />'.$after;
    } elseif ((is_home()) && ($endpoint_slug === false)) {
        $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.get_the_title(get_option('page_for_posts')).'</span><meta itemprop="position" content="'.$position.'" />'.$after;
    } else {

        $homeLink = home_url('/');
        $res      .= $before.'<a itemprop="item" href="'.$homeLink.'"><span itemprop="name">'.$home.'</span></a><meta itemprop="position" content="'.$position.'" />'.$after;
        $position++;

        if ($endpoint_slug !== false) {        
            $res .= $before.'<span aria-current="page" itemprop="name">'.ucfirst($endpoint_slug).'</span>';
            $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;
            $position++;
          
        } elseif (is_category()) {
            $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.single_cat_title('', false).'</span>';
            $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;

        } elseif (is_date()) {

            if (is_day()) {
                $res .= $before.'<a itemprop="item" href="'.get_year_link(get_the_time('Y')).'"><span itemprop="name">'.get_the_time('Y').'</span></a>';
                $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;
                $position++;

                $res .= $before.'<a itemprop="item" href="'.get_month_link(get_the_time('Y'),
                        get_the_time('m')).'"><span itemprop="name">'.get_the_time('F').'</span></a>';
                $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;
                $position++;

                $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.get_the_time('d').'</span>';
                $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;

            } elseif (is_month()) {
                $res .= $before.'<a itemprop="item" href="'.get_year_link(get_the_time('Y')).'"><span itemprop="name">'.get_the_time('Y').'</span></a>';
                $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;
                $position++;

                $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.get_the_time('F').'</span>';
                $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;

            } else {
                $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.get_the_time('Y').'</span>';
                $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;

            }

        } elseif (is_single() && !is_attachment()) {

            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug      = $post_type->rewrite;

                $res .= $before.'<a itemprop="item" href="'.$homeLink.$slug['slug'].'"><span itemprop="name">'.$post_type->labels->name.'</span></a>';
                $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;
                $position++;

                if ($showcurrent) {
                    $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.get_the_title().'</span>';
                    $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;
                }
            } elseif ($showcurrent) {
                $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.get_the_title().'</span>';
                $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;
            }

        } elseif (!is_single() && !is_page() && !is_search() && get_post_type() != 'post' && !is_404()) {
            $post_type = get_post_type_object(get_post_type());
            $res       .= $before.'<span class="active" aria-current="page" itemprop="name">'.$post_type->labels->name.'</span>';
            $res       .= '<meta itemprop="position" content="'.$position.'" />'.$after;
        } elseif (is_attachment()) {
            $parent = get_post($post->post_parent);

            $res .= $before.'<a itemprop="item" href="'.get_permalink($parent).'"><span itemprop="name">'.$parent->post_title.'</span></a>';
            $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;
            $position++;


            if ($showcurrent) {
                $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.get_the_title().'</span>';
                $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;

            }
        } elseif (is_page()) {

            if ($post->post_parent) {
                $parent_id   = $post->post_parent;
                $breadcrumbs = array();
                $thisentry   = array();
                while ($parent_id) {
                    $page               = get_page($parent_id);
                    $thisentry['url']   = get_permalink($page->ID);
                    $thisentry['title'] = fau_get_the_title($page->ID);
                    $breadcrumbs[]      = $thisentry;
                    $parent_id          = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                foreach ($breadcrumbs as $crumb) {
                    $res .= $before.'<a itemprop="item" href="'.$crumb['url'].'"><span itemprop="name">'.$crumb['title'].'</span></a>';
                    $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;
                    $position++;
                }
            }
            if ($showcurrent) {
                $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.fau_get_the_title().'</span>';
                $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;
            }

        } elseif (is_search()) {
            $thistitle    = '<span>'.__('Suche', 'fau').'</span>';
            $searchstring = esc_attr(get_search_query());
            if (!fau_empty($searchstring)) {
                $thistitle = '<span>'.__('Suche nach', 'fau').'</span> "'.$searchstring.'"';
            }

            $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.$thistitle.'</span>';
            $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;


        } elseif (is_tag()) {
            $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.__('Schlagwort',
                    'fau').' "'.single_tag_title('', false).'"'.'</span>';
            $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;

        } elseif (is_author()) {
            global $author;
            $userdata = get_userdata($author);

            $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.__('Beiträge von','fau').' '.$userdata->display_name.'</span>';
            $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;

        } elseif (is_404()) {
            $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.__('Seite nicht gefunden','fau').'</span>';
            $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;

        }

    }
    $res .= '</ol>';
    if (!$noNav) {
        $res .= '</nav>';
    }

    if ($echo) {
        echo $res;
    }

    return $res;
}
/*-----------------------------------------------------------------------------------*/
/* Get the title for the hero section
/*-----------------------------------------------------------------------------------*/
function fau_get_hero_title($overwrite = '') {
    if (!fau_empty($overwrite)) {
        return $overwrite;
    }
    global $post;


    if ($title = fau_is_endpoint()) {
        return ucfirst($title);
     } elseif (is_archive()) {
        return get_the_archive_title();     
    } elseif ((is_front_page()) || (is_home())) {
        return get_option('blogname'); // get_the_title(get_option('page_for_posts'));
    } elseif (is_category()) {
        return single_cat_title('', false);
    } elseif (is_tag()) {
        return __('Schlagwort', 'fau').' "'.single_tag_title('', false).'"';    
    } elseif (is_date()) {
        return get_the_time();
    } elseif (is_page()) {        
        return fau_get_the_title();
    } elseif (is_search()) {
        $thistitle    = '<span>'.__('Suche', 'fau').'</span>';
        $searchstring = esc_attr(get_search_query());
        if (!fau_empty($searchstring)) {
            $thistitle = '<span>'.__('Suche nach', 'fau').'</span> "'.$searchstring.'"';
        }
        return $thistitle;

    } elseif (is_author()) {
        global $author;
        $userdata = get_userdata($author);
        return __('Beiträge von','fau').' '.$userdata->display_name;
    } elseif (is_404()) {
        return __('Seite nicht gefunden','fau');

    } elseif (!is_single() && !is_page() && !is_search() && get_post_type() != 'post' && !is_404()) {
        $post_type = get_post_type_object(get_post_type());
        return $post_type->labels->name;
    }


    
    // fallback for everything else, that wasnt defined above
    return get_the_title();

}
/*-----------------------------------------------------------------------------------*/
/* Create Social Media Menu
/*-----------------------------------------------------------------------------------*/
function fau_get_socialmedia_menu($name = '', $ulclass = '', $withog = true) {

    if (!isset($name)) {
        return;
    }
    $menu     = wp_get_nav_menu_object($name);
    $thislist = '';
    if (isset($menu)) {
        $thislist = '<ul';
        if ($ulclass) {
            $thislist .= ' class="'.$ulclass.'"';
        }
        $thislist .= '>';

        $menu_items = wp_get_nav_menu_items($menu->term_id);
        foreach ((array)$menu_items as $key => $menu_item) {
            $title       = esc_attr($menu_item->title);
            $url         = esc_url($menu_item->url);
            $class_names = '';
            $social      = fau_sanitize_socialmedia_classes($title);
            if ($social) {
                $class_names = 'social-'.$social;
                $thislist    .= '<li class="'.$class_names.'">';
            } else {
                $thislist .= '<li class="social-iconbyurl">';
            }

            $thislist   .= '<a data-wpel-link="internal" ';
            $attr_title = esc_attr($menu_item->attr_title);
            if ($attr_title) {
                $thislist .= 'title="'.$attr_title.'" ';
            }
            if ($withog) {
                $thislist .= 'itemprop="sameAs" ';
            }
            $thislist .= 'href="'.$url.'">'.$title.'</a></li>';
        }
        $thislist .= '</ul>';
    }

    return $thislist;
}

/*-----------------------------------------------------------------------------------*/
/* Create Page Nav for Template Page with Subnav
/*-----------------------------------------------------------------------------------*/
function fau_get_page_subnav($id) {
    $thismenu = '';
    $thismenu .= '<nav class="sidebar-subnav" aria-labelledby="subnavtitle">';

    $offset = 0;
    if (!isset($id)) {
        $id = $post->ID;
    }

    $websitetype = get_theme_mod('website_type');
    if ($websitetype == -1) {
        $menulevel = get_post_meta($id, 'menu-level', true);
        if ($menulevel) {
            $offset = $menulevel;
        }
    }
    $parent_page = get_top_parent_page_id($id, $offset);
    $parent      = get_page($parent_page);


    $pagelist = get_pages(array('child_of' => $parent_page));
    $exclude  = '';

    foreach ($pagelist as $page) {
        $ignoresubnavi = get_post_meta($page->ID, 'fauval_hide-in-subnav', true);
        if ($ignoresubnavi) {
            $exclude .= $page->ID.",";
        }
    }


    $thismenu .= '<header id="subnavtitle" class="small menu-header">';
    $thismenu .= '<span class="screen-reader-text">'.__('Bereichsnavigation:',
            'fau').' </span><a href="'.get_permalink($parent->ID).'">'.$parent->post_title.'</a>';
    $thismenu .= '</header>';
    $thismenu .= '<ul id="subnav">';

    $showstatus = 'publish';
    if (is_user_logged_in()) {
        $showstatus = array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private');
    }
    
    // Verwendung: Holen der Liste der auszuschließenden Seiten für die Seite mit der ID $id
    $exclude_sibilings = get_pages_to_exclude($id,$showstatus);
    $exclude_list = implode(',', $exclude_sibilings);    
    if (!empty($exclude)) {
        $exclude_list .= ','.$exclude;
    }


    $thismenu .= wp_list_pages(array(
        'child_of'    => $parent_page,
        'title_li'    => '',
        'echo'        => false,
        'post_status' => $showstatus,
        'exclude'     => $exclude_list, // $exclude,
        'walker'      => new Walker_SubNav()
    ));

    $thismenu .= '</ul>';
    $thismenu .= '</nav>';

    return $thismenu;
}


function get_pages_to_exclude($id, $showstatus = 'publish') {
    // Liste der auszuschließenden Seiten initialisieren
    $exclude_list = array();

    // Seiten-ID von $id holen
    $page_id = get_post($id);

    // Wenn $id eine gültige Seite ist
    if ($page_id) {
        // Alle Seiten holen
        $all_pages = get_pages($showstatus);

        // Liste der Elternseiten von $id erstellen
        $parent_pages = array_merge(array($page_id->ID), get_post_ancestors($page_id->ID));

        // Seiten filtern, die weder Kinder von $id noch Kinder der Elternseiten von $id sind
        foreach ($all_pages as $page) {
            // Wenn die Seite weder eine Kinderseite von $id noch eine Kinderseite von den Elternseiten von $id ist
            if ($page->ID !== $id && $page->post_parent !== $id && !in_array($page->post_parent, $parent_pages)) {
                $exclude_list[] = $page->ID;
            }
        }
    }

    return $exclude_list;
}



class Walker_SubNav extends Walker_Page {
    function start_el(&$output, $page, $depth = 0, $args = array(), $current_page = 0) {
        if ($depth) {
            $indent = str_repeat("\t", $depth);
        } else {
            $indent = '';
        }
        
        $showstatus =  array('publish');
        if (is_user_logged_in()) {
            $showstatus = array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private');
        }
        
        if (in_array($page->post_status, $showstatus)) {
    
            // Standard CSS-Klassen für die Listenelemente
            $css_class = array();
            if ($page->post_status != 'publish') {
                $css_class[] = $page->post_status.'-page';
            }
            $is_password_protected = post_password_required($page->ID);
             if ($is_password_protected) {
                $css_class[] = 'private-page';
            }

            // Prüfen, ob die Seite Kinder hat
            if (isset($args['pages_with_children'][$page->ID])) {
                $css_class[] = 'page_item_has_children';
            }

            // Markieren der aktuellen Seite
            if (!empty($current_page)) {
                $_current_page = get_post($current_page);
                if ($_current_page && in_array($page->ID, $_current_page->ancestors)) {
                    $css_class[] = 'current_page_ancestor';
                }
                if ($page->ID == $current_page) {
                    $css_class[] = 'current_page_item';
                } elseif ($_current_page && $page->ID == $_current_page->post_parent) {
                    $css_class[] = 'current_page_parent';
                }
            } elseif ($page->ID == get_option('page_for_posts')) {
                $css_class[] = 'current_page_parent';
            }



            // Kombinieren der CSS-Klassen in eine Zeichenkette
            $class_names = implode(' ', $css_class);
            $class_attr = '';
            if (!empty($class_names)) {
                $class_attr = ' class="'.$class_names.'"';
            }

            // Abrufen und Hinzufügen des Meta-Werts 'fauval_aria-label' zur Ausgabe
            $aria_label = get_post_meta($page->ID, 'fauval_aria-label', true);
            $aria_label_attr = '';
            if (!empty($aria_label)) {
                $aria_label_attr = ' aria-label="'.esc_html($aria_label).'"';
            }

            $url = fau_make_link_relative(get_permalink($page->ID));

            // Hinzufügen des Listenelements zur Ausgabe
            $output .= $indent . sprintf(
                '<li%s><a%s href="%s">%s</a>',
                $class_attr,
                $aria_label_attr,
                $url,
                $page->post_title
            );
        }
      
    }

    function end_el(&$output, $page, $depth = 0, $args = array()) {
        $output .= "</li>";
    }

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $output .= '<ul class="children">';
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "</ul>";
    }
}

/*-----------------------------------------------------------------------------------*/
/* Erstellt Links in der Metanav oben
/*
 * @param $args array
 * @param $mode int         1 = orgalist only, 2 = meta-nav menu only
 * @param $no_logo bool     Render home link without logo
 *
 * @return string
 */
/*-----------------------------------------------------------------------------------*/
function fau_get_toplinks($args = array(), $mode = 0) {
    global $default_link_liste;


    $uselist = $default_link_liste['meta'];
    $result  = '';

    $orgalist = fau_get_orgahomelink();
    $thislist = "";


    if (has_nav_menu('meta')) {
        $menu_name = 'meta';

        if (($locations = get_nav_menu_locations()) && isset($locations[$menu_name])) {
            $menu       = wp_get_nav_menu_object($locations[$menu_name]);
            $menu_items = wp_get_nav_menu_items($menu->term_id);
            foreach ((array)$menu_items as $key => $menu_item) {
                $title       = $menu_item->title;
                $url         = $menu_item->url;            
                $type       = $menu_item->type;
                $class_attr = '';
                if (!empty( $menu_item->classes )) {
                    $class_names = join( ' ',  $menu_item->classes  );            
                    if (!empty($class_names)) {
                        $class_attr = ' class="' . esc_attr( $class_names ) . '"';
                    }
                }
                if ($type == 'post_type') {
               //     $thislist .= RRZE\THEME\EINRICHTUNGEN\Debugging::get_html_var_dump($menu_item);
                    $item_output = '';
                    if (isset($menu_item->object_id) && isset($menu_item->object) && ($menu_item->object == 'imagelink')) {
                        	$url  = get_post_meta( $menu_item->object_id, 'fauval_imagelink_url', true );
                            $imageid = get_post_thumbnail_id( $menu_item->object_id ); 
                            
                            if (empty($title)) {
                                $title = get_the_title($menu_item->object_id);
                                $title = esc_html($title);
                                if (empty($title)) {
                                    $title = __("Zum Webauftritt", 'fau').': '.$url;
                                }
                            }

                            $item_output = fau_get_image_htmlcode($imageid, 'logo-thumb', $title);
   
                    }
                    if (!empty($item_output)) {
                           $thislist .= '<li' . $class_attr . '><a href="' . $url . '">' .$item_output.'</a></li>';
                    }
                     
                } else {
                    $thislist .= '<li' . $class_attr . '><a data-wpel-link="internal" href="' . $url . '">' . $title .'</a></li>';
                }
                
                
               
                
                
                
                
               
            }
        }
    } else {
        foreach ($uselist as $key => $entry) {
            if (substr($key, 0, 4) != 'link') {
                continue;
            }
            $thislist .= '<li';
            if (isset($entry['class'])) {
                $thislist .= ' class="' . $entry['class'] . '"';
            }
            $thislist .= '>';
            if (isset($entry['content'])) {
                $thislist .= '<a data-wpel-link="internal" href="' . $entry['content'] . '">';
            }
            $thislist .= $entry['name'];
            if (isset($entry['content'])) {
                $thislist .= '</a>';
            }
            $thislist .= "</li>\n";
        }
    }


    if (isset($orgalist)) {
        $result .= $orgalist;

        if ($mode === 1) {
            return $result;
        }
    }
    if (isset($thislist)) {

        if ($mode === 2) {
            $result = '<ul class="meta-nav menu"';
        } else {
            $result .= '<ul class="meta-nav menu"';
        }

        if (is_array($args) && isset($args['title'])) {
            $result .= ' aria-label="' . esc_attr($args['title']) . '"';
        }
        $result .= '>';
        $result .= $thislist;
        $result .= '</ul>';
        $result .= "\n";
    }

    return $result;
}
/*-----------------------------------------------------------------------------------*/
/* EOF menu.php
/*-----------------------------------------------------------------------------------*/