<?php

/* 
 * Default Constants and values 
 */

global $OPTIONS_NAME, $defaultoptions, $setoptions;

$OPTIONS_NAME = 'fau_theme_options';
    // Name des Options-Array

$defaultoptions = [
    'optiontable-version'	    => 84,
		// zaehlt jedesmal hoch, wenn neue Optionen eingefuegt werden 
		// oder Default Optionen geaendert werden. Vorhandene Defaultoptions 
		// in der Options-Table werden nur dann geändert, wenn der Wert erhöht 
		// wurde oder die Theme Options von Hand aufgerufen und gespeichert wurden.
    'debugmode'			    => false,
		// Der Debugmode erlaubt die Einschaltung von Debug- und Demowerten über den Customizer.
		// Bei der Compilierung des Themes mit Gulp wird der Debugmode bei
		//  'gulp build'   auf false gesetzt
		// Ansonsten kann er manuell über 'gulp nodebug' auf false und
		//  mit 'gulp debugmode' auf true gesetzt werden
		// Oder hier von Hand :)
    'js-version'		    => '2.10',
		// Theme-Versionslinie, wird überschrieben durch Style.css Version
    
    'website_type'		    => 1,
		// website_type: 
		//  0 = Fakultaetsportal; 
		//  1 = Lehrstuehle, Departments 
		//  2 = Zentrale Einrichtungen, 
		//  3 = Kooperationen 
		// -1 = fau.de Portal (4 Spalter in Bühne, kein Link zur FAU. 

    'website_usefaculty'		=> '',
		// phil, med, nat, rw, tf
		// Setzt fest die Fakultät bei Wahl des Website-Types    

    'default_home_orga'			=> 'fau',
		// Muss in $default_fau_orga_data auf erster Ebene vorhanden sein.	
    'default-social-media-color'	=> '#04316a',
    'default-sourcecode-notice'		=> true,
    'default-sourcecode-notice-text'	=> __("Wenn Sie dies lesen, sind wir vielleicht was für Sie! \n	    Die FAU sucht immer vielversprechende Talente in allen universitären Bereichen, die bereit sind, sich mit Leidenschaft, Kreativität und Engagement für die FAU einzusetzen: \n	    https://jobs.fau.de",'fau'),
    'optionpage-tab-default'		=> 'website',
    'content-width'			=> 620,
    'content-width-fullpage'		=> 940,
    'src-fallback-slider-image'		=> get_fau_template_uri().'/img/slider-fallback.jpg',
    'slider-catid'			=> 0,    
    'src-scriptjs'			=> get_fau_template_uri() . '/js/fau-theme.min.js',
    'src-sliderjs'			=> get_fau_template_uri() . '/js/fau-theme-slick.min.js',
    'src-printlinks'			=> get_fau_template_uri() . '/js/printlinks.min.js',
    'src-adminjs'			=> get_fau_template_uri() . '/js/fau-theme-admin.min.js',
    'src-admin-customizer-js'		=> get_fau_template_uri() . '/js/fau-theme-customizer-range-value-control.min.js',
    'src-admin-wplinkjs'		=> get_fau_template_uri() . '/js/fau-theme-wplink.min.js',

    'src-svglib_dir'			=> get_template_directory().  '/src/svglib/',
    
    'default_slider_excerpt_length'		=> 240,
    'slider-autoplay'			=> true,
    'slider-animation'			=> 'fade',
    'slider-stoptext'			=> __('Animation stoppen','fau'),
    'slider-starttext'			=> __('Animation starten','fau'),
   
    
    
    'start_header_count'			=> 5,
    'start_max_newscontent'			=> 5,
    'start_max_newspertag'			=> 1,    
    'start_prefix_tag_newscontent'		=> 'startseite',
    'start_link_news_cat'			=> 0,    
    'start_link_news_show'			=> 1,
    'start_link_news_linktitle'			=> __('Mehr Meldungen','fau'),


    'default_postthumb_always'			=> 1,
    'default_postthumb_image'			=> 0,
    'default_submenu_spalten'			=> 4,
    'default_submenu_entries'			=> 5,
    'menu_fallbackquote_show_excerpt'		=> 1,
    'menu_fallbackquote_excerpt_length'		=> 240,  
    'start_topevents_max'			=> 1,
    'start_topevents_active'			=> true,
    'topevent_hideimage'			=> false,
    'topevents_templates'			=> array(1), 
    'fallback_topevent_image'			=> 0,
    'fallback_submenu_image'			=> 0,
    'default_topevent_excerpt_length'		=> 100,

    'default_startseite-bannerbild-image_src'	=> get_fau_template_uri().'/img/bannerbild-tafel-1260x182.jpg',
    'startseite_banner_usedefault'		=> false,
    

    /* Image Sizes */
    'default_image_sizes' => [
	'hero'	=> [
	    'width'	=> 1260,
	    'height'	=> 350,
	    'crop'	=> true,
	    'imagelink'	=> false,
	    'desc'	=> __('Sliderbild auf Startseite','fau')
	],
	'herobanner'	=> [
	    'width'	=> 1260,
	    'height'	=> 182,
	    'crop'	=> true,
	    'imagelink'	=> false,
	    'desc'	=> __('Bannerbild Startseite','fau')
	],
	'logo-thumb'	=> [
	    'width'	=> 140,
	    'height'	=> 110,
	    'crop'	=> false,
	    'imagelink'	=> true,
	    'desc'	=> __('Kleines rechteckiges Logo','fau')
	],
	'rwd-480-2-1'	=> [
	    'width'	=> 480,
	    'height'	=> 240,
	    'crop'	=> false,
	    'imagelink'	=> true,
	    'desc'	=> __('Bild in 2:1 Format','fau')
	],
	'rwd-480-3-2'	=> [
	    'width'	=> 480,
	    'height'	=> 320,
	    'crop'	=> false,
	    'imagelink'	=> true,
	    'desc'	=> __('Bild in 3:2 Format','fau')
	],
	'gallery-full'	=> [
	    'width'	=> 940,
	    'height'	=> 470,
	    'crop'	=> false,
	    'imagelink'	=> false,
	    'desc'	=> __('Hochkantbild für Galerien','fau')
	],
	'_post_thumbnail'   => [
	    'width'	=> 480,
	    'height'	=> 240,
	    'crop'	=> false,
	    'imagelink'	=> true,
	    'desc'	=> __('Artikelbild','fau')
	],
	'_thumbnail'	=> [
	    'width'	=> 150,
	    'height'	=> 150,
	    'crop'	=> true,
	    'imagelink'	=> true,
	    'desc'	=> __('Thumbnail','fau')
	],
	'_thumb'	=> [
	    'width'	=> 150,
	    'height'	=> 150,
	    'crop'	=> true,
	    'imagelink'	=> true,
	    'desc'	=> __('Thumb','fau')
	]
	
    ],

    
    'default_imagelink_sizes' => [
            'logo-thumb' => '140×110 Pixel',
            'page-thumb' => '2:1 (220×110 Pixel)',
            'post-thumbnails' => '2:1 (300×150 Pixel)',
            'thumbnail' => '1:1 (150×150 Pixel)',
        ],

    'breadcrumb_root'				=> __('Startseite', 'fau'),
    'breadcrumb_withtitle'			=> false,
    'breadcrumb_showcurrent'			=> true,
    'default_logo_height'			=> 152,
    'default_logo_width'			=> 400,
    
    'socialmedia'				=> 0,
    'active_socialmedia_footer'			=> array(0),  
    'socialmedia_buttons_title'			=> __('Social Media','fau'),
    
    'socialmedia_menu_name'			=> __( 'Social Media Menu', 'fau' ),
    'socialmedia_menu_position'			=> 'FAU_SocialMedia_Menu_Footer',
    'socialmedia_menu_position_title'		=> __( 'Social Media Bereich im Footer', 'fau' ),
    
    'menu_pretitle_portal'			=> __('Portal', 'fau'),
    'menu_aftertitle_portal'			=> '',
    
   'contact_address_name'			=> __('Friedrich-Alexander-Universität', 'fau'),
   'contact_address_name2'			=> __('Erlangen-Nürnberg', 'fau'),
   'contact_address_street'			=> __('Schlossplatz 4', 'fau'),
   'contact_address_plz'			=> __('91054', 'fau'),
   'contact_address_ort'			=> __('Erlangen', 'fau'),
   
    'contact_address_country'			=> '',
    'google-site-verification'			=> '',
    'default_mainmenu_number'			=> 4,
   

    'default_excerpt_morestring'		=> '...',
    'default_excerpt_length'			=> 50,
    'default_anleser_excerpt_length'		=> 300,
    'default_search_excerpt_length'		=> 300,
    'default_display_continue_link'		=> true,
    
    'custom_excerpt_allowtags'			=> 'br',
    'title_hero_search'				=> __( 'Webauftritt durchsuchen', 'fau' ),
    
    'advanced_footer_display_address'		=> true,
    'advanced_footer_display_socialmedia'	=> false,
    
    
    'advanced_beitragsoptionen'			=> true,
    'advanced_topevent'				=> true,
    'default_display_thumbnail_3_2'		=> true,
    'galery_link_original'			=> true,
    'galery_force_caption_onslick'		=> true,

    'advanced_post_active_subtitle'		=> true,

    'advanced_page_sidebar_titleabove'		=> true,
    'advanced_page_sidebar_titlebelow'		=> true,    
    'advanced_page_sidebar_useeditor_textabove'	=> false,
    'advanced_page_sidebar_useeditor_textbelow'	=> false,

	
	'advanced_header_banner_display_title'	=> true,
		// Anzeige des Website-Titels bei der Startseite (mit Banner)
	'advanced_header_banner_display_slogan'	=> true,
		// Anzeige des Slogans bei der Startseite (mit Banner)
	
    'advanced_page_sidebar_wpsidebar'		=> false,
		// wenn true, wird die Siderbar fuer Pages aktiviert
    'advanced_page_sidebar_wpsidebar_id'	=> 'page-sidebar',
		// Sidebar Id
    
    
    'advanced_page_sidebar_personen_title'	=> __('Kontakt','fau'), 
    'advanced_page_sidebar_linkblock1_number'	=> 3, 
    'advanced_page_sidebar_linkblock2_number'	=> 3,
    'advanced_page_sidebar_linkblock1_title'	=> __('Weitere Informationen','fau'), 
    'advanced_page_sidebar_linkblock2_title'	=> __('Sonstiges','fau'), 
    'advanced_page_sidebar_order_personlinks'	=> 0,
		// 0 = Kontakte, Links
		// 1 = Links, Kontakte
    'advanced_activate_post_comments'		=> false,
    'advanced_comments_notes_before'		=> __('Ihre E-Mail-Adresse wird nicht angezeigt. Verpflichtende Felder werden mit dem folgenden Zeichen markiert: <span class="required">*</span>', 'fau' ),
    'advanced_comments_disclaimer'			=> __('Hinweis: Die Kommentare wurden von Lesern geschrieben und spiegeln deren persönliche Meinung wieder. Sie müssen nicht die Meinung der Universität oder der Fakultät repräsentieren.', 'fau' ),
    'advanced_comments_avatar'			=> false,
    
    
    'post_display_category_below'			=> true,
    'post_display_tags_below'			    => true,
    
    'search_display_post_thumbnails'		    => true,
    'search_display_post_cats'			    => true,
    'search_display_continue_arrow'			=> true,
    'search_display_excerpt_morestring'		    => '...',
    'search_display_typenote'			    => true,
    'advanced_display_postthumb_alt-from-desc'	    => false,
    'search_post_types'				    => array("page", "post", "attachment"),
    'search_post_types_checked'			    => array("page", "post"),
    'search_allowfilter'				=> true,
    'search_notice_searchregion'			=> __('Es wird nur in diesem Webauftritt gesucht. Um Dokumente und Seiten aus anderen Webauftritten zu finden, nutzen Sie bitte die jeweils dort zu findende Suchmaschine oder verwenden eine Internet-Suchmaschine.','fau'),

        
    'advanced_reveal_pages_id'			    => false,
		// Zeigt Page-ID im Backend der Seitebearbeitung
    'advanced_images_info_credits'			=> 0,
    'advanced_display_hero_credits'			=> true,   
    'advanced_display_postthumb_credits'		=> true,
		// Zeigt das Copyright-Feld as der EXIF-Meta von Bildern an
    'advanced_activate_page_langcode'		=> false,
		// Option zur Deklarierung einer anderen Sprache für eine Seite
    'advanced_blogroll_thumblink_alt_pretitle'	=> __('Zum Artikel "','fau'), 
		// Alternativer Tag wird mit dem Tiotel des verlinkten Beitrags gefüllt. 
		// Hier kann davor noch ein Vortitel stehen.
    'advanced_blogroll_thumblink_alt_posttitle'	=> '"', 
		// Alternativer Tag wird mit dem Tiotel des verlinkten Beitrags gefüllt. 
		// Hier kann davor noch ein teil dahinter stehen. 

    'advanced_topevent_thumblink_alt_pretitle'	=> __('Zum Artikel "','fau'), 
		// Alternativer Tag wird mit dem Tiotel des verlinkten Beitrags gefüllt. 
		// Hier kann davor noch ein Vortitel stehen.
    'advanced_topevent_thumblink_alt_posttitle'	=> '"', 
		// Alternativer Tag wird mit dem Tiotel des verlinkten Beitrags gefüllt. 
		// Hier kann davor noch ein teil dahinter stehen.     

    'advanced_display_portalmenu_forceclick'		=> false,
		// Hauptmenü öffnet sich nur bei einem Klick
		//  'advanced_display_header_md-showsitelogo'	=> false,
		// Zeigt bei der mobilen Ansicht statt dem Logo der Website das 
		// CI Logo der FAU anstelle des Logos der Website
    'advanced_display_portalmenu_button'    => true,
		// Per Default wird ein Button zur Verlinkung der jeweiligen 
		// Menüoberseite im Flyout angezeigt.
		// Wenn das Flyout jedoch auf Hover/Focus öffnet, kann der Button 
		// weggelassen werden, da dann auch der Klick auf den Hauptmenüpunkt 
		// wirksam ist.
		// Diese Option wirkt nicht, wenn das Menü einen Klick 
		// erfordert um zu öffnen.
		
    'advanced_header_template'	=> '',
		// Anzeigeform des Heros bei Index- und Kategorieseiten
    'advanced_template_page_start_display_content'  => false,
		// Auf dem Template "Page Start" (Startseite) wird der Content
		//  per Default nicht gezeigt (false). Über die Customizer-Option
		// kann dieser Eintrag geändert werden.
	]; 

 $content_width =$defaultoptions['content-width'];

