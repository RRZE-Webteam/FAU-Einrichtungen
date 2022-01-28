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
function fau_register_menus()
{
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

    register_nav_menu('main-menu', __('Haupt-Navigation', 'fau'));
    // Hauptnavigation

    if ($website_type == 0) {
        // Buehnennavigation Template Portal Startseite mit 2 Spalten
        register_nav_menu('quicklinks-3', __('Startseite Fakultät: Bühne Spalte 1', 'fau'));
        register_nav_menu('quicklinks-4', __('Startseite Fakultät: Bühne Spalte 2', 'fau'));
    }


    register_nav_menu($defaultoptions['socialmedia_menu_position'], $defaultoptions['socialmedia_menu_position_title']);
    // Social Media Menu (seit 1.9.5)

    register_nav_menu('error-helper', __('Fehler- und Suchseite: Vorschlagmenu', 'fau'));
    // Fehler und Suchseite
    // Mit V2. nur noch ein Menü, welches als Portalmenu angezeigt wird.
    
    
//    register_nav_menu('error-2', __('Fehler- und Suchseite: Vorschlagmenu Spalte 2', 'fau'));
    // Fehler und Suchseite: Vorschlagmenu Spalte 2
//    register_nav_menu('error-3', __('Fehler- und Suchseite: Vorschlagmenu Spalte 3', 'fau'));
    // Fehler und Suchseite: Vorschlagmenu Spalte 3
//    register_nav_menu('error-4', __('Fehler- und Suchseite: Vorschlagmenu Spalte 4', 'fau'));
    // Fehler und Suchseite: Vorschlagmenu Spalte 4

}

/*-----------------------------------------------------------------------------------*/
/* Erstelle ein Social Media Menu, wenn es noch nicht vorhanden ist
/*-----------------------------------------------------------------------------------*/
function fau_create_socialmedia_menu()
{
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
function add_has_children_to_nav_items($items)
{
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
function fau_get_menu_name($location)
{
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
add_filter('nav_menu_item_id', 'clear_nav_menu_item_id', 10, 3);
function clear_nav_menu_item_id($id, $item, $args)
{
    return "";
}

/*-----------------------------------------------------------------------------------*/
/* returns top parent id
/*-----------------------------------------------------------------------------------*/
function get_top_parent_page_id($id, $offset = false)
{

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
        if ($this->level == 2) {
            $output .= '<div class="nav-flyout"><div class="container"><div class="row">';
            $output .= '<div class="flyout-entries-full">';
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


        if (is_page($item->object_id)) {
            $titlelangcode = get_post_meta($item->object_id, 'fauval_pagetitle_langcode', true);
            if (!fau_empty($titlelangcode)) {
                $sitelang = fau_get_language_main();
                if ($titlelangcode != $sitelang) {
                    $attributes .= ' lang="'.$titlelangcode.'"';
                }
            }
        }


        $item_output = $args->before;
        $item_output .= '<a'.$attributes.'>';
        $item_output .= $args->link_before.apply_filters('the_title', $item->title, $item->ID).$args->link_after;
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

    echo '<div class="contentmenu" role="navigation" aria-label="'.__('Inhaltsmenu', 'fau').'">';
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
/* Walker for subnav
/*-----------------------------------------------------------------------------------*/
class Walker_Content_Menu extends Walker_Nav_Menu {
    private $level = 1;
    private $count = array();
    private $element;
    private $showsub = true;

    function __construct(
        $menu,
        $showsub = true,
        $maxsecondlevel = 6,
        $noshowthumb = false,
        $nothumbnailfallback = false,
        $thumbnail = 'rwd-480-2-1'
    ) {
        $this->showsub             = $showsub;
        $this->maxsecondlevel      = $maxsecondlevel;
        $this->nothumbnail         = $noshowthumb;
        $this->nothumbnailfallback = $nothumbnailfallback;
        $this->thumbnail           = $thumbnail;
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
        if ($this->level == 2 && $this->count[$this->level] <= $this->maxsecondlevel && $this->showsub) {
            $output .= '</ul>';
        } elseif (($this->level == 2) && ($this->count[$this->level] == ($this->maxsecondlevel + 1)) && ($this->showsub)) {
            $output .= '<li class="more"><a href="'.$this->element->url.'">'.__('Mehr', 'fau').' ...</a></li>';
            $output .= '</ul>';

        } elseif (($this->level == 2) && ($this->showsub)) {
            $output .= '</ul>';
        }

        $this->level--;
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        global $options;
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

            if ($this->level == 1) {
                $output .= $indent.'<li'.$class_names.'>';
            } else {
                $output .= '<li>';
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
                $item_output .= $args->link_before.'<span class="portaltop">';
                $item_output .= $link;
                $item_output .= apply_filters('the_title', $item->title, $item->ID);
                $item_output .= '</a>';
                $item_output .= '</span>'.$args->link_after;
            } else {
                $item_output .= $link;
                $item_output .= $args->link_before.apply_filters('the_title', $item->title,
                        $item->ID).$args->link_after;
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

    function end_el(&$output, $item, $depth = 0, $args = array())
    {

        if ($this->level == 1 ||
            ($this->level == 2 && $this->count[$this->level] <= $this->maxsecondlevel && $this->showsub)) {
            $output .= "</li>";

        }

    }
}

/*-----------------------------------------------------------------------------------*/
/* Cleanup Menu Classes from unwanted garbage :)
/*-----------------------------------------------------------------------------------*/
function fau_cleanup_menuclasses($currentarray = array())
{
    $menugarbage = array(
        "menu-item-type-post_type",
        "menu-item-object-page",
        "menu-item-has-children"
    );

    return array_diff($currentarray, $menugarbage);
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


    $res = '';

    if (!$noNav) {
        $res .= '<nav aria-label="'.__('Breadcrumb', 'fau').'" class="breadcrumbs">';
    }
    if (get_theme_mod('breadcrumb_withtitle')) {
        $res .= '<p class="breadcrumb_sitetitle" role="presentation">'.get_bloginfo('title').'</p>'."\n";
    }

    $res .= '<ol class="breadcrumblist" itemscope itemtype="https://schema.org/BreadcrumbList">';


    if (is_front_page()) {
        $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.$home.'</span><meta itemprop="position" content="'.$position.'" />'.$after;
    } elseif (is_home()) {
        $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.get_the_title(get_option('page_for_posts')).'</span><meta itemprop="position" content="'.$position.'" />'.$after;
    } else {

        $homeLink = home_url('/');
        $res      .= $before.'<a itemprop="item" href="'.$homeLink.'"><span itemprop="name">'.$home.'</span></a><meta itemprop="position" content="'.$position.'" />'.$after;

        $position++;


        if (is_category()) {
            $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.single_cat_title('',
                    false).'</span>';
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

            $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.__('Beiträge von',
                    'fau').' '.$userdata->display_name.'</span>';
            $res .= '<meta itemprop="position" content="'.$position.'" />'.$after;

        } elseif (is_404()) {
            $res .= $before.'<span class="active" aria-current="page" itemprop="name">'.__('Seite nicht gefunden',
                    'fau').'</span>';
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

    $thismenu .= wp_list_pages(array(
        'child_of'    => $parent_page,
        'title_li'    => '',
        'echo'        => false,
        'post_status' => $showstatus,
        'exclude'     => $exclude
    ));

    $thismenu .= '</ul>';
    $thismenu .= '</nav>';

    return $thismenu;
}
/*-----------------------------------------------------------------------------------*/
/* EOF menu.php
/*-----------------------------------------------------------------------------------*/
