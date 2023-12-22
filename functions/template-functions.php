<?php

/**
 * Additional features to allow styling of the templates
 */

/*-----------------------------------------------------------------------------------*/
/* Extends the default WordPress body classes
/*-----------------------------------------------------------------------------------*/
function fau_body_class($classes)
{
    global $defaultoptions;
    global $default_fau_orga_data;
    global $default_fau_orga_faculty;
    // Additional body classes for Meta WIdget (once only language switcher)
    global $is_sidebar_active;

    // Wenn CMS-Workflow vorhanden und aktiviert ist.
    if (is_workflow_translation_active()) {
        if (is_active_sidebar('language-switcher')) {
            $classes[] = 'active-meta-widget';
        }
    } // Wenn das Widget des RRZE-Multilang-Plugins vorhanden ist.
    elseif (apply_filters('rrze_multilang_widget_enabled', false)) {
        if (is_active_sidebar('language-switcher')) {
            $classes[] = 'active-meta-widget';
        }
    }

    if (function_exists('wpel_init')) {
        $classes[] = 'wp-external-links';
    }
    $posttype = get_post_type();



    if ($posttype == 'page') {
        $page_sidebar = get_theme_mod('advanced_page_sidebar_wpsidebar');
        if ($page_sidebar && is_active_sidebar($defaultoptions['advanced_page_sidebar_wpsidebar_id'])) {
            $is_sidebar_active = true;
            $classes[]         = 'page-sidebar';
        } else {
            global $post;
            $sidebarfilled = false;
            $titleup = get_post_meta($post->ID, 'sidebar_title_above', true);
            $textup = get_post_meta($post->ID, 'sidebar_text_above', true);
            $titledown = get_post_meta($post->ID, 'sidebar_title_below', true);
            $textdown = get_post_meta($post->ID, 'sidebar_text_below', true);

            if ($titleup || $titledown || $textup || $textdown) {
                $sidebarfilled = true;
            } else {
                $foundlink = 0;
                $linkblock1_number = get_theme_mod('advanced_page_sidebar_linkblock1_number');
                if ($linkblock1_number > 0) {
                    for ($i = 1; $i <= $linkblock1_number; $i++) {
                        $name = 'fauval_linkblock1_link' . $i;
                        $urlname = $name . '_url';
                        $oldurl =  get_post_meta($post->ID, $urlname, true);
                        $oldid =  get_post_meta($post->ID, $name, true);
                        if ($oldid || !empty($oldurl)) {
                            $foundlink = 1;
                        }
                    }
                }
                if ($foundlink) {
                    $sidebarfilled = true;
                } else {
                    $linkblock2_number = get_theme_mod('advanced_page_sidebar_linkblock2_number');
                    if ($linkblock2_number > 0) {
                        for ($i = 1; $i <= $linkblock2_number; $i++) {
                            $name = 'fauval_linkblock2_link' . $i;
                            $urlname = $name . '_url';
                            $oldurl =  get_post_meta($post->ID, $urlname, true);
                            $oldid =  get_post_meta($post->ID, $name, true);
                            if ($oldid || !empty($oldurl)) {
                                $foundlink = 1;
                            }
                        }
                    }

                    if ($foundlink) {
                        $sidebarfilled = true;
                    } else {
                        $sidebar_personen = get_post_meta($post->ID, 'sidebar_personen', true);
                        if ($sidebar_personen) {
                            $sidebarfilled = true;
                        }
                    }
                }
            }


            if ($sidebarfilled) {
                $is_sidebar_active = true;
                $classes[]         = 'page-sidebar';
            }
        }
    }

    if (($posttype == 'post') && is_active_sidebar('news-sidebar')) {
        $is_sidebar_active = true;
        $classes[]         = 'post-sidebar';
    }
    if ($is_sidebar_active) {
        $classes[] = 'with-sidebar';
    }


    $website_usefaculty = $defaultoptions['website_usefaculty'];

    if ($defaultoptions['debugmode']) {
        $debug_website_fakultaet = get_theme_mod('debug_website_fakultaet');
        if (isset($debug_website_fakultaet)) {
            $website_usefaculty = $debug_website_fakultaet;
        }
    }


    if ((isset($website_usefaculty)) && (in_array($website_usefaculty, $default_fau_orga_faculty))) {
        $classes[] = 'faculty-' . $website_usefaculty;
    }
    $website_type = get_theme_mod('website_type');
    /*
	 * website_type:
	 *  0 = Fakultaetsportal oder zentrale Einrichtung
	 *	=> Nur Link zur FAU, kein Link zur Fakultät
	 *	   Link zur FAU als Text, da FAU-Logo bereits Teil des
	 *         Fakultätslogos
	 *  1 = Lehrstuhl oder eine andere Einrichtung die einer Fakultät zugeordnet ist
	 *	=> Link zur FAU und Link zur Fakultät,
	 *         Link zur FAU als Grafik, Link zur Fakultät als Text (lang oder kurz nach Wahl)
	 *  2 = Sonstige Einrichtung, die nicht einer Fakultät zugeordnet sein muss
	 *	=> Nur Link zur FAU, kein Link zur Fakultät
	 *	   Link zur FAU als Grafik (das ist der Unterschied zur Option 0)
	 *  3 = Koopertation mit Externen (neu ab 1.4)
	 *	=> Kein Link zur FAU
	 *  -1 = FAU-Portal (neu ab 1.4, nur für zukunftigen Bedarf)
	 *	=> Kein Link zur FAU, aktiviert 4 Spalten unter HERO
	 *
	 * 'website_usefaculty' = ( nat | phil | med | tf | rw )
	 *  Wenn gesetzt, wird davon ausgegangen, dass die Seite
	 *  zu einer Fakultät gehört; Daher werden website_type-optionen auf
	 *  0 und 2 reduziert. D.h.: Immer LInk zur FAU, keine Kooperationen.
	 *
	 */

    $classes[] = 'fau-theme';
    if ($website_type == -1) {
        $classes[] = 'fauorg-home';
    } elseif ($website_type == 0) {
        $classes[] = 'fauorg-fakultaet';
        $classes[] = 'fauorg-unterorg';
    } elseif ($website_type == 1) {
        $classes[] = 'fauorg-fakultaet';
    } elseif ($website_type == 2) {
        $classes[] = 'fauorg-sonst';
    } elseif ($website_type == 3) {
        $classes[] = 'fauorg-kooperation';
    } else {
        $classes[] = 'fauorg-fakultaet';
        $classes[] = 'fauorg-unterorg';
    }


    $show_customlogo = false;
    $custom_logo_id  = get_theme_mod('custom_logo');
    $logo_src        = '';
    if ($custom_logo_id) {
        $logo            = wp_get_attachment_image_src($custom_logo_id, 'full');
        $logo_src        = $logo[0];
        $show_customlogo = true;
        if (!empty($logo_src)) {
            if (preg_match('/\/themes\/FAU\-[a-z]+\/img\/logos\//i', $logo_src,  $match)) {
                $show_customlogo = false;
                // Version 2: Check for old Images in theme, that was chosen in customizer, but removed
                // from code later. In this case, ignore this entry.
            }
        }
    }
    if ($show_customlogo === false) {
        $classes[] = 'nologo';
    }


    $sitetitle = get_bloginfo('title');
    if (strlen($sitetitle) > 50) {
        $classes[] = 'longtitle';
    }


    if (('' != get_theme_mod('slider-autoplay')) && (true == get_theme_mod('slider-autoplay'))) {
        $classes[] = 'slider-autoplay';
    } else {
        $classes[] = 'slider-noplay';
    }

    if ('fade' == get_theme_mod('slider-animation')) {
        $classes[] = 'slider-fade';
    }



    if (('' != get_theme_mod('default_display_thumbnail_3_2')) && (true == get_theme_mod('default_display_thumbnail_3_2'))) {
        $classes[] = 'blogroll-image-3-2';
    }
    $classes[] = 'mainnav-forceclick';
    

    $classes[] = 'mainnav-plainview';

    if (false == get_theme_mod('advanced_header_banner_display_slogan')) {
        $classes[] = 'hide-banner-slogan';
    }
    if (false == get_theme_mod('advanced_header_banner_display_title')) {
        $classes[] = 'hide-banner-title';
    }




    if (is_active_sidebar('search-sidebar')) {
        $classes[] = 'with-search-sidebar';
    }

    if (('' != get_theme_mod('advanced_template_hr_linecolor')) && (true == get_theme_mod('advanced_template_hr_linecolor'))) {
        $val = "hr-default-".esc_attr(get_theme_mod('advanced_template_hr_linecolor'));
        $classes[] = $val;
    }
    
    
    return $classes;
}