/*--------------------------------------------------------------------*/
/* Initialisiere Options und Theme Mods 
/*  (unter besonderer Berücksichtung der Abwärtskompatibilität alter Options)
/*--------------------------------------------------------------------*/
function fau_initoptions() {
    global $defaultoptions;
    global $setoptions;
    global $OPTIONS_NAME;
    
    
    $themeopt = get_theme_mods();
    $theme = get_option( 'stylesheet' );
    $newoptions = $defaultoptions;
   
        
    $theme_data = wp_get_theme();
    $newoptions['version'] =  $theme_data->Version;
    
   
    $update_thememods = false;
    // Fuer Abwaertscompatibilitaet zu alten Images aus dem Option Settings:
    foreach($setoptions[$OPTIONS_NAME] as $tab => $f) {       
		foreach($setoptions[$OPTIONS_NAME][$tab]['fields'] as $i => $value) {  
		    if ($value['type'] == "image") {
			if (isset($newoptions[$i."_id"])) {			    
			    if (!isset($themeopt[$i])) {
				$themeopt[$i] = $newoptions[$i."_id"];
				$update_thememods = true;
			    }  
			} 
		    } elseif ($value['type'] == 'section') {
			// this not
		    } elseif ((!isset($themeopt[$i])) && (isset($newoptions[$i]))) {
			$themeopt[$i] = $newoptions[$i];
			$update_thememods = true;
		    }
		}		
    }
    if ($update_thememods==true) {
	
        update_option( "theme_mods_$theme", $themeopt );
    }
    
    $theme_opt_version = get_theme_mod('optiontable-version');
    
    if ((!isset($theme_opt_version)) || ($theme_opt_version < $defaultoptions['optiontable-version'])) {
	// Optiontable ist neuer. Prüefe noch die Optionen, die nicht
	// in der Settable stehen und fülle diese ebenfalls in theme_mods:
	
	    $ignoreoptions = array();
	    $update_thememods = false;
	    foreach($setoptions[$OPTIONS_NAME] as $tab => $f) {       
			foreach($setoptions[$OPTIONS_NAME][$tab]['fields'] as $i => $value) {  
				$ignoreoptions[$i] = $value;
			}
	    }
	    $defaultlist = '';
	    foreach($defaultoptions as $i => $value) {       
			if (!isset($ignoreoptions[$i])) {
				if (isset($themeopt[$i]) && ($themeopt[$i] !=  $defaultoptions[$i])) {
					$themeopt[$i] = $defaultoptions[$i];	
					$update_thememods = true;
				} elseif (!isset($themeopt[$i])) {
					$themeopt[$i] = $defaultoptions[$i];	
					$update_thememods = true;
				}
			}
	    }
	    if ($update_thememods==true) {
			update_option( "theme_mods_$theme", $themeopt );
	    } else {
			// only version number
			set_theme_mod( 'optiontable-version', $defaultoptions['optiontable-version'] ); 
	    }
	    
	    
	    fau_compatible_header_logo();
	     // Prüfe: Header-Image zu Custom Logo
    }

    
    return $newoptions;
}