add_filter('body_class', 'fau_body_class');

/*-----------------------------------------------------------------------------------*/
/* Mark sidebar as used. This will add the class with-sidebar in the body class
/*-----------------------------------------------------------------------------------*/
function fau_use_sidebar($activate)
{
    global $is_sidebar_active;
    if ($activate) {
        $is_sidebar_active = 1;
    } else {
        $is_sidebar_active = 0;
    }

    return $is_sidebar_active;
}


function fau_prefix_custom_site_icon_size($sizes) {
    $sizes[] = 64;
    $sizes[] = 120;

    return $sizes;
}
add_filter('site_icon_image_sizes', 'fau_prefix_custom_site_icon_size');

function fau_prefix_custom_site_icon_tag($meta_tags) {
    $meta_tags[] = sprintf('<link rel="icon" href="%s" sizes="64x64" />', esc_url(get_site_icon_url(null, 64)));
    $meta_tags[] = sprintf('<link rel="icon" href="%s" sizes="120x120" />', esc_url(get_site_icon_url(null, 120)));

    return $meta_tags;
}

add_filter('site_icon_meta_tags', 'fau_prefix_custom_site_icon_tag');
/*-----------------------------------------------------------------------------------*/
/* Define errorpages 401 and 403
/*-----------------------------------------------------------------------------------*/
function custom_error_pages() {
    global $wp_query;

    if (isset($_REQUEST['status']) && $_REQUEST['status'] == 403) {
        $wp_query->is_404      = false;
        $wp_query->is_page     = true;
        $wp_query->is_singular = true;
        $wp_query->is_single   = false;
        $wp_query->is_home     = false;
        $wp_query->is_archive  = false;
        $wp_query->is_category = false;
        add_filter('wp_title', 'custom_error_title', 65000, 2);
        add_filter('body_class', 'custom_error_class');
        status_header(403);
        get_template_part('403');
        exit;
    }

    if (isset($_REQUEST['status']) && $_REQUEST['status'] == 401) {
        $wp_query->is_404      = false;
        $wp_query->is_page     = true;
        $wp_query->is_singular = true;
        $wp_query->is_single   = false;
        $wp_query->is_home     = false;
        $wp_query->is_archive  = false;
        $wp_query->is_category = false;
        add_filter('wp_title', 'custom_error_title', 65000, 2);
        add_filter('body_class', 'custom_error_class');
        status_header(401);
        get_template_part('401');
        exit;
    }
}

function custom_error_title($title = '', $sep = '') {
    if (isset($_REQUEST['status']) && $_REQUEST['status'] == 403) {
        return __('Zugriff nicht gestattet', 'fau');
    }

    if (isset($_REQUEST['status']) && $_REQUEST['status'] == 401) {
        return __('Anmeldung fehlgeschlagen', 'fau');
    }
}


function custom_error_class($classes) {
    if (isset($_REQUEST['status']) && $_REQUEST['status'] == 403) {
        $classes[] = "error403";

        return $classes;
    }

    if (isset($_REQUEST['status']) && $_REQUEST['status'] == 401) {
        $classes[] = "error401";

        return $classes;
    }
}

add_action('wp', 'custom_error_pages');


/*-----------------------------------------------------------------------------------*/
/*  Anzeige Suchergebnisse
/*-----------------------------------------------------------------------------------*/
function fau_display_search_resultitem() {
    global $post;
    global $defaultoptions;

    $output       = '';
    $withthumb    = get_theme_mod('search_display_post_thumbnails');
    $withcats     = get_theme_mod('search_display_post_cats');
    $withtypenote = get_theme_mod('search_display_typenote');
    $attachment   = array();

    if (isset($post) && isset($post->ID)) {

        $link     = get_post_meta($post->ID, 'external_link', true);
        $link     = esc_url(trim($link));
        $external = 0;
        if (isset($link) && (filter_var($link, FILTER_VALIDATE_URL))) {
            $external = 1;
        } else {
            $link = get_permalink($post->ID);
        }

        $type      = get_post_type();
        $typeclass = "res-" . $type;
        $output    .= '<li class="search-result ' . $typeclass . '">' . "\n";
        $output    .= "\t<h3><a ";
        if ($external == 1) {
            $output .= 'class="ext-link" ';
        }
        $output .= "href=\"" . $link . "\">" . get_the_title() . "</a></h3>\n";

        $typeinfo = get_post_type_object($type);


        if ($type == 'post') {
            $typestr = '<div class="search-meta">';
            if ($withtypenote == true) {
                if ($external == 1) {
                    $typestr .= '<span class="post-meta-news-external"> ';
                    $typestr .= __('Externer ', 'fau');
                } else {
                    $typestr .= '<span class="post-meta-news"> ';
                }

                $typestr .= __('Beitrag', 'fau');
                $typestr .= '</span>';
            }
            $categories = get_the_category();
            $separator  = ', ';
            $thiscatstr = '';
            if (($withcats == true) && ($categories)) {
                $typestr .= '<span class="post-meta-category"> ';
                $typestr .= __('Kategorie', 'fau');
                $typestr .= ': ';
                foreach ($categories as $category) {
                    $thiscatstr .= '<a href="' . get_category_link($category->term_id) . '">' . $category->cat_name . '</a>' . $separator;
                }
                $typestr .= trim($thiscatstr, $separator);
                $typestr .= '</span> ';
            }
            $istopevent         = get_post_meta($post->ID, 'topevent_active', true);
            $topevent_date      = get_post_meta($post->ID, 'topevent_date', true);
            $topevent_date_time = 0;
            if ($topevent_date) {
                $topevent_date_time = strtotime($topevent_date);
            }
            if (($topevent_date_time > 0) && ($istopevent == true)) {
                $typestr .= '<span class="post-meta-date"> ';
                $typestr .= date_i18n(get_option('date_format'), strtotime($topevent_date));
                $typestr .= ' (';
                $typestr .= __('Veranstaltungshinweis', 'fau');
                $typestr .= ')';
                $typestr .= '</span>';
            } else {
                $typestr .= '<span class="post-meta-date"> ';
                $typestr .= get_the_date();
                $typestr .= '</span>';
            }
            $typestr .= '</div>' . "\n";
        } elseif ($type == 'person') {
            $typestr = '<div class="search-meta">';
            $typestr .= '<span class="post-meta-kontakt"> ';
            $typestr .= $typeinfo->labels->singular_name;
            $typestr .= '</span>';
            $typestr .= '</div>' . "\n";
        } elseif ($type == 'event') {
            $typestr = '<div class="search-meta">';
            $typestr .= '<span class="post-meta-event"> ';
            $typestr .= __('Veranstaltungshinweis', 'fau');
            $typestr .= '</span>';
            $typestr .= '</div>' . "\n";
        } elseif ($type == 'attachment') {

            $attachment = wp_prepare_attachment_for_js($post->ID);
            $filesize   = isset($attachment['filesizeHumanReadable']) ? $attachment['filesizeHumanReadable'] : '';
            $filesize   = (isset($attachment['filesize']) && (!isset($filesize))) ? $attachment['filesize'] : $filesize;
            $filesize   = (isset($attachment['filesizeInBytes']) && (!isset($filesize))) ? $attachment['filesizeInBytes'] . " Bytes" : $filesize;

            $filetype = wp_check_filetype($attachment['url']);

            $image = wp_get_attachment_image($attachment['id']);
            if (!empty($image)) {
                $attachment['image'] = $image;
            }

            $typestr = '<div class="search-meta">';
            $typestr .= '<span class="post-meta-attachment">';
            $typestr .= ' <span class="dateityp">' . $filetype['ext'] . '</span> ';
            $typestr .= __('Datei', 'fau');
            $typestr .= '</span>';

            $typestr .= ' <span class="post-meta-date"> ';
            $typestr .= get_the_date();

            if (get_the_date() != get_the_modified_date()) {
                $typestr .= ' (' . __('Erstellungsdatum', 'fau') . ')';
                $typestr .= '</span>';
                $typestr .= ' <span class="post-meta-date"> ';
                $typestr .= get_the_modified_date();
                $typestr .= ' (' . __('Letzte Änderung', 'fau') . ')';
            }
            $typestr .= '</span>';


            $typestr .= ' <span class="download">';
            $typestr .= ' <a href="' . fau_esc_url($attachment['url']) . '">' . __('Download', 'fau') . '</a>';

            $typestr .= ' <span class="filesize">(<span class="screen-reader-text">';
            $typestr .= __('Größe', 'fau') . ': </span>' . $filesize;
            $typestr .= ')</span>';
            $typestr .= '</span>';

            $typestr .= '</div>' . "\n";
        } elseif ($withtypenote == true) {
            $typestr = '<div class="search-meta">';
            $typestr .= '<span class="post-meta-defaulttype"> ';
            $typestr .= $typeinfo->labels->singular_name;
            $typestr .= '</span>';

            $typestr .= ' <span class="post-meta-date"> ';
            $typestr .= get_the_modified_date();
            $typestr .= ' (' . __('Letzte Änderung', 'fau') . ')';
            $typestr .= '</span>';


            $typestr .= '</div>' . "\n";
        } else {
            $typestr = '';
        }

        if (!empty($typestr)) {
            $output .= "\t" . $typestr . "\n";
        }
        $output .= "\t";


        if (($type == 'person') && (class_exists('FAU_Person_Shortcodes'))) {
            $output .= '<div class="search-row">' . "\n";
            if (($withthumb == true) && (has_post_thumbnail($post->ID))) {

                $output .= "\t\t" . '<div class="searchresult-image">' . "\n";
                $output .= '<a href="' . $link . '" class="news-image"';
                if ($external == 1) {
                    $output .= ' ext-link';
                }
                $output .= '">';


                $post_thumbnail_id = get_post_thumbnail_id($post->ID);

                $imagehtml = fau_get_image_htmlcode($post_thumbnail_id, 'rwd-480-3-2', '');
                if (fau_empty($imagehtml)) {
                    $imagehtml = fau_get_image_fallback_htmlcode('rwd-480-3-2', '');
                }
                $output .= $imagehtml;
                $output .= '</a>';

                $output .= "\t\t" . '</div>' . "\n";
            }
            $output .= "\t\t" . '<div class="searchresult-imagetext">' . "\n";
            $output .= FAU_Person_Shortcodes::fau_person(array(
                "id"       => $post->ID,
                'format'   => 'kompakt',
                'showlink' => 0,
                'showlist' => 0,
                'hide'     => 'bild'
            ));
            $output .= "\t</div> <!-- /row -->\n";
        } elseif ($type == 'attachment') {
            $output .= '<div class="search-row">' . "\n";
            if ($withthumb == true) {

                $output .= "\t\t" . '<div class="searchresult-image ' . $type . '">' . "\n";
                if (!empty($attachment['image'])) {
                    $output .= $attachment['image'];
                } else {
                    $output .= fau_get_image_fallback_htmlcode('rwd-480-3-2', '', 'fallback');
                }

                $output .= "\t\t" . '</div>' . "\n";
            }
            $output .= "\t\t" . '<div class="searchresult-imagetext">' . "\n";
            $output .= "\t\t" . '<p><em>' . "\n";
            $output .= "\t\t\t" . $attachment['caption'];
            $output .= "\t\t" . '</em></p>' . "\n";
            $output .= "\t\t" . '<p>' . "\n";
            $output .= "\t\t\t" . $attachment['description'];
            $output .= "\t\t" . '</p>' . "\n";



            $output .= "\t</div> <!-- /row -->\n";
        } elseif ($type == 'studienangebot') {
            $output .= "\t\t" . '<p>' . "\n";
            $output .= fau_custom_excerpt(
                $post->ID,
                get_theme_mod('default_search_excerpt_length'),
                false,
                '',
                true,
                get_theme_mod('search_display_excerpt_morestring')
            );

            $output .= "\t\t\t" . '</p>' . "\n";
        } else {
            $output .= '<div class="search-row">' . "\n";
            if (($withthumb == true) && (has_post_thumbnail($post->ID))) {

                $output .= "\t\t" . '<div class="searchresult-image">' . "\n";
                $output .= '<a href="' . $link . '" class="news-image"';
                if ($external == 1) {
                    $output .= ' ext-link';
                }
                $output .= '">';


                $post_thumbnail_id = get_post_thumbnail_id($post->ID);

                $imagehtml = fau_get_image_htmlcode($post_thumbnail_id, 'rwd-480-3-2', '');
                if (fau_empty($imagehtml)) {
                    $imagehtml = fau_get_image_fallback_htmlcode('rwd-480-3-2', '');
                }
                $output .= $imagehtml;
                $output .= '</a>';

                $output .= "\t\t" . '</div>' . "\n";
            }
            $output .= "\t\t" . '<div class="searchresult-imagetext">' . "\n";
            $output .= "\t\t" . '<p>' . "\n";
            $output .= fau_custom_excerpt(
                $post->ID,
                get_theme_mod('default_search_excerpt_length'),
                false,
                '',
                true,
                get_theme_mod('search_display_excerpt_morestring')
            );
            $output .= "\t\t\t" . '</p>' . "\n";
            if (get_theme_mod('search_display_continue_arrow')) {

                $output .= '<div class="continue">';
                $output .= fau_create_readmore($link, '', $external, true, __('Seite aufrufen', 'fau'));
                $output .= '</div>';
            }

            $output .= "\t</div> <!-- /row -->\n";
        }


        $output .= "</li>\n";
    }

    return $output;
}