/*--------------------------------------------------------------------*/
/* Ab Version 2.0 wurde die Verwaltung des Logos von Header-Image auf Custom Logo umgestellt.
/* Für Installationen, bei der das Logo jedoch mit Header Ikmage hochgeladen wurden,
/* soll das Logo dennoch weiterhin angezeigt werden. 
/*--------------------------------------------------------------------*/
function fau_compatible_header_logo() {
    $data = get_theme_mod( 'header_image_data' );
    if ( $data) {
	// Es existiert ein Custom Header 
	
	if (is_object($data) && ($data->attachment_id)) {
	    
	    // ok, there is an id
	    // now check if we already have an id of a custom logo
	    $custom_logo_id = get_theme_mod( 'custom_logo' );
	    
	    if ($custom_logo_id) {
		// We already have an id
	    } else {
		// Set the image as custom logo and remove it from header mods
		set_theme_mod( 'custom_logo', $data->attachment_id ); 
		remove_theme_mod('header_image_data');
		remove_theme_mod('header_image');
		// now remove the 'header_image_data' mod.
	    }
	    
	    
	}
	
    }
    
}
/*--------------------------------------------------------------------*/
/* Suchfelder
/*--------------------------------------------------------------------*/
function fau_get_searchable_fields($format = 'array') {
  //  $search_post_types = array("page", "post", "attachment");
    $search_post_typelist  = array(
	"page"		=> __('Seiten','fau'),
	"post"		=> __('Artikel','fau'),
	"attachment"	=> __('Dokumente und Bilder','fau'),
    );
    
    if (class_exists('FAU_Studienangebot')) {
//	$search_post_types[] ='studienangebot';
	$addsearch=  array('studienangebot' => __('Studienangebot','fau'));
	$search_post_typelist = array_merge($search_post_typelist, $addsearch);
    }
    if (class_exists('FAU_Person')) {
//	$search_post_types[] ='person';
	$addsearch = array('person' => __('Kontakte','fau'));
	$search_post_typelist = array_merge($search_post_typelist, $addsearch);
    }
     if (class_exists('RRZE\FAQ\CPT')) {
  //      $search_post_types[] = 'faq';
	$addsearch= array('faq' => __('Fragen und Antworten','fau'));
	$search_post_typelist = array_merge($search_post_typelist, $addsearch);
    }
    
    if ($format === 'list') {
	return $search_post_typelist;
    } else {
	return array_keys($search_post_typelist);
    }
    
    return $search_post_types;
    
    

    
}
/*--------------------------------------------------------------------*/
/* Erstelle globale Kategorieliste 
 * (für Version unter 1.9.4 benötigt
 */
/*--------------------------------------------------------------------*/
 $categories=get_categories(array('orderby' => 'name','order' => 'ASC'));
 $currentcatliste = array();
 foreach($categories as $category) {
     if (!is_wp_error( $category )) {
	$currentcatliste[$category->cat_ID] = $category->name.' ('.$category->count.' '.__('Einträge','fau').')';
     }
 }        