/*-----------------------------------------------------------------------------------*/
/*  Blogroll
/*-----------------------------------------------------------------------------------*/
function fau_display_news_teaser($id = 0, $withdate = false, $hstart = 2, $hidemeta = true, $overwrite_thememods = false) {
    if ($id == 0) {
        return;
    }
    $withcat = get_theme_mod('show_cat_on');
    $post   = get_post($id);
    $output = '';
    if ($post) {
        $categories = get_the_category();
        $vidpod_auth = get_post_meta($post->ID, 'vidpod_auth', true);
        
        
        $withvideopost_autor = false;
        if ($hidemeta) {
            $withcat = false;
        }
        if ($overwrite_thememods===false) {
            if (('' != get_theme_mod('show_date_on')) && (true == get_theme_mod('show_date_on'))) {
                 $withdate = true;
                 $hidemeta = false;
            }
            if (('' != get_theme_mod('show_cat_on')) && (true == get_theme_mod('show_cat_on'))) { 
                if ($categories) {   
                    $withcat = true;
                    $hidemeta = false;
                }
            }          
        }
        if ($vidpod_auth !=null){
            $withvideopost_autor = true;
            $hidemeta = false;
        }
        
        
        if (!is_int($hstart)) {
            $hstart = 2;
        } elseif (($hstart < 1) || ($hstart > 6)) {
            $hstart = 2;
        }
        $arialabelid = "aria-" . $post->ID . "-" . random_int(10000, 30000);

        // get value for zig zag display format
        $default_zig_zag_blogroll = get_theme_mod('default_zig_zag_blogroll');
        $zigzag_class = $default_zig_zag_blogroll ? 'zigzag ' : '';
        $articleclass = "news-item";
        // add random key, due to the possible use of blogrolls of the news. The same article can be displayed
        // more times on the same page. This would result in an wcag/html error, cause the uniq id would be used more as one time
        $output   .= '<article '; // class="news-item" ';
        if (is_sticky($post->ID)) {
            $articleclass .= ' sticky';
        }
        $output   .= 'class="'.$articleclass.' '.$zigzag_class . esc_attr(implode(' ', get_post_class('', $post->ID))) . '"';
        $output   .= ' aria-labelledby="' . $arialabelid . '" itemscope itemtype="http://schema.org/NewsArticle">';
        $link     = get_post_meta($post->ID, 'external_link', true);
        $link     = esc_url(trim($link));
        $external = false;
        if (isset($link) && (filter_var($link, FILTER_VALIDATE_URL))) {
            $external = true;
        } else {
            $link = fau_esc_url(get_permalink($post->ID));
        }

        $output .= '<h' . $hstart . ' id="' . $arialabelid . '" itemprop="headline">';
        $output .= '<a itemprop="url" ';
        if ($external) {
            $output .= 'class="ext-link" rel="canonical" ';
        }
        $output .= 'href="' . $link . '">' . get_the_title($post->ID) . '</a>';
        $output .= "</h" . $hstart . ">";

        $output      .= '<div class="teaser-row">';
        $show_thumbs = get_theme_mod('default_postthumb_always');


        if ((has_post_thumbnail($post->ID)) || ($show_thumbs == true)) {
            $usefallbackthumb = false;
            $post_thumbnail_id = get_post_thumbnail_id($post->ID);
            $pretitle          = get_theme_mod('advanced_blogroll_thumblink_alt_pretitle');
            $posttitle         = get_theme_mod('advanced_blogroll_thumblink_alt_posttitle');
            $fallback_svgfaulogo = get_theme_mod('fallback_svgfaulogo');
            
            $alttext           = $pretitle . get_the_title($post->ID) . $posttitle;
            $alttext           = esc_html($alttext);

            $imagehtml = fau_get_image_htmlcode($post_thumbnail_id, 'rwd-480-3-2', $alttext, '', array('itemprop' => 'thumbnailUrl'));
            if (fau_empty($imagehtml)) {
                $imagehtml = fau_get_image_fallback_htmlcode('post-thumb', $alttext, '',  array('itemprop' => 'thumbnailUrl'));
                $ownfallback = get_theme_mod('default_postthumb_image');
                if ($ownfallback) {
                    $usefallbackthumb = false;
                } else {
                    $usefallbackthumb = true;
                }
            }

            $output .= '<div class="thumbnailregion';

            if (metadata_exists('post', $post->ID, 'vidpod_url') && shortcode_exists('fauvideo')) {
                 // check if the post is using Video/Podcast
                $output .= ' vidpod-thumb">';
                $vidpod_url = get_post_meta($post->ID, 'vidpod_url', true);
                $output .= do_shortcode('[fauvideo url="' . $vidpod_url . '"]');
            } else {
                if (($usefallbackthumb) && ($fallback_svgfaulogo)) {
                    $output .= ' fallback';
                }
                $output .= '">';
                $output .= '<div aria-hidden="true" role="presentation" class="passpartout" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">';
                $output .= '<a href="' . $link . '" tabindex="-1" class="news-image';
                if ($external) {
                    $output .= ' ext-link';
                }
                $output .= '">';
                $output  .= $imagehtml;
                $output  .= '</a>';
                $imgmeta = wp_get_attachment_image_src($post_thumbnail_id, 'rwd-480-3-2');
                if ($imgmeta) {
                    $output .= '<meta itemprop="url" content="' . fau_make_absolute_url($imgmeta[0]) . '">';
                }
                global $defaultoptions;
                $output .= '<meta itemprop="width" content="' . $defaultoptions['default_image_sizes']['rwd-480-3-2']['width'] . '">';
                $output .= '<meta itemprop="height" content="' . $defaultoptions['default_image_sizes']['rwd-480-3-2']['height'] . '">';
                $output .= '</div>';
            }
            $output .= '</div>';
            $output .= '<div class="teaserregion">';
        }else         if ((metadata_exists('post', $post->ID, 'vidpod_url') && shortcode_exists('fauvideo')) && !((has_post_thumbnail($post->ID)) || ($show_thumbs == true)) ){
            $usefallbackthumb = false;
            $output .= '<div class="thumbnailregion vidpod-thumb">';
            $vidpod_url = get_post_meta($post->ID, 'vidpod_url', true);
            $output .= do_shortcode('[fauvideo url="' . $vidpod_url . '"]');
            $output .= '</div>';
            $output .= '<div class="teaserregion">';
        }

        
        else {
            $output .= '<div class="fullwidthregion">';
        }
        $output   .= '<p itemprop="description">';
        $cuttet   = false;
        if (post_password_required()) {
            $abstract = __('Dieser Inhalt ist passwortgeschützt.', 'fau');
        } else {
            $abstract = get_post_meta($post->ID, 'abstract', true);
            if (strlen(trim($abstract)) < 3) {
                $abstract = fau_custom_excerpt($post->ID, get_theme_mod('default_anleser_excerpt_length'), false, '', true);
            }
        }
        $output .= $abstract;
        $output .= '</p>';
        $output .= '</div>';
        $output .= "</div>";

        // news-meta
        $schemaauthor = get_theme_mod('contact_address_name') . " " . get_theme_mod('contact_address_name2');
        if (!fau_empty($schemaauthor)) {
            $output .= '<meta itemprop="author" content="' . esc_attr($schemaauthor) . '">';
        }
        if ($vidpod_auth !=null) {
            $output .= '<meta itemprop="producer" content="' . esc_attr($vidpod_auth) . '">';
        }
        if ($hidemeta) {
             // display meta informations that we need for structured data as <meta> tags       
            $output .= '<meta itemprop="datePublished" content="' . esc_attr(get_post_time('c')) . '">';
            $output .= '<meta itemprop="dateModified" content="' . esc_attr(get_the_modified_time('c')) . '">';
        } else {
            // displays meta informations like date, category, tags, autornames with visible tags
            $output .= '<div class="news-meta">';
            
            if ($withdate) {
                $output .= '<span class="news-meta-date" itemprop="datePublished" content="'.esc_attr(get_post_time('c')).'"> '.get_the_date('',
                    $post->ID)." </span>";
            }
            if ($withcat) {                
                $separator  = ', ';
                $thiscatstr = '';
                $typestr = '<span class="news-meta-categories"> ';
                $typestr .= __('Kategorie', 'fau').': ';
                foreach ($categories as $category) {
                    $thiscatstr .= '<a href="' . get_category_link($category->term_id) . '">' . $category->cat_name . '</a>' . $separator;
                }
                $typestr .= trim($thiscatstr, $separator);
                $typestr .= '</span> ';
                
                $output .= $typestr;
            }
            if ($withvideopost_autor){
                $output .= '<span class="news-meta-videopost-author"> ';      
                $output .= __('Produzent', 'fau').': ';
                $output .= esc_attr($vidpod_auth);      
                $output .= '</span>';      
            }

            $display_continue_link = get_theme_mod('default_display_continue_link');
            if ($display_continue_link) {
                $output .= '<div class="continue">';
                $output .= fau_create_readmore($link, get_the_title($post->ID), $external, true);
                $output .= '</div>';
            }

            $output .= '</div>';
        }

        if (!$external) {
            $output .= fau_create_schema_publisher();
        }
        $output .= "</article>";
    }

    return $output;
}

/*-----------------------------------------------------------------------------------*/
/*  Create String with custom excerpt
/*-----------------------------------------------------------------------------------*/
function fau_custom_excerpt(
    $id = 0,
    $length = 0,
    $withp = true,
    $class = '',
    $withmore = false,
    $morestr = '',
    $continuenextline = false
) {
    if ($length == 0) {
        $length = get_theme_mod('default_excerpt_length');
    }

    if (fau_empty($morestr)) {
        $morestr = get_theme_mod('default_excerpt_morestring');
    }
    $excerpt = "";
    //  $excerpt = get_the_excerpt($id); // get_post_field('post_excerpt',$id);
    // get_the_excerpt  nmacht Probleme, wenn text Shortcode enthält, daher direkte Lösung

    $excerpt = get_post_field('post_excerpt', $id);
    if (mb_strlen(trim($excerpt)) < 5) {
        $excerpt = get_post_field('post_content', $id);
    }
    $excerpt = strip_shortcodes($excerpt);
    // removes most shortcodes
    $excerpt = preg_replace('#\[[^\]]+\]#', '', $excerpt);
    // in case strip_shortcodes doenst remove all	    
    $excerpt = preg_replace('/\s+(https?:\/\/www\.youtube[\/a-z0-9\.\-\?&;=_]+)/i', '', $excerpt);
    // remove YT embeds
    $excerpt = strip_tags($excerpt, get_theme_mod('custom_excerpt_allowtags'));
    // remove most tags, but not those who are allowed

    if (mb_strlen($excerpt) < 5) {
        $excerpt = '<!-- ' . __('Kein Inhalt', 'fau') . ' -->';
        return $excerpt;
    }

    $needcontinue = 0;
    if (mb_strlen($excerpt) > $length) {
        $str          = mb_substr($excerpt, 0, $length);
        $needcontinue = 1;
    } else {
        $str = $excerpt;
    }

    $the_str = '';
    if ($withp) {
        $the_str .= '<p';
        if (!fau_empty($class)) {
            $the_str .= ' class="' . $class . '"';
        }
        $the_str .= '>';
    }
    $the_str .= $str;

    if (($needcontinue == 1) && ($withmore == true)) {
        if ($continuenextline) {
            $the_str .= '<br>';
        }
        $the_str .= $morestr;
    }

    if ($withp) {
        $the_str .= '</p>';
    }

    return $the_str;
}