/*--------------------------------------------------------------------*/
/* Durch User änderbare Konfigurationen
 *   Ab 1.9.5 über Customizer, davor über Theme Options
/*--------------------------------------------------------------------*/
$setoptions = array(
    'fau_theme_options'   => array(
	
	'title_tagline'       => array(
	    // Default Section
           'fields' => array(
	       
	        'website_shorttitle' => array(
                  'type'    => 'text',
                  'title'   => __( 'Abkürzung', 'fau' ),
                  'label'   => __( 'Abkürzung für den offiziellen Titel der Website', 'fau' ),               
                  'default' => '',
		    'input_attrs' => array(
			'maxlength' => 10,
			'size'	=> 8,
			'style' => 'width: auto;',
		    ),
		  'parent'  => 'title_tagline'
		),
	       'website_logotitle' => array(
                  'type'    => 'text',
                  'title'   => __( 'Titel im Logo', 'fau' ),
                  'label'   => __( 'Bei Einrichtungen der FAU wird der Titel neben dem FAU-Logo gezeigt. Ist der Titel zu lang um ihn optisch korrekt darzustellen, kann hiermmit ein kürzerer Titel angegeben werden. Wird dieser EIntrag leer gelassen, wird der Titel der Website verwendet.', 'fau' ),               
                  'default' => '',
		    'input_attrs' => array(
			'maxlength' => 80,
			'size'	=> 15,
			'style' => 'width: auto;',
		    ),
		  'parent'  => 'title_tagline'
		),
	       
	        'website_type'=> array(
		    'type'    => 'select',
		    'title'   => __( 'Typ', 'fau' ),
		    'label'   => __( 'Bitte wählen Sie hier aus, um welcherart Webauftritt es sich handelt.', 'fau' ),
		    'liste'   => array(
				
				    0 => __('Fakultätsportal','fau'), 
				    1 => __('Department, Lehrstuhl, Einrichtung','fau'),  
				    2 => __('Zentrale Einrichtung','fau') ,
				    3 => __('Website für uniübergreifende Kooperationen mit Externen','fau') ,
				    -1 => __('Zentrales FAU-Portal www.fau.de','fau') 
			),
		    'default' => $defaultoptions['website_type'],
		    'parent'  => 'title_tagline'
		    
		),  
	       

	       
		'startseite_banner_image' => array(
		    'type'    => 'image',
		    'maxwidth'	=> 1260,
		    'maxheight'	=> 182,
		    'title'   => __( 'Banner Startseite', 'fau' ),
		    'label'   => __( 'Festes Banner für die Startseite (Template für Lehrstühle und Einrichtungen)', 'fau' ),               
		    'parent'  => 'title_tagline',
		    'default'	=> '',
		),  
	       
	       
	       
	       
	    )
	),
	       
       
       'allgemeines'   => array(
           'tabtitle'   => __('Anzeigeoptionen', 'fau'),
	   'user_level'	=> 1,
           'fields' => array(
              
	     'postoptions'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Beiträge', 'fau' ),                      
              ),
	       
	       'post_display_category_below' => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Zeige Kategorien', 'fau' ),
                  'label'   => __( 'Liste der Kategorien unter dem Beitrag anzeigen', 'fau' ),                
                  'default' => $defaultoptions['post_display_category_below'],
		  'parent'  => 'postoptions'
              ),  
	       
	       'post_display_tags_below' => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Zeige Schlagworte', 'fau' ),
                  'label'   => __( 'Zeige die Schlagworte eines Beitrags unterhalb des Artikels', 'fau' ),                
                  'default' => $defaultoptions['post_display_tags_below'],
		  'parent'  => 'postoptions'
              ),  
	       'advanced_display_postthumb_credits'	  => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Copyright-Hinweis', 'fau' ),
                  'label'   => __( 'In Beiträgen wird das Artikelbild mit einem Copyright-Hinweis des Bildes versehen, wenn ein solcher Hinweis vorhanden ist.', 'fau' ),                
                  'default' => $defaultoptions['advanced_display_postthumb_credits'],
		  'parent'  => 'postoptions'
              ), 
	       
	       'advanced_activate_post_comments'		  => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Kommentarfunktion', 'fau' ),
                  'label'   => __( 'Schaltet die Kommentarfunktion für Beiträge ein. Die Kommentare erscheinen unterhalb des Artikels. Bitte beachten Sie, daß diese Darstellung von Kommentarfunktionen ebenfalls von den Diskussions-Einstellungen abhängig sind.', 'fau' ),                
                  'default' => $defaultoptions['advanced_activate_post_comments'],
		  'parent'  => 'postoptions'
		), 
	       
	    'advanced_comments_notes_before'	  => array(
                  'type'    => 'text',
                  'title'   => __( 'Hinweistext Eingabeformular', 'fau' ),
                  'label'   => __( 'Informationen über den Eingabefeldern für neue Kommentare.', 'fau' ),                
                  'default' => $defaultoptions['advanced_comments_notes_before'],
		  'parent'  => 'postoptions'
		), 
	        'advanced_comments_disclaimer'	  => array(
                  'type'    => 'text',
                  'title'   => __( 'Kommentar-Disclaimer', 'fau' ),
                  'label'   => __( 'Hinweistext zur Abgrenzung zum Inhalt der Kommentare.', 'fau' ),                
                  'default' => $defaultoptions['advanced_comments_disclaimer'],
		  'parent'  => 'postoptions'
		), 
		'advanced_display_postthumb_alt-from-desc'		  => array(
		    'type'    => 'toggle',
		    'title'   => __( 'Alternativtext Artikelbild', 'fau' ),
		    'label'   => __( 'Bei der Darstellung des Artikelbildes wird das ALT-Attribut vom Inhalt der Bildbeschreibung gefüllt, wenn das ALT-Attribut leer ist.', 'fau' ),                
		    'default' => $defaultoptions['advanced_display_postthumb_alt-from-desc'],
		    'parent'  => 'postoptions'
		), 
	       

	        
	       
	    
	    
	     
	    
	       
	    'slider'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Slider', 'fau' ),    
		  'desc'    => __( 'Einstellungen für die wechselnden Bilder auf Startseiten.', 'fau' ),
             ),
              
	     'start_header_count'=> array(
                  'type'    => 'range-value',
                  'title'   => __( 'Zahl der Slides', 'fau' ),
                  'label'   => __( 'Anzahl der Slides von verlinkten Top-Artikeln', 'fau' ),
		  'min'	    => 2,
		  'max'	    => 10,	
                  'default' => $defaultoptions['start_header_count'],
                   'parent'  => 'slider'
              ), 
      
              'slider-catid' => array(
                  'type'    => 'category',
                  'title'   => __( 'Kategorie', 'fau' ),
                  'label'   => __( 'Bitte wählen Sie die Kategorie der Artikel aus, die im Slider erscheinen sollen.', 'fau' ),
                  'default' => $defaultoptions['slider-catid'],
                   'parent'  => 'slider'
              ), 

	       'fallback-slider-image' => array(
		    'type'    => 'image',
		    'maxwidth'	=> $defaultoptions['default_image_sizes']['hero']['width'], // $defaultoptions['slider-image-width'],
		    'maxheight'	=> $defaultoptions['default_image_sizes']['hero']['height'], // $defaultoptions['slider-image-height'],
		    'title'   => __( 'Slider Ersatzbild', 'fau' ),
		    'label'   => __( 'Ersatzbild für den Slider, für den Fall, daß ein Artikel kein eigenes Artikel- oder Bühnenbild definiert hat.', 'fau' ),               
		    'parent'  => 'slider'
		),  
		'fallback-slider-image-title' => array(
		    'type'    => 'text',
		    'title'   => __( 'Copyright/Titel Ersatzbild', 'fau' ),
		    'label'   => __( 'Optionaler Titel bzw. Copyright-Info für das Ersatzbild für den Fall, daß es sich um ein geschnittenes Originalbild handelt.', 'fau' ),               
		    'default' => '',
		    'parent'  => 'slider'
		),  
	       
	       'default_slider_excerpt_length' => array(
                  'type'    => 'range-value',
                  'title'   => __( 'Textauszug', 'fau' ),
                  'label'   => __( 'Maximale Länge des Teasers des verlinkten Beitrags.', 'fau' ),
		  'min'	    => 0,
		  'max'	    => 350,	
		   'step'   => 10,
                  'default' => $defaultoptions['default_slider_excerpt_length'],
                   'parent'  => 'slider'
              ), 
	      
	       
	       'slider-autoplay'  => array(
		    'type'    => 'toggle',
		    'title'   => __( 'Autoplay', 'fau' ),
		    'label'   => __( 'Slider automatisch starten', 'fau' ),                
		    'default' => $defaultoptions['slider-autoplay'],
		    'parent'  => 'slider'
		),   	  
	       
		'slider-animation' => array(
		    'type'      => 'select',
		    'title'     => esc_html__( 'Slider Animation', 'fau'),
		    'label'     => esc_html__( 'Art der Animation einzelner Slides', 'fau'),
		    'liste'     => array(
			    'slide'	 => esc_html__( 'Verschieben', 'fau'),
			    'fade' 	 => esc_html__( 'Erscheinen', 'fau'),
		    ),
		    'default'   => $defaultoptions['slider-animation'],
		    'parent'    => 'slider'	    
		),
	       
	       
		'galery'  => array(
		    'type'    => 'section',
		    'title'   => __( 'Galerien', 'fau' ),    
		    'desc'    => __( 'Einstellungen für Bildergalerien', 'fau' ),
		),
	        
	         'galery_link_original'	  => array(
		    'type'    => 'toggle',
		    'title'   => __( 'Verlinke Galeriebilder', 'fau' ),
		    'label'   => __( 'Bei der Anzeige einer Defaultgalerie unter der Bildunterschrift eine Verlinkung auf das Originalbild einschalten', 'fau' ),                
		    'default' => $defaultoptions['galery_link_original'],
		    'parent'  => 'galery'
		),   
	         'galery_force_caption_onslick'	  => array(
		    'type'    => 'toggle',
		    'title'   => __( 'Bildbeschriftung bei Galerien erzwingen', 'fau' ),
		    'label'   => __( 'Bei der Darstellung von Bildergalerien mit dem Slider werden die Bildbeschriftungen immer unterhalb des aktiven Bildes angezeigt.', 'fau' ),                
		    'default' => $defaultoptions['galery_force_caption_onslick'],
		    'parent'  => 'galery'
		),   

	  
	       
	       
	    'header'  => array(
		    'type'    => 'section',
		    'title'   => __( 'Header', 'fau' ),    
		    'desc'    => __( 'Einstellungen für den Kopfteil des Webauftritts.', 'fau' ),
		),
	    'advanced_header_template'	  => array(
		    'type'    => 'select',
		    'title'   => __( 'Kopfteil auf Index- und Kategorieseiten', 'fau' ),
		    'label'   => __( 'Auf Index- und Kategorieseite wird üblicherweise ein Kopfteil wie bei normalen Seiten mit einer Breadcrumb gezeigt. Stattdessen kann man jedoch auch die Darstellung des Banners von Startseiten auswählen.', 'fau' ),                
		    'liste'     => array(
			    '' => __('Index in der Standard-Seitenanzeige', 'fau'),
			    'banner'	 => __( 'Banner', 'fau'),
			    'slider' 	 => __( 'Slider', 'fau'),
		    ),
		    'default'   => $defaultoptions['advanced_header_template'],
		    'parent'  => 'header'
		),   
	           
	  	  
		'advanced_header_banner_display_title'	  => array(
		    'type'    => 'toggle',
		    'title'   => __( 'Seitentitel im Banner', 'fau' ),
		    'label'   => __( 'Zeigt den Seitentitel im Banner an', 'fau' ),                
		    'default' => $defaultoptions['advanced_header_banner_display_title'],
		    'parent'  => 'header'
		), 
		'advanced_header_banner_display_slogan'	  => array(
		    'type'    => 'toggle',
		    'title'   => __( 'Slogan im Banner', 'fau' ),
		    'label'   => __( 'Zeigt den Slogan im Banner an', 'fau' ),                
		    'default' => $defaultoptions['advanced_header_banner_display_slogan'],
		    'parent'  => 'header'
		),					
		'breadcrumb_withtitle'	  => array(
		    'type'    => 'toggle',
		    'title'   => __( 'Website-Titel', 'fau' ),
		    'label'   => __( 'Zeige den Website-Titel oberhalb der Breadcrumb', 'fau' ),                
		    'default' => $defaultoptions['breadcrumb_withtitle'],
		    'parent'  => 'header'
		),   
			   
		'footer'  => array(
		    'type'    => 'section',
		    'title'   => __( 'Footer', 'fau' ),    
		    'desc'    => __( 'Einstellungen für den Fußteil des Webauftritts.', 'fau' ),
		),
	       
	    'advanced_footer_display_socialmedia'	  => array(
		    'type'    => 'toggle',
		    'title'   => __( 'Social Media', 'fau' ),
		    'label'   => __( 'Zeigt die Social Media Icons im Footerbereich unterhalb des technischen Menüs an', 'fau' ),                
		    'default' => $defaultoptions['advanced_footer_display_socialmedia'],
		    'parent'  => 'footer'
		), 
		'active_socialmedia_footer' => array(
		    'type'    => 'multiselectlist',
		    'title'   => __( 'Widget aktivieren', 'fau' ),
		    'label'   => __( 'Auf welchen Seiten soll das Widget angezeigt werden.', 'fau' ),
		    'liste'   => array(
				1 => __('Startseite','fau'),
				2 => __('Portalseiten','fau'),
				3 => __('Suche und Fehlerseiten','fau'),
				4 => __('Inhaltsseite mit Navi','fau'),
				5 => __('Standard Seiten','fau'),
				6 => __('Beiträge','fau'), 
				-1 => __('Auf allen Seiten','fau'), 
		    ),
		    'default' => $defaultoptions['active_socialmedia_footer'],
		    'parent'  => 'footer',
		),  
		'socialmedia' => array(
		    'type'    => 'toggle',
		    'title'   => __( 'Social Media Buttons in der ersten Widgetspalte anzeigen', 'fau' ),
		    'label'   => __( 'Schaltet die Social Media Buttons in der Widgetspalte an oder aus. Bitte beachten Sie, daß die anzuzeigenden Icons selbst als Menü verwaltet werden. Rufen Sie hierzu die Menüeinstellungen auf und bearbeiten dort das Social Media Menü.', 'fau' ),
		    'parent'  => 'footer',
		    'default' => $defaultoptions['socialmedia'],
		),  

		'socialmedia_buttons_title' => array(
		    'type'    => 'text',
		    'title'   => __( 'Titel', 'fau' ),
		    'label'   => __( 'Titel über den Social Media Icons in der ersten Spalte.', 'fau' ),               
		    'default' => $defaultoptions['socialmedia_buttons_title'],
		    'parent'  => 'footer',
		),      
	       
	    'advanced_footer_display_address'	  => array(
		    'type'    => 'toggle',
		    'title'   => __( 'Adresse', 'fau' ),
		    'label'   => __( 'Zeigt die Postadresse an, wie sie bei den Inhaltsdaten des Webauftritts angegeben wurde.', 'fau' ),                
		    'default' => $defaultoptions['advanced_footer_display_address'],
		    'parent'  => 'footer'
		),  
	    'contact_address_name' => array(
			'type'    => 'text',
			'title'   => __( 'Adressat', 'fau' ),
			'label'   => __( 'Erste Zeile der Adresse', 'fau' ),               
			'default' => $defaultoptions['contact_address_name'],
			'parent'  => 'footer'
		),  
	    'contact_address_name2' => array(
            'type'    => 'text',
            'title'   => __( 'Adressat (Zusatz)', 'fau' ),
            'label'   => __( 'Zweite Zeile der Adresse', 'fau' ),               
			'default' => $defaultoptions['contact_address_name2'],
		    'parent'  => 'footer'
		),  
		'contact_address_street' => array(
			'type'    => 'text',
			'title'   => __( 'Strasse', 'fau' ),
			'label'   => __( 'Strasse inkl. Hausnummer', 'fau' ),               
			'default' => $defaultoptions['contact_address_street'],
			'parent'  => 'footer'
        ),  
	    'contact_address_plz' => array(
			'type'    => 'text',
			'title'   => __( 'PLZ', 'fau' ),
			'label'   => __( 'Postleitzahl', 'fau' ),               
			'default' => $defaultoptions['contact_address_plz'],
			'parent'  => 'footer'
        ),  
	    'contact_address_ort' => array(
			'type'    => 'text',
			'title'   => __( 'Ort', 'fau' ),
			'label'   => __( 'Ortsname', 'fau' ),               
			'default' => $defaultoptions['contact_address_ort'],
			'parent'  => 'footer'
        ),  
	    'contact_address_country' => array(
			'type'    => 'text',
			'title'   => __( 'Land', 'fau' ),
			'label'   => __( 'Optionale Landesangabe', 'fau' ),               
			'default' => $defaultoptions['contact_address_country'],
			'parent'  => 'footer'
        ),  
	       
	      'newsbereich'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Artikelliste (Blogroll)', 'fau' ),        
              ),
	       
	       'start_max_newscontent'=> array(
                  'type'    => 'range-value',
                  'title'   => __( 'Zahl der Artikel (Gesamt)', 'fau' ),
                  'label'   => __( 'Anzahl der News auf der Startseite unterhalb des Sliders', 'fau' ),
                  'default' => $defaultoptions['start_max_newscontent'],
		   'parent'  => 'newsbereich',
		   'min'	    => 0,
		  'max'	    => 7,	
              ),  
	        'start_prefix_tag_newscontent' => array(
                  'type'    => 'text',
                  'title'   => __( 'Positionierungs-Tag', 'fau' ),
                  'label'   => __( 'Angabe des Tag-Prefixes, mit der die Position von definierten Artikel auf der Startseite gesteuert werden kann. Im Artikel wird dann dieser Tag plus eine Nummer von 1 bis 3 vergeben um die Position festzusetzen. <br>Beispiel bei einem gewählten Tag-Prefix "Startseite": Erster Artikel mit Tag "Startseite1", Zweiter Artikel mit Tag "Startseite2". Wenn mehrere Artikel den Tag "Startseite1" haben und nur eines davon gezeigt werden soll, wird der jüngste Artikel mit dem Tag angezeigt.', 'fau' ),               
                  'default' => $defaultoptions['start_prefix_tag_newscontent'],
		     'parent'  => 'newsbereich'
              ),  
	       
	       
	       'start_max_newspertag'=> array(
                  'type'    => 'range-value',
                  'title'   => __( 'Artikel mit gleichem Positionierungs-Tag', 'fau' ),
                  'label'   => __( 'Anzahl der Artikel mit dem gleichen Prefix-Tag (Positionierung), die angezeigt werden sollen. Normalerweise sollte hier nur 1 Artikel auf der ersten Position sein.', 'fau' ),
		   'min'	    => 1,
		  'max'	    => 5,
                  'default' => $defaultoptions['start_max_newspertag'],
		    'parent'  => 'newsbereich'
              ),  
	       'start_link_news_show' => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Kategorie verlinken', 'fau' ),
                  'label'   => __( 'Weitere Meldungen verlinken.', 'fau' ),               
                  'default' => $defaultoptions['start_link_news_show'],
		     'parent'  => 'newsbereich'
              ),  
		'start_link_news_cat' => array(
                  'type'    => 'category',
                  'title'   => __( 'Kategorie auswählen', 'fau' ),
                  'label'   => __( 'Unter den News erscheint ein Link auf eine Übersicht der Artikel. Hier wird die Kategorie dafür ausgewählt. Für den Fall, dass keine Artikel mit einem Prefix-Tag ausgestattet sind, wird diese Kategorie auch bei der Anzeige der ersten News verwendet.', 'fau' ),
                  'default' => $defaultoptions['start_link_news_cat'],
		     'parent'  => 'newsbereich'
              ), 
	        'start_link_news_linktitle' => array(
                  'type'    => 'text',
                  'title'   => __( 'Linktitel', 'fau' ),
                  'label'   => __( 'Verlinkungstitel für weitere Meldungen.', 'fau' ),               
                  'default' => $defaultoptions['start_link_news_linktitle'],
		     'parent'  => 'newsbereich'
              ),  
	       
	    'default_postthumb_always' => array(
		    'type'    => 'toggle',
		    'title'   => __( 'Artikelbild erzwingen', 'fau' ),
		    'label'   => __( 'Immer ein Artikelbild zu einer Nachricht zeigen. Wenn kein Artikelbild definiert wurde, nehme stattdessen ein Ersatzbild.', 'fau' ),      
		    'default' => $defaultoptions['default_postthumb_always'],
		    'parent'  => 'newsbereich'
              ), 

	    'default_postthumb_image' => array(
		    'type'    => 'image',
		    'maxwidth'	=> $defaultoptions['default_rwdimage_width'], 
		    'maxheight'	=> $defaultoptions['default_rwdimage_height'],
		    'title'   => __( 'Thumbnail Ersatzbild', 'fau' ),
		    'label'   => __( 'Ersatzbild für den Fall, daß ein Artikel kein eigenes Artikelbild definiert hat.', 'fau' ),               
		    'parent'  => 'newsbereich'
		),  
	       
	      'default_display_thumbnail_3_2' => array(
		    'type'    => 'toggle',
		    'title'   => __( 'Artikelbilder im 3:2 Format', 'fau' ),
		    'label'   => __( 'Artikelbilder der Artikelliste werden in ein festes 3:2 Format angezeigt. Sollten die Bilder höher oder Breiter sein, werden sie entsprechend proportional verkleinert.', 'fau' ),      
		    'default' => $defaultoptions['default_display_thumbnail_3_2'],
		    'parent'  => 'newsbereich'
              ), 
	       
	       'default_display_continue_link' => array(
		    'type'    => 'toggle',
		    'title'   => __( 'Weiterlesen Button', 'fau' ),
		    'label'   => __( 'Zeige den Weiterlesen-Button hinter jeder Nachricht.', 'fau' ),      
		    'default' => $defaultoptions['default_display_continue_link'],
		    'parent'  => 'newsbereich'
              ), 
	       
	        	
	    'topevents'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Top Events', 'fau' ),                      
            ), 
	      
	    'start_topevents_active' => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Aktivieren', 'fau' ),
                  'label'   => __( 'Anzeige der Top-Events aktivieren', 'fau' ),               
                  'default' => $defaultoptions['start_topevents_active'],
		  'parent'  => 'topevents'
            ),  
	     
	    'topevents_templates' => array(
		    'type'    => 'multiselectlist',
		    'title'   => __( 'Seitentypen', 'fau' ),
		    'label'   => __( 'Auf welchen Seiten sollen Top Events in der Sidebar angezeigt werden.', 'fau' ),
		    'liste'   => array(
				1 => __('Startseite','fau'),
      				2 => __('Portalseiten','fau'),
      				3 => __('Suche und Fehlerseiten','fau'),
		      ),
		    'default' => $defaultoptions['topevents_templates'],
		    'parent'  => 'topevents'
            ),     
           'start_topevents_max'=> array(
                  'type'    => 'range-value',
                  'title'   => __( 'Anzahl Top-Events', 'fau' ),
                  'label'   => __( 'Wieviele Top-Events sollen maximal angezeigt werden.', 'fau' ),
                  'default' => $defaultoptions['start_topevents_max'],
		  'min'	    => 1,
		  'max'	    => 6,		  
                  'parent'  => 'topevents'
            ),   
	       
	      'fallback_topevent_image' => array(
		    'type'    => 'image',
		    'maxwidth'	=> $defaultoptions['default_rwdimage_width'], 
		    'maxheight'	=> $defaultoptions['default_rwdimage_height'],
		    'title'   => __( 'Thumbnail Ersatzbild', 'fau' ),
		    'default' => $defaultoptions['fallback_topevent_image'],
		    'label'   => __( 'Ersatzbild für den Fall, daß für den Eventeintrag kein eigenes Bild definiert wurde.', 'fau' ),               
		    'parent'  => 'topevents'
		),  
	          
	       
	       
	    
	    'suchergebnisse'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Suchergebnisse', 'fau' ),                      
		),
	        
		'search_display_post_thumbnails' => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Thumbnails', 'fau' ),
                  'label'   => __( 'Bei den Suchergebnisse Thumbnails anzeigen, wenn diese vorhanden sind', 'fau' ),                
                  'default' => $defaultoptions['search_display_post_thumbnails'],
		  'parent'  => 'suchergebnisse'
		),   
		'search_display_post_cats'  => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Kategorien', 'fau' ),
                  'label'   => __( 'Bei den Suchergebnisse Kategorien der Beiträge anzeigen', 'fau' ),                
                  'default' => $defaultoptions['search_display_post_cats'],
		  'parent'  => 'suchergebnisse'
		),   
		'search_display_continue_arrow' => array(
                  'type'    => 'toggle',
		  'title'   => __( 'Weiterlesen Button', 'fau' ),
		  'label'   => __( 'Zeige den Weiterlesen-Button hinter jeder Nachricht.', 'fau' ),                  
                  'default' => $defaultoptions['search_display_continue_arrow'],
		  'parent'  => 'suchergebnisse'
		),   
		'default_search_excerpt_length' => array(
                  'type'    => 'range-value',
                  'title'   => __( 'Länge Textauszug', 'fau' ),
                  'label'   => __( 'Anzahl der maximalen Zeichen für den Textauszug bei der Ergebnisliste.', 'fau' ),                
                  'default' => $defaultoptions['default_search_excerpt_length'],
		    'min'   => 80,
		    'max'   => 500,
		    'step'  => 10,
		  'parent'  => 'suchergebnisse'
		),   
		'search_display_excerpt_morestring'=> array(
		    'type'    => 'text',
		    'title'   => __( 'Textabbruch', 'fau' ),
		    'label'   => __( 'Falls der Textauszug nach der vorgegebenen Länge abgeschnitten werden muss, können hier Trennzeichen angegeben werden.', 'fau' ),               
		    'default' => $defaultoptions['search_display_excerpt_morestring'],
		     'parent'  => 'suchergebnisse'
		), 
		'search_display_typenote' => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Typ anzeigen', 'fau' ),
                  'label'   => __( 'Zeige Inhaltstyp des Treffers an.', 'fau' ),                
                  'default' => $defaultoptions['search_display_typenote'],
		  'parent'  => 'suchergebnisse'
		),    

   
	       'search_allowfilter' => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Suche filterbar', 'fau' ),
                  'label'   => __( 'Erlaubt es, Suchergebnisse nach der Art des Dokumenttypes (Seiten, Beiträge, etc.) zu filtern.', 'fau' ),                
                  'default' => $defaultoptions['search_allowfilter'],
		  'parent'  => 'suchergebnisse'
		),    
	        'search_post_types_checked' => array(
		    'type'    => 'multiselectlist',
		    'title'   => __( 'Filter', 'fau' ),
		    'label'   => __( 'Vorab aktivierte Suchbereiche des Filters. In diesen wird gesucht, wenn der Nutzer der Seite keine Auswahl trifft oder diese nicht zur Verfügung gestellt wird.', 'fau' ),
		    'listeold'   => array(
				"page"		=> __('Seiten','fau'),
      				"post"		=> __('Artikel','fau'),
				"attachment"	=> __('Dokumente und Bilder','fau'),
		      ),
		    'liste' => fau_get_searchable_fields('list'),
		    'default' => $defaultoptions['search_post_types_checked'],
		    'parent'  => 'suchergebnisse'
            ),     
	       'search_notice_searchregion' => array(
		    'type'    => 'text',
		    'title'   => __( 'Hinweis zum Suchbereich', 'fau' ),
		    'label'   => __( 'Für Besucher der Website ist oft nicht klar, daß die Suchmaschine nur den einzelnen Webauftritt durchsucht und nicht beispielsweise alle Seiten der FAU. Dieser Texthinweis macht darauf aufmerksam.', 'fau' ),               
		    'default' => $defaultoptions['search_notice_searchregion'],
		     'parent'  => 'suchergebnisse'
		), 
   
	       
	       
          )
       ),
       
	
	
       'advanced'   => array(
           'tabtitle'   => __('Erweitert', 'fau'),
	   'user_level'	=> 1,
	   'capability'    => 'customize',
           'fields' => array(
               'bedienung'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Backend', 'fau' ),                      
              ),
             'advanced_beitragsoptionen'  => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Erweiterte Beitragsoptionen', 'fau' ),
                  'label'   => __( 'Bei der Bearbeitung von Beiträgen die erweiterten Optionen anzeigen.', 'fau' ),                
                  'default' => $defaultoptions['advanced_beitragsoptionen'],
		  'parent'  => 'bedienung'
              ),   
	      'advanced_topevent'  => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Top-Events', 'fau' ),
                  'label'   => __( 'Ermöglicht es Beiträge als Top-Event zu deklarieren und entsprechende Optionen freizuschalten.', 'fau' ),                
                  'default' => $defaultoptions['advanced_topevent'],
		  'parent'  => 'bedienung'
              ),   
	
	       'advanced_post_active_subtitle'	=> array(
                  'type'    => 'toggle',
                  'title'   => __( 'Untertitel (Beiträge)', 'fau' ),
                  'label'   => __( 'Erlaube die Eingabe von Untertitel bei Beiträgen.', 'fau' ),                
                  'default' => $defaultoptions['advanced_post_active_subtitle'],
		  'parent'  => 'bedienung'
              ),   
	       
	      'advanced_reveal_pages_id'	=> array(
                  'type'    => 'toggle',
                  'title'   => __( 'Zeige Seiten-Ids', 'fau' ),
                  'label'   => __( 'In der Übersicht der Seiten werden die Ids angezeigt.', 'fau' ),                
                  'default' => $defaultoptions['advanced_reveal_pages_id'],
		  'parent'  => 'bedienung'
              ),   
	       
	        'advanced_activate_page_langcode'	=> array(
                  'type'    => 'toggle',
                  'title'   => __( 'Seitensprache', 'fau' ),
                  'label'   => __( 'Aktiviert die Möglichkeit, pro Seite eine eigene Inhaltssprache zu deklarieren, die von dem Rest des Webauftritts abweicht. Deklariert wird dabei die Überschrift der Seite und dessen Inhaltsbereich. Die restlichen Bestandteile, inkl. der Sidebar bleiben in der Sprache, mit der die gesamte Website gekennzeichnet wurde. Achtung: Diese Option arbeitet nicht mit dem Workflow-Plugin für mehrsprachigen Webauftritten zusammen. Diese Option sollte nur dann verwendet werden, wenn anderssprachige Seiten eine Ausnahme auf dem Webauftritt darstellen. Für umfangreiche Webauftritte in verschiedenen Sprachen sind eigene sprachspezifische Webauftritte vorzuziehen. Webauftritte, die unterhalb einer Domain mehrmals die Sprachen wechseln und eine Mischung im Navigationsmenu haben, haben zudem ein schlechteres Suchmaschinen-Ranking.', 'fau' ),                
                  'default' => $defaultoptions['advanced_activate_page_langcode'],
		  'parent'  => 'bedienung'
              ),   
	       	       
  
	       
	       
	       
	      
	    'topmenulinks'  => array(
		    'type'    => 'section',
		    'title'   => __( 'Hauptmenü', 'fau' ),                      
		),
	       
	       'advanced_display_portalmenu_button' => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Menü Portal-Button anzeigen', 'fau' ),
                  'label'   => __( 'Der Button im Aufklappmenü verlinkt zur Seite des gewählten Oberpunktes. Mit dieser Option kann das Button bei Menüs, die nicht auf Klick, sondern auf Maushover öffnen, deaktiviert werden. Diese Option wirkt nicht, wenn das Menü einen Klick erfordert um zu öffnen.', 'fau' ),               
                  'default' => $defaultoptions['advanced_display_portalmenu_button'],
		  'parent'  => 'topmenulinks'
		),  
	        'menu_pretitle_portal' => array(
                  'type'    => 'text',
                  'title'   => __( 'Menü Portal-Button (Vortitel)', 'fau' ),
                  'label'   => __( 'Begriff vor dem Titel des gewählten Menüs', 'fau' ),               
                  'default' => $defaultoptions['menu_pretitle_portal'],
		    'parent'  => 'topmenulinks'
		),  
	        'menu_aftertitle_portal' => array(
                  'type'    => 'text',
                  'title'   => __( 'Menü Portal-Button (Nachtitel)', 'fau' ),
                  'label'   => __( 'Begriff nach dem Titel des gewählten Menüs', 'fau' ),               
                  'default' => $defaultoptions['menu_aftertitle_portal'],
		    'parent'  => 'topmenulinks'
		),  

	       
	       'advanced_display_portalmenu_forceclick'	  => array(
		    'type'    => 'toggle',
		    'title'   => __( 'Menü erfordert Klick', 'fau' ),
		    'label'   => __( 'Die Unterpunkte des Menüs öffnen sich nur bei einem Klick auf den jeweiligen Menüpunkt.', 'fau' ),                
		    'default' => $defaultoptions['advanced_display_portalmenu_forceclick'],
	//	    'ifmodvalue'    => -1,
	//	    'ifmodname'	=> 'website_type',
	    //	    'ifsuperadmin'   => true,
		    'parent'  => 'topmenulinks'
		), 
	       
	       
	       
                'contentmenus'  => array(
		    'type'    => 'section',
		    'title'   => __( 'Inhaltsmenüs', 'fau' ),                      
		),
                'default_submenu_entries' => array(
                  'type'    => 'range-value',
                  'title'   => __( 'Untermenüpunkte', 'fau' ),
                  'label'   => __( 'Anzahl der anzuzeigenden Untermenüpunkte (Zweite Ebene).', 'fau' ),                
                  'default' => $defaultoptions['default_submenu_entries'],
		    'min'   => 0,
		    'max'   => 10,
		    'step'  => 1,
		  'parent'  => 'contentmenus'
		),   
              
	         'fallback_submenu_image' => array(
		    'type'    => 'image',
		    'maxwidth'	=> $defaultoptions['default_image_sizes']['rwd-480-2-1']['width'], // $defaultoptions['default_rwdimage_2-1_width'],
		    'maxheight'	=> $defaultoptions['default_image_sizes']['rwd-480-2-1']['height'], // $defaultoptions['default_rwdimage_2-1_height'],
		    'title'   => __( 'Thumbnail Ersatzbild', 'fau' ),
		    'label'   => __( 'Ersatzbild für den Fall, daß eine verlinkte Seite kein eigenes Artikelbild definiert hat.', 'fau' ),               
		    'parent'  => 'contentmenus',
		    'default' => $defaultoptions['fallback_submenu_image'],
		),  
	       
	       

	       
	       'sidebaropt'  => array(
		    'type'	=> 'section',
		    'title'	=> __( 'Sidebar', 'fau' ),     
		    'desc'	=> __('Konfigurationen der Sidebar auf Seiten', 'fau'),
		    'user_level'	=> 2,
              ),
	       'advanced_page_sidebar_titleabove'	  => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Feld Titel oben', 'fau' ),
                  'label'   => __( 'Fragt ein eigenes Titelfeld über den Texteditor zum Text oben ab (Titel können allerdings auch im Editorfeld eingegeben werden)', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_sidebar_titleabove'],
		  'parent'  => 'sidebaropt'
              ), 
	       'advanced_page_sidebar_titlebelow'	  => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Feld Titel unten', 'fau' ),
                  'label'   => __( 'Fragt ein eigenes Titelfeld über den Texteditor zum Text unten ab (Titel können allerdings auch im Editorfeld eingegeben werden)', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_sidebar_titlebelow'],
		  'parent'  => 'sidebaropt'
              ), 
	       
	        'advanced_page_sidebar_useeditor_textabove'		  => array(
                  'type'    => 'toggle',
                  'title'   => __( 'WYSIWYG-Editor Text oben', 'fau' ),
                  'label'   => __( 'Erlaubt die Nutzung des WYSWYG-Editors für die Eingabe von Text in der Sidebar. Dies schließt auch HTML-Tags mit Bildern und Links ein. Andernfalls ist nur ein Text mit Absätzen möglich.', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_sidebar_useeditor_textabove'],
		  'parent'  => 'sidebaropt'
              ), 
	    'advanced_page_sidebar_useeditor_textbelow'		  => array(
                  'type'    => 'toggle',
                  'title'   => __( 'WYSIWYG-Editor Text unten', 'fau' ),
                  'label'   => __('Erlaubt die Nutzung des WYSWYG-Editors für die Eingabe von Text in der Sidebar. Dies schließt auch HTML-Tags mit Bildern und Links ein. Andernfalls ist nur ein Text mit Absätzen möglich.', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_sidebar_useeditor_textbelow'],
		  'parent'  => 'sidebaropt'
              ), 
	       
	       
	       
		'advanced_page_sidebar_personen_title'	  => array(
                  'type'    => 'text',
                  'title'   => __( 'Default Titel über Kontakte', 'fau' ),
                  'label'   => __( 'Optionaler Titel über einem ausgewählten Kontakt.', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_sidebar_personen_title'],
		  'parent'  => 'sidebaropt'
		), 
	       'advanced_page_sidebar_linkblock1_title'	  => array(
                  'type'    => 'text',
                  'title'   => __( 'Default Titel erster Linkblock', 'fau' ),
                  'label'   => __( 'Optionaler Titel über den ersten Linkblock, wenn dieser belegt ist.', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_sidebar_linkblock1_title'],
		  'parent'  => 'sidebaropt'
		), 
	       
	        'advanced_page_sidebar_linkblock2_title'	  => array(
                  'type'    => 'text',
                  'title'   => __( 'Default Titel zweiter Linkblock', 'fau' ),
                  'label'   => __( 'Optionaler Titel über den zweiten Linkblock, wenn dieser belegt ist.', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_sidebar_linkblock2_title'],
		  'parent'  => 'sidebaropt'
		), 
	        'advanced_page_sidebar_linkblock1_number'	  => array(
                  'type'    => 'range-value',
                  'title'   => __( 'Links im ersten Linkblock', 'fau' ),
                  'label'   => __( 'Wieviele Links können maximal im ersten Linkblock angegeben werden.', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_sidebar_linkblock1_number'],
		  'min'	    => 1,
		  'max'   => 10,
		  'parent'  => 'sidebaropt'
		), 
	         'advanced_page_sidebar_linkblock2_number'	  => array(
                  'type'    => 'range-value',
                  'title'   => __( 'Links im zweiten Linkblock', 'fau' ),
                  'label'   => __( 'Wieviele Links können maximal im zweiten Linkblock angegeben werden.', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_sidebar_linkblock2_number'],
		  'min'	    => 1,
		  'max'   => 10,
		  'parent'  => 'sidebaropt'
		), 
	        'advanced_page_sidebar_wpsidebar'		  => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Aktiviere Widget-Funktion', 'fau' ),
                  'label'   => __( 'Widgets die auf allen Seiten angezeigt werden sollen. Sollten Seitenspezifische Inhalte in der Sidebar angegeben worden sein, wird diese Sidebar darunter folgen. Diese Sidebar-Inhalte werden nicht auf Beiträgen gezeigt.', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_sidebar_wpsidebar'],
		  'parent'  => 'sidebaropt'
              ), 

	       
	       
		
	        'inhalte'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Metadaten und Inhalte', 'fau' ),     
		),
		'advanced_images_info_credits' => array(
		    'type'    => 'select',
		    'title'   => __( 'Copyright-Info ermitteln', 'fau' ),
		    'label'   => __( 'Definiert, ob die Copyright-Info eines Bildes sich aus dessen IPTC-Infos ermittelt oder durch die Texteingabe Beschreibung überschrieben werden kann.<br>Reihenfolge via IPTC: 1. IPTC-Copyright, 2. IPTC-Credit, 3. IPTC-Author, 4. Beschreibung, 5. IPTC-Caption, 6. Bildunterschrift. <br>Durch diese Auswahl kann die Beschreibung priorisiert werden.', 'fau' ),      
		    'liste'   => array('0' => __('IPTC-Feld Copyright hat Priorität', 'fau'), 
					'1' => __('Eingabefeld Beschreibung überschreibt IPTC und andere vorangige Felder.', 'fau')),
		    'default' => $defaultoptions['advanced_images_info_credits'],
		    'parent'  => 'inhalte'
		), 
	        'advanced_display_hero_credits'	  => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Copyright-Hinweis', 'fau' ),
                  'label'   => __( 'Auf der Startseite wird im Slider bzw. im Banner der Copyright-Hinweis des Bildes angezeigt, wenn vorhanden', 'fau' ),                
                  'default' => $defaultoptions['advanced_display_hero_credits'],
		  'parent'  => 'inhalte'
              ),   
	       
	        'google-site-verification' => array(
                  'type'    => 'text',
                  'title'   => __( 'Google Site Verification', 'fau' ),
                  'label'   => __( 'Zur Verifikation der Website als Property in den <a target="_blank" href="https://www.google.com/webmasters/tools/home">Google Webmaster Tools</a> wird die Methode über den HTML-Tag ausgewählt. Google erstellt dann auf der Einrichtungsseite eine HTML-Anweisung. Von dieser Anweisung kopiert man den Bestandteil, der im Attribut "content" angegeben ist. <br>Beispiel: <br>Google gibt den HTML-Code: &nbsp; &nbsp;<code>&lt;meta name="google-site-verification" content="BBssyCpddd8" /&gt;</code><br>  Dann geben Sie dies ein: <code>BBssyCpddd8</code> .', 'fau' ),               
                  'default' => $defaultoptions['google-site-verification'],
		     'parent'  => 'inhalte'
              ),  

	   
		'templates'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Templates', 'fau' ),     
		),
	        'advanced_template_page_start_display_content'	  => array(
                  'type'    => 'toggle',
                  'title'   => __( 'Startseite: Inhalt zeigen', 'fau' ),
                  'label'   => __( 'Auf der Startseite wird per Default nur die Artikelliste gezeigt. Mit diesen Schalter kann oberhalb der Artikelliste auch eigenen Inhalt eingeblendet werden.', 'fau' ),                
                  'default' => $defaultoptions['advanced_template_page_start_display_content'],
		  'parent'  => 'templates'
              ),   

	       
	       
	    ),    
	),    

	
	
	'debugmode'   => array(
           'tabtitle'   => __('Einstellungen im Debugmode', 'fau'),
	   'user_level'	=> 1,
	   'capability'    => 'customize',
           'fields' => array(
      

	      
	        'debugorgs'  => array(
		    'type'    => 'section',
		    'title'   => __( 'Organisatorisches', 'fau' ),                      
		),
	      

	       'debug_website_fakultaet'=> array(
		    'type'    => 'select',
		    'title'   => __( 'Fakultät setzen', 'fau' ),
		    'label'   => __( 'Das Logo kann im Titel eine Farbe bekommen. Diese wird üblicherweise aus dem Theme-Child bestimmt, kann aber mit dieser Variante zu Testzwecken auch überschrieben werden. Diese Funktion wird im produktiven Betrieb nicht zur Verfügung stehen.', 'fau' ),
		    'liste'   => array(
				
				    '' => __('Themefarbe (Default)','fau'), 
				    'zentral' => __('Zentral','fau'),  
				    'phil' => __('Phil','fau'),  
				    'med' => __('Med','fau'),  
				    'nat' => __('Nat','fau'),  
				    'rw' => __('RW','fau'),  
				    'tf' => __('TF','fau'),  
				    
			),
		    'default' => '',
		    'parent'  => 'debugorgs',
		    'ifmodvalue'    => true,
		    'ifmodname'	=> 'debugmode',
		    
		),  
	        'debug_orgabreadcrumb'=> array(
		    'type'    => 'toggle',
		    'title'   => __( 'Orga Breadcrumb simulieren', 'fau' ),
		    'label'   => __( 'Es wird eine organisatorische Breadcrumb simuliert und ausgegeben. Diese wird im produktiven Betrieb durch ein Plugin bereit gestellt werden.  Mit dieser Option kann man ohne Installation und Konfiguration des Plugins die Ausgabe mit Pseudodaten simulieren.', 'fau' ),                
		    'default' => false,
		    'parent'  => 'debugorgs',
		    'ifmodvalue'    => true,
		    'ifmodname'	=> 'debugmode',
		    
		),  
	       
	       'debugcontent'  => array(
		    'type'    => 'section',
		    'title'   => __( 'Plugins und Content', 'fau' ),                      
		),
	      
	       
	       'debug_sprachschalter'=> array(
		    'type'    => 'toggle',
		    'title'   => __( 'Sprachschalter simulieren', 'fau' ),
		    'label'   => __( 'Der Sprachschalter wird im produktiven Betrieb durch eines der Plugins CMS Workflow oder Language Switcher aktiviert. Mit dieser Option kann man ohne Installation und Konfiguration der Plugins den Sprachschalter simulieren - sprich den HTML-Code im Header erzeugen lassen. Falls einer der Plugins vorhanden und aktiv ist, wird diese Debug-Einstellung vorrang haben. ', 'fau' ),                
		    'default' => false,
		    'parent'  => 'debugcontent',
		    'ifmodvalue'    => true,
		    'ifmodname'	=> 'debugmode',
		    
		),  
	       
	       
	     

	    ),    
	),
	
	       
    )
);
	       