/*-----------------------------------------------------------------------------------*/
/*  Create String for Publisher Info, used by Schema.org Microformat Data
/*-----------------------------------------------------------------------------------*/
function fau_create_schema_publisher($withrahmen = true)
{
    $out = '';
    if ($withrahmen) {
        $out .= '<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">';
    }
    $header_image = get_header_image();
    if ($header_image) {
        $out .= '<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">';
        $out .= '<meta itemprop="url" content="' . fau_make_absolute_url($header_image) . '">';
        $out .= '<meta itemprop="width" content="' . get_custom_header()->width . '">';
        $out .= '<meta itemprop="height" content="' . get_custom_header()->height . '">';
        $out .= '</div>';
    }
    $out .= '<meta itemprop="name" content="' . get_bloginfo('title') . '">';
    $out .= '<meta itemprop="url" content="' . home_url('/') . '">';
    if ($withrahmen) {
        $out .= '</div>';
    }

    return $out;
}


/*-----------------------------------------------------------------------------------*/
/*  Weiterlesen-Link einheitlich gestalten fuer verschiedene Ausgaben
/*-----------------------------------------------------------------------------------*/
function fau_create_readmore($url, $linktitle = '', $external = false, $ariahide = true, $linktext = '')
{
    $output = '';

    if (empty($linktext)) {
        $linktext = __('Weiterlesen', 'fau');
    }
    if (isset($url)) {

        $link   = fau_esc_url($url);
        $output .= '<a';

        if ($ariahide) {
            $output .= ' aria-hidden="true" tabindex="-1"';
        }
        $class = 'read-more-link';
        if ($external) {
            $class .= ' ext-link';
        }

        $output .= ' class="' . $class . '"';
        $output .= ' href="' . $link . '"';
        if (!empty($linktitle)) {
            $output .= ' title="' . $linktitle . '"';
        }
        $output .= '>';
        $output .= $linktext;
        $output .= '</a>';
    }

    return $output;
}

/*-----------------------------------------------------------------------------------*/
/* Array als Table ausgeben
/*-----------------------------------------------------------------------------------*/
function fau_array2table($array, $table = true)
{
    $out         = '';
    $tableHeader = '';
    foreach ($array as $key => $value) {
        $out .= '<tr>';
        $out .= "<th>$key</th>";
        if (is_array($value)) {
            if (!isset($tableHeader)) {
                $tableHeader =
                    '<th>' .
                    implode('</th><th>', array_keys($value)) .
                    '</th>';
            }
            array_keys($value);
            $out .= "<td>";
            $out .= fau_array2table($value, true);
            $out .= "</td>";
        } else {
            $out .= "<td>$value</td>";
        }
        $out .= '</tr>';
    }

    if ($table) {
        return '<table>' . $tableHeader . $out . '</table>';
    } else {
        return $out;
    }
}

/*-----------------------------------------------------------------------------------*/
/* Get Image Attributs / Extends https://codex.wordpress.org/Function_Reference/wp_get_attachment_metadata
/*-----------------------------------------------------------------------------------*/
function fau_get_image_attributs($id = 0)
{
    $precopyright = ''; // __('Bild:','fau').' ';
    if ($id == 0) {
        return;
    }

    $meta = get_post_meta($id);
    if (!isset($meta)) {
        return;
    }

    $result = array();
    if (isset($meta['_wp_attachment_image_alt'][0])) {
        $result['alt'] = trim(strip_tags($meta['_wp_attachment_image_alt'][0]));
    } else {
        $result['alt'] = "";
    }

    if (isset($meta['_wp_attachment_metadata']) && is_array($meta['_wp_attachment_metadata'])) {
        $data = unserialize($meta['_wp_attachment_metadata'][0]);
        if (isset($data['image_meta']) && is_array($data['image_meta'])) {
            if (isset($data['image_meta']['copyright'])) {
                $result['copyright'] = trim(strip_tags($data['image_meta']['copyright']));
            }
            if (isset($data['image_meta']['author'])) {
                $result['author'] = trim(strip_tags($data['image_meta']['author']));
            } elseif (isset($data['image_meta']['credit'])) {
                $result['credit'] = trim(strip_tags($data['image_meta']['credit']));
            }
            if (isset($data['image_meta']['title'])) {
                $result['title'] = $data['image_meta']['title'];
            }
            if (isset($data['image_meta']['caption'])) {
                $result['caption'] = $data['image_meta']['caption'];
            }
        }
        if (isset($data['width'])) {
            $result['orig_width'] = $data['width'];
        }
        if (isset($data['height'])) {
            $result['orig_height'] = $data['height'];
        }
        if (isset($data['file'])) {
            $result['orig_file'] = $data['file'];
        }
    }
    if (isset($meta['_wp_attached_file']) && is_array($meta['_wp_attached_file'])) {
        $result['attachment_file'] = $meta['_wp_attached_file'][0];
    }

    $attachment        = get_post($id);
    $result['excerpt'] = $result['credits'] = $result['description'] = $result['title'] = '';
    if (isset($attachment)) {

        if (isset($attachment->post_excerpt)) {
            $result['excerpt'] = trim(strip_tags($attachment->post_excerpt));
        }
        if (isset($attachment->post_content)) {
            $result['description'] = trim(strip_tags($attachment->post_content));
        }
        if (isset($attachment->post_title) && (empty($result['title']))) {
            $result['title'] = trim(strip_tags($attachment->post_title));
        }
    }
    $info_credits = get_theme_mod('advanced_images_info_credits');
    if ($info_credits) {

        if (isset($result['description']) && (!empty($result['description']))) {
            $result['credits'] = $result['description'];
        } elseif (isset($result['copyright']) && (!empty($result['copyright']))) {
            $result['credits'] = $precopyright . ' ' . $result['copyright'];
        } elseif (!empty($result['author'])) {
            $result['credits'] = $precopyright . ' ' . $result['author'];
        } elseif (!empty($result['credit'])) {
            $result['credits'] = $precopyright . ' ' . $result['credit'];
        } else {
            if (!empty($result['caption'])) {
                $result['credits'] = $result['caption'];
            } elseif (!empty($result['excerpt'])) {
                $result['credits'] = $result['excerpt'];
            }
        }
    } else {

        if (!empty($result['copyright'])) {
            $result['credits'] = $precopyright . ' ' . $result['copyright'];
        } elseif (!empty($result['author'])) {
            $result['credits'] = $precopyright . ' ' . $result['author'];
        } elseif (!empty($result['credit'])) {
            $result['credits'] = $precopyright . ' ' . $result['credit'];
        } else {
            if (!empty($result['description'])) {
                $result['credits'] = $result['description'];
            } elseif (!empty($result['caption'])) {
                $result['credits'] = $result['caption'];
            } elseif (!empty($result['excerpt'])) {
                $result['credits'] = $result['excerpt'];
            }
        }
    }

    return $result;
}

/*-----------------------------------------------------------------------------------*/
/* Get category links for front page
/*-----------------------------------------------------------------------------------*/
function fau_get_category_links($cateid = 0)
{
    global $defaultoptions;

    if ($cateid == 0) {
        $cateid = get_theme_mod('start_link_news_cat');
    }
    $link = get_category_link($cateid);
    if (empty($link)) {
        $cat = get_categories();
        if (is_array($cat) && (!empty($cat)) && isset($cat[0])) {
            $cateid = $cat[0]->cat_ID;
            $link   = get_category_link($cateid);
        }
    }
    $res = '';
    if (!fau_empty($link)) {
        $linktitle = get_theme_mod('start_link_news_linktitle');
        if (fau_empty($linktitle)) {
            $linktitle = $defaultoptions['start_link_news_linktitle'];
        }
      //  $link = add_query_arg('paged', 2, $link); //allways link to the second page of the category // let us remove this untill we find a better solution
        $res .= '<div class="news-more-links">' . "\n";
        $res .= "\t" . '<a class="news-more" href="' . $link . '">' . $linktitle . '</a>';
        $res .= '<a class="news-rss" href="' . get_category_feed_link($cateid) . '">' . __('RSS', 'fau') . '</a>';
        $res .= "</div>\n";
    }

    return $res;
}


/*-----------------------------------------------------------------------------------*/
/* Default Linklisten
/*-----------------------------------------------------------------------------------*/
function fau_get_defaultlinks($list = '', $ulclass = '', $ulid = '')
{
    global $default_link_liste;

    $uselist = array();
    if (is_array($default_link_liste[$list])) {
        $uselist = $default_link_liste[$list];
    } else {
        return;
    }

    $result = '';
    if (isset($uselist['_title'])) {
        $result .= '<p class="headline">' . $uselist['_title'] . '</p>';
        $result .= "\n";
    }
    $thislist = '';

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
    if (isset($thislist)) {
        $result .= '<ul';
        if (!empty($ulclass)) {
            $result .= ' class="' . $ulclass . '"';
        }
        if (!empty($ulid)) {
            $result .= ' id="' . $ulid . '"';
        }
        $result .= '>';
        $result .= $thislist;
        $result .= '</ul>';
        $result .= "\n";
    }

    return $result;
}


/*-----------------------------------------------------------------------------------*/
/* Erstellt Link zur Home-ORGA in der Meta-Nav
/*-----------------------------------------------------------------------------------*/
function fau_get_orgahomelink()
{
    global $defaultoptions;
    global $default_fau_orga_data;
    global $default_fau_orga_faculty;

    /*
         * website_type:
         *  0 = Fakultaetsportal oder zentrale Einrichtung
         *	=> Nur Link zur FAU, kein Link zur Fakultät
         *	   Link zur FAU als Text, da FAU-Logo bereits Teil des
         *         Fakultätslogos
         *  1 = Lehrstuhl oder eine andere Einrichtung die einer Fakultät zugeordnet ist
         *	=> Link zur FAU und Link zur Fakultät,
         *         Link zur FAU als Grafik, Link zur Fakultät als Text (lang oder kurz nach Wahl)
         *  2 = Sonstige Einrichtung, die nicht einer Fakultät zugeordnet sein muss
         *	=> Nur Link zur FAU, kein Link zur Fakultät
         *	   Link zur FAU als Grafik (das ist der Unterschied zur Option 0)
         *  3 = Koopertation mit Externen (neu ab 1.4)
         *	=> Kein Link zur FAU
         *  -1 = FAU-Portal (neu ab 1.4, nur für zukunftigen Bedarf)
         *	=> Kein Link zur FAU, aktiviert 4 Spalten unter HERO
         *
         * 'website_usefaculty' = ( nat | phil | med | tf | rw )
         *  Wenn gesetzt, wird davon ausgegangen, dass die Seite
         *  zu einer Fakultät gehört; Daher werden website_type-optionen auf
         *  0 und 2 reduziert. D.h.: Immer LInk zur FAU, keine Kooperationen.
         *
         */
    $result = '';

    $website_usefaculty = $defaultoptions['website_usefaculty'];

    if ($defaultoptions['debugmode']) {
        $debug_website_fakultaet = get_theme_mod('debug_website_fakultaet');
        if (isset($debug_website_fakultaet)) {
            $website_usefaculty = $debug_website_fakultaet;
        }
    }


    $isfaculty = false;
    if ((isset($website_usefaculty)) && (in_array($website_usefaculty, $default_fau_orga_faculty))) {
        $isfaculty = true;
    }

    $linkhome    = true;
    $linkhomeimg = false;


    $website_type = get_theme_mod("website_type");


    // Using if-then-else structure, due to better performance as switch
    if ($website_type == -1) {
        $linkhome    = true; // wird uber CSS unsichtbar gemacht fuer desktop und bei kleinen aufloesungen gezeigt
        $linkhomeimg = true;
    } elseif ($isfaculty) {
        $linkhomeimg = true;
    } else {
        $linkhomeimg = true;
        if ($website_type == 3) {
            $linkhome = false;
        }
    }

    $charset = fau_get_language_main();

    $homeorga = 'fau';

    $hometitle = $shorttitle = $homeurl = $linkdataset = '';

    if ((isset($default_fau_orga_data[$homeorga])) && is_array($default_fau_orga_data[$homeorga])) {
        $hometitle  = $default_fau_orga_data[$homeorga]['title'];
        $shorttitle = $default_fau_orga_data[$homeorga]['shorttitle'];
        if (isset($default_fau_orga_data[$homeorga]['homeurl_' . $charset])) {
            $homeurl = $default_fau_orga_data[$homeorga]['homeurl_' . $charset];
        } else {
            $homeurl = $default_fau_orga_data[$homeorga]['homeurl'];
        }
    } else {
        $linkhome = false;
    }


    $orgalist = '';


    if (($linkhome) && isset($homeurl)) {
        $orgalist .= '<li class="fauhome">';
        $orgalist .= '<a href="' . $homeurl . '">';

        if ($linkhomeimg) {
            $orgalist .= fau_use_svg(
                "fau-logo-2021",
                42,
                16,
                'fau',
                false,
                ['title' => 'FAU', 'desc' => __('Zur zentralen FAU Website', 'fau'), 'role' => 'img']
            );
        } else {
            $orgalist .= $shorttitle;
        }
        $orgalist .= '</a>';
        $orgalist .= '</li>' . "\n";
    }


    if (isset($orgalist) && strlen($orgalist) > 0) {
        $result .= '<ul class="orgalist">';
        $result .= $orgalist;
        $result .= '</ul>';
    }

    return $result;
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
function fau_get_toplinks($args = array(), $mode = 0)
{
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
                $class_names = '';
                //   $classes[] = 'menu-item';
                //   $classes = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
                //   $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ) ) );
                //    $class_names = ' class="' . esc_attr( $class_names ) . '"';
                $thislist .= '<li' . $class_names . '><a data-wpel-link="internal" href="' . $url . '">' . $title . '</a></li>';
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
/* Get cat id by name or slug
/*-----------------------------------------------------------------------------------*/
function fau_get_cat_ID($string)
{
    if (empty($string)) {
        return 0;
    }
    $string = esc_attr($string);
    if (is_string($string)) {
        $thisid = get_cat_ID($string);
        if ($thisid == 0) {
            $idObj = get_category_by_slug($string);
            if (false == $idObj) {
                return -1;
            }
            $thisid = $idObj->term_id;
        }

        return $thisid;
    } elseif (is_numeric($string)) {
        return $string;
    }
}

/*-----------------------------------------------------------------------------------*/
/* Get tag id
/*-----------------------------------------------------------------------------------*/
function fau_get_tag_ID($tag_name)
{
    $tag = get_term_by('name', $tag_name, 'post_tag');
    if ($tag) {
        return $tag->term_id;
    } else {
        return -1;
    }
}

/*-----------------------------------------------------------------------------------*/
/* Display blog entries as blogroll
/*-----------------------------------------------------------------------------------*/
if (!function_exists('fau_blogroll')) :
    function fau_blogroll($posttag = '', $postcat = '', $num = 4, $divclass = '', $hstart = 2, $hidemeta = false)
    {
        $posttag = $posttag ? esc_attr($posttag) : '';

        if ((!isset($posttag)) && (!isset($postcat))) {
            // kein wert gesetzt, also nehm ich die letzten Artikel
            $postcat = 0;
        } else {
            if (is_string($posttag)) {
                $posttag = fau_get_tag_ID($posttag);
            }
            $postcat = fau_get_cat_ID($postcat);
        }

        if (!is_int($num)) {
            $num = 4;
        }
        if (!is_int($hstart)) {
            $hstart = 2;
        }
        $divclass = $divclass ? esc_attr($divclass) : '';


        $parameter = array(
            'posts_per_page'      => $num,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
        );
        $found     = 0;
        if ((isset($posttag)) && ($posttag >= 0)) {
            $parameter['tag_id'] = $posttag;
            $found               = 1;
        }
        if ((isset($postcat)) && ($postcat >= 0)) {
            $parameter['cat'] = $postcat;
            $found            = 2;
        }
        if ($found == 0) {
            return;
        }
        $blogroll_query = new WP_Query($parameter);
        $out            = '<section class="blogroll ' . $divclass . '">';
        if ($blogroll_query->have_posts()) :
            while ($blogroll_query->have_posts()) :
                $blogroll_query->the_post();
                $id  = get_the_ID();
                $out .= fau_display_news_teaser($id, true, $hstart, $hidemeta);
            endwhile;
        endif; // have_posts()

        wp_reset_postdata();
        $out .= '</section>' . "\n";

        return $out;
    }
endif;
/*-----------------------------------------------------------------------------------*/
/* Display blog entries as list
/*-----------------------------------------------------------------------------------*/
if (!function_exists('fau_articlelist')) :
    function fau_articlelist($posttag = '', $postcat = '', $num = 5, $divclass = '', $title = '')  {
        $posttag = $posttag ? esc_attr($posttag) : '';

        if ((!isset($posttag)) && (!isset($postcat))) {
            // kein wert gesetzt, also nehm ich die letzten Artikel
            $postcat = 0;
        } else {
            if (is_string($posttag)) {
                $posttag = fau_get_tag_ID($posttag);
            }
            $postcat = fau_get_cat_ID($postcat);
        }

        if (!is_int($num)) {
            $num = 5;
        }
        $divclass = $divclass ? esc_attr($divclass) : '';


        $parameter = array(
            'posts_per_page'      => $num,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
        );
        $found     = 0;
        if ((isset($posttag)) && ($posttag > 0)) {
            $parameter['tag_id'] = $posttag;
            $found               = 1;
        }
        if ((isset($postcat)) && ($postcat > 0)) {
            $parameter['cat'] = $postcat;
            $found            = 2;
        }
        if ($found == 0) {
            return;
        }
        $blogroll_query = new WP_Query($parameter);


        $divclass = $divclass ? esc_attr($divclass) : '';
        $title    = esc_attr($title);


        $out = '';
        if (!empty($title)) {
            $out .= '<section class="section_articlelist"><h2>' . $title . '</h2>';
        }
        $out .= '<ul class="articlelist ' . $divclass . '">';
        if ($blogroll_query->have_posts()) :
            while ($blogroll_query->have_posts()) :
                $blogroll_query->the_post();

                $out .= '<li>';
                $out .= '<a href="' . esc_url(get_permalink()) . '">';
                $out .= get_the_title();
                $out .= '</a>';
                $out .= '</li>';
            endwhile;
        endif; // have_posts()

        wp_reset_postdata();
        $out .= '</ul>' . "\n";
        if (!empty($title)) {
            $out .= '</section>';
        }

        return $out;
    }
endif;


/*-----------------------------------------------------------------------------------*/
/* Add another esc_url, but also makes URL relative
/*-----------------------------------------------------------------------------------*/
function fau_esc_url($url) {
    if (!isset($url)) {
        $url = home_url("/");
    }

    return fau_make_link_relative(esc_url($url));
}

function get_fau_template_uri() {
    return get_template_directory_uri();
}

/*-----------------------------------------------------------------------------------*/
/* Makes absolute URL from relative URL
/*-----------------------------------------------------------------------------------*/
function fau_make_absolute_url($url) {
    if (!isset($url)) {
        $url = home_url("/");
    } else {
        if (substr($url, 0, 1) == '/') {
            $base = home_url();
            return $base . $url;
        } else {
            return $url;
        }
    }
}

/*-----------------------------------------------------------------------------------*/
/* make urls relative to base url
/*-----------------------------------------------------------------------------------*/
function fau_make_link_relative($url) {
    $orig             = $url;
    $current_site_url = get_site_url();
    if (!empty($GLOBALS['_wp_switched_stack'])) {
        $switched_stack = $GLOBALS['_wp_switched_stack'];
        $blog_id        = end($switched_stack);
        if ($GLOBALS['blog_id'] != $blog_id) {
            $current_site_url = get_site_url($blog_id);
        }
    }
    $current_host = parse_url($current_site_url, PHP_URL_HOST);
    $host         = parse_url($url, PHP_URL_HOST);
    if ($current_host == $host) {
        $url = wp_make_link_relative($url);
   //     return apply_filters('fau_relative_link', $url, $orig);
    }
    return $url;
    
}
/*-----------------------------------------------------------------------------------*/
/*is url external
/*-----------------------------------------------------------------------------------*/
function fau_is_url_external($url) {
    $rellink = fau_make_link_relative($url);
    if (substr($rellink, 0, 4) == 'http') {
        return true;
    }
    return false;
}

/*-----------------------------------------------------------------------------------*/
/* Custom template tags: Functions for templates and output
/*-----------------------------------------------------------------------------------*/
function fau_load_template_part($template_name, $part_name = null) {
    ob_start();
    get_template_part($template_name, $part_name);
    $var = ob_get_contents();
    ob_end_clean();

    return $var;
}

/*-----------------------------------------------------------------------------------*/
/* Check for langcode and return it
/*-----------------------------------------------------------------------------------*/
function fau_get_page_langcode($id = 0) {
    if ($id == 0) {
        return;
    }

    $langcode = get_post_meta($id, 'fauval_langcode', true);
    $setlang  = "";
    if (!fau_empty($langcode)) {
        $sitelang = fau_get_language_main();
        if ($langcode != $sitelang) {
            $setlang = ' lang="' . $langcode . '"';
        }
    }

    return $setlang;
}

/*-----------------------------------------------------------------------------------*/
/* Add langcode to title if need
/*-----------------------------------------------------------------------------------*/
function fau_get_the_title($id = 0) {
    global $post;
    if ($id == 0) {
        $id = $post->ID;
    }

    if (is_page($id)) {
        $titlelangcode = get_post_meta($id, 'fauval_pagetitle_langcode', true);
        if (!fau_empty($titlelangcode)) {
            $sitelang = fau_get_language_main();
            if ($titlelangcode != $sitelang) {
                $res = '<span lang="' . $titlelangcode . '">';
                $res .= get_the_title($id);
                $res .= '</span>';

                return $res;
            }
        } else {
            return get_the_title($id);
        }
    } else {
        return get_the_title($id);
    }
}

/*-----------------------------------------------------------------------------------*/
/* create HTML for image figcaption
/*-----------------------------------------------------------------------------------*/
function fau_get_image_figcaption($atts = array(), $type = 'post-image', $class = 'post-image-caption') {
    // $type:
    //    'post-image': Standard Post Image

    $out = '';
    if (!empty($atts)) {
        $caption_content = '';

        // Fill with default behaviour; Will be overwritten by cases
        if ($atts['excerpt'] && !fau_empty($atts['excerpt'])) {
            $caption_content = trim(strip_tags($atts['excerpt']));
        } elseif (isset($atts['description'])) {
            // Fallback to description, if avaible
            $caption_content = trim(strip_tags($atts['description']));
        } elseif (isset($atts['title'])) {
            // Fallback to title, if avaible
            $caption_content = trim(strip_tags($atts['title']));
        }

        if ($type == 'post-image') {
            // Default Post Image in Post Display
            if (isset($atts['fauval_overwrite_thumbdesc'])) {
                $caption_content = $atts['fauval_overwrite_thumbdesc'];
            } else {

                $displaycredits = get_theme_mod("advanced_display_postthumb_credits");
                if (($displaycredits == true) && isset($atts['copyright']) && (!fau_empty($atts['copyright'])) && ($caption_content !== trim(strip_tags($atts['copyright'])))) {
                    $caption_content .= '<span class="copyright">' . trim(strip_tags($atts['copyright'])) . '</span>';
                }
            }
        }

        if (!fau_empty($caption_content)) {
            $out = '<figcaption class="' . $class . '">';
            $out .= $caption_content;
            $out .= '</figcaption>';
        }
    }

    return $out;
}

/*-----------------------------------------------------------------------------------*/
/* create HTML for image with srcset-codes
/*-----------------------------------------------------------------------------------*/
function fau_get_image_htmlcode($id = 0, $size = 'rwd-480-3-2', $alttext = '', $classes = '', $atts = array()) {
    if ($id == 0) {
        return;
    }

    $img = wp_get_attachment_image_src($id, $size);

    if ($img) {

        $attributes = '';
        if (!empty($atts)) {
            foreach ($atts as $attr => $value) {
                if (!empty($value)) {
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }
        }

        $widthstr = '';
        if ((isset($img[1])) && ($img[1] > 1)) {
            $widthstr = ' width="' . $img[1] . '"';
        }
        $heightstr = '';
        if ((isset($img[2])) && ($img[2] > 1)) {
            $heightstr = ' height="' . $img[2] . '"';
        }
        // In case of svg images, width and height are either empty or set to 1.
        // therefor we set the attributes only in case they are above 1.



        $imgsrcset   = wp_get_attachment_image_srcset($id, $size);
        $imgsrcsizes = wp_get_attachment_image_sizes($id, $size);
        $alttext     = esc_html($alttext);


        if (!isset($alttext)) {
            $imgmeta = fau_get_image_attributs($id);
            $alttext = $imgmeta['alt'];
        }
        $item_output = '';
        $item_output .= '<img src="' . fau_esc_url($img[0]) . '"' . $widthstr . $heightstr . ' alt="' . $alttext . '"';
        if ($imgsrcset) {
            $item_output .= ' srcset="' . $imgsrcset . '"';
            if ($imgsrcsizes) {
                $item_output .= ' sizes="' . $imgsrcsizes . '"';
            }
        }
        if ($classes) {
            $item_output .= ' class="' . $classes . '"';
        }
        if ($attributes) {
            $item_output .= $attributes;
        }
        $item_output .= ' loading="lazy">';

        return $item_output;
    }

    return;
}
/*-----------------------------------------------------------------------------------*/
/* get info of defined sizes
/*-----------------------------------------------------------------------------------*/
function fau_get_image_sizes($size = 'rwd-480-3-2') {
    global $defaultoptions;

    // Find (old) aliases and map them
    switch ($size) {
        case 'topevent_thumb':
        case 'post-thumb':
            $realsize = 'rwd-480-3-2';
            break;
        case 'fallback_submenu_image':
        case 'page-thumb':
            $realsize = 'rwd-480-2-1';
            break;
        default:
            $realsize = $size;
            break;
    }
    if (isset($defaultoptions['default_image_sizes'][$realsize])) {
        return $defaultoptions['default_image_sizes'][$realsize];
    }

    return $defaultoptions['default_image_sizes']['rwd-480-3-2'];
}
/*-----------------------------------------------------------------------------------*/
/* get fallback image by size
/*-----------------------------------------------------------------------------------*/
function fau_get_image_fallback_htmlcode($size = 'rwd-480-3-2', $alttext = '', $classes = '', $atts = array()) {
    $imgsrc  = $imgsrcset = $imgsrcsizes = '';
    $alttext = esc_html($alttext);

    $sizedata = fau_get_image_sizes($size);
    $width = $sizedata['width'];
    $height = $sizedata['height'];

    $fallback_svgfaulogo = get_theme_mod('fallback_svgfaulogo');
    
    switch ($size) {
        case 'topevent_thumb':
            $fallback = get_theme_mod('fallback_topevent_image');
            if ($fallback) {
                $thisimage   = wp_get_attachment_image_src($fallback, 'rwd-480-3-2');
                $imgsrc      = $thisimage[0];
                $width       = $thisimage[1];
                $height      = $thisimage[2];
                $imgsrcset   = wp_get_attachment_image_srcset($fallback, 'rwd-480-3-2');
                $imgsrcsizes = wp_get_attachment_image_sizes($fallback, 'rwd-480-3-2');
            }

            break;

        case 'post-thumb':
            $fallback = get_theme_mod('default_postthumb_image');
            if ($fallback) {
                $thisimage   = wp_get_attachment_image_src($fallback, 'rwd-480-3-2');
                $imgsrc      = $thisimage[0];
                $width       = $thisimage[1];
                $height      = $thisimage[2];
                $imgsrcset   = wp_get_attachment_image_srcset($fallback, 'rwd-480-3-2');
                $imgsrcsizes = wp_get_attachment_image_sizes($fallback, 'rwd-480-3-2');
            }

            break;

        case 'fallback_submenu_image':
            $fallback = get_theme_mod('fallback_submenu_image');
            if ($fallback) {
                $thisimage   = wp_get_attachment_image_src($fallback, 'rwd-480-2-1');
                $imgsrc      = $thisimage[0];
                $width       = $thisimage[1];
                $height      = $thisimage[2];
                $imgsrcset   = wp_get_attachment_image_srcset($fallback, 'rwd-480-2-1');
                $imgsrcsizes = wp_get_attachment_image_sizes($fallback, 'rwd-480-2-1');
            }

            break;
    }

    if ($imgsrc) {
        $attributes = '';
        if (!empty($atts)) {
            foreach ($atts as $attr => $value) {
                if (!empty($value)) {
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }
        }
        $item_output = '';
        $item_output .= '<img src="' . fau_esc_url($imgsrc) . '" width="' . $width . '" height="' . $height . '" alt="' . $alttext . '"';
        if ($imgsrcset) {
            $item_output .= ' srcset="' . $imgsrcset . '"';
            if ($imgsrcsizes) {
                $item_output .= ' sizes="' . $imgsrcsizes . '"';
            }
        }
        if ($classes) {
            $item_output .= ' class="' . $classes . '"';
        }
        if ($attributes) {
            $item_output .= $attributes;
        }
        $item_output .= ' loading="lazy">';

        return $item_output;
    } elseif ($fallback_svgfaulogo) {
        $item_output = fau_use_svg("fau-logo-2021", $width, $height, $classes, false);

        return $item_output;
    } else {
        global $defaultoptions;
        $item_output = '';
        $item_output .= '<img src="' . fau_esc_url($defaultoptions['src-fallback-post-image']) . '" width="' . $width . '" height="' . $height . '" alt="' . $alttext . '"';
      
        $classes .= ' fallback';
        $item_output .= ' class="' . $classes . '"';
        $attributes = '';
        if (!empty($atts)) {
            foreach ($atts as $attr => $value) {
                if (!empty($value)) {
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }
        }
        if ($attributes) {
            $item_output .= $attributes;
        }
        $item_output .= ' loading="lazy">';
        
        return $item_output;
    }
    

    return;
}

/*-----------------------------------------------------------------------------------*/
/* Tag List with title-Attribute fot better A11y, see 
 * https://github.com/RRZE-Webteam/FAU-Einrichtungen/issues/949
 */
/*-----------------------------------------------------------------------------------*/
function fau_get_the_taglist($before = '', $sep = '', $after = '') {
    $terms = get_the_tags();
    $res = '';

    if (!is_wp_error($terms) && !empty($terms)) { // Check if $terms is OK.

        if (!empty($before)) {
            $res .= $before;
        }

        if (empty($sep)) {
            // use list
            $res .= '<ul>';
        }


        foreach ($terms as $term) {
            $link = get_term_link($term);
            if (is_wp_error($link)) {
                continue;
            }
            if (empty($sep)) {
                // use list
                $res .= '<li>';
            }
            $res .= '<a href="' . esc_url($link) . '" rel="tag" aria-label="' . __('Beiträge mit dem Schlagwort', 'fau') . ' ' . $term->name . ' ' . __('aufrufen', 'fau') . '">' . $term->name . '</a>';
            if (empty($sep)) {
                // use list
                $res .= '<li>';
            } else {
                $res .= $sep;
            }
        }
        if (empty($sep)) {
            // use list
            $res .= '</ul>';
        } else {
            $res = trim($res, $sep);
        }

        if (!empty($after)) {
            $res .= $after;
        }
    }
    return $res;
}
/*-----------------------------------------------------------------------------------*/
/*  Add tabs / intendens to default output functions
 *   Source/credits to: https://wordpress.stackexchange.com/questions/70901/indenting-tabbing-wp-head
 */
/*-----------------------------------------------------------------------------------*/
if (!function_exists("print_indented")) {
    function print_indented($fn, $num_tabs = 1, $params = null)  {
        ob_start();
        call_user_func($fn, $params);
        $html = ob_get_contents();
        ob_end_clean();
        $tabs = "";
        for ($i = 0; $i < $num_tabs; $i++) $tabs .= "\t";
        echo preg_replace("/\n/", "\n" . $tabs, substr($html, 0, -1));
        echo "\n";
    }
}
/*-----------------------------------------------------------------------------------*/
/*  Returns language code, without subcode
/*-----------------------------------------------------------------------------------*/
function fau_get_language_main() {
    $charset = explode('-', get_bloginfo('language'))[0];
    return $charset;
}


/*-----------------------------------------------------------------------------------*/
/* Change WordPress default language attributes function to
 * strip of region code parts. Not used yet /anymore
/*-----------------------------------------------------------------------------------*/
function fau_get_language_attributes($doctype = 'html') {
    $attributes = array();

    if (function_exists('is_rtl') && is_rtl())
        $attributes[] = 'dir="rtl"';

    if ($langcode = fau_get_language_main()) {
        if (get_option('html_type') == 'text/html' || $doctype == 'html')
            $attributes[] = "lang=\"$langcode\"";

        if (get_option('html_type') != 'text/html' || $doctype == 'xhtml')
            $attributes[] = "xml:lang=\"$langcode\"";
    }
    $output = implode(' ', $attributes);
    return $output;
}


/*-----------------------------------------------------------------------------------*/
/* This is the end :)
/*-----------------------------------------------------------------------------------*/