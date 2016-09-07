<?php

/* 
 * Default Constants and values 
 */
$defaultoptions = array(
    'js-version'			=> '1.6',
	// Theme-Versionslinie
    'website_type'			=> 2,
	// website_type: 
	//  0 = Fakultaetsportal; 
	//  1 = Lehrstuehle, Departents 
	//  2 = Zentrale Einrichtungen, 
	//  3 = Kooperationen 
	// -1 = fau.de Portal (4 Spalter in Bühne, kein Link zur FAU. 
	//                       Nur wählbar für definierte Domains)
    'website_usefaculty'		=> '',
	// phil, med, nat, rw, tf
	// Setzt fest die Fakultät bei Wahl des Website-Types    
    'website_allow_fauportal'		=> array('www.fau.de',
						'www.fau.eu',
						'zuv.cms.rrze.uni-erlangen.de',
						'megli.zuv.cms.rrze.uni-erlangen.de',
						'heledir.zuv.cms.rrze.uni-erlangen.de',
						'annabon.zuv.cms.rrze.uni-erlangen.de',
						'tuilinn.zuv.cms.rrze.uni-erlangen.de',
						'cugu.zuv.cms.rrze.uni-erlangen.de',
						'alfirin.test.rrze.fau.de',
						'ithron.rrze.uni-erlangen.de',
						'test8.tindu.rrze.uni-erlangen.de',		
						'test4.tindu.rrze.uni-erlangen.de'),
	// welche Websites können bei website_type die Option -1 wählen 
    'default_home_orga'			=> 'fau',
	// Muss in $default_fau_orga_data auf erster Ebene vorhanden sein.	
    'default_faculty_useshorttitle'	=> false,   
    'optionpage-tab-default'		=> 'website',
    'content-width'			=> 770,
    'src-fallback-slider-image'		=> get_fau_template_uri().'/img/slider-fallback.jpg',
    'slider-category'			=> 'header',
    'slider-catid'			=> 1,    
    'src-scriptjs'			=> get_fau_template_uri() . '/js/scripts.min.js',
    'src-pluginsjs'			=> get_fau_template_uri() . '/js/libs/plugins.min.js',
    'default_slider_excerpt_length' => 240,
    'start_header_count'	    => 5,
    'start_max_newscontent'	    => 5,
    'start_max_newspertag'	    => 1,    
    'start_prefix_tag_newscontent'  => 'startseite',
    'start_link_news_cat'	    => 0,    
    'start_link_news_show'	    => 1,
    'start_link_news_linktitle'	    => __('Mehr Meldungen','fau'),
    'start_link_videoportal_socialmedia'    => false,
    'start_title_videoportal_socialmedia'   => __('Videoportal','fau'),
    'start_title_videoportal_url'	    => 'http://video.fau.de',

    'default_submenuthumb_src'	    =>  get_fau_template_uri().'/img/default-submenuthumb.png',
    'default_submenu_spalten'	    => 4,
    'default_submenu_entries'	    => 5,
    'menu_fallbackquote_show_excerpt'		=> 1,
    'menu_fallbackquote_excerpt_length'	=> 240,  
    'start_topevents_tag'	    => 'top',
    'start_topevents_max'	    => 1,
    'start_topevents_active'	    => true,
    
    'default_startseite-bannerbild-image_src'	    => get_fau_template_uri().'/img/bannerbild-tafel-1260x182.jpg',
    'startseite_banner_usedefault'  => false,

    'default_topevent_thumb_src'    => get_fau_template_uri().'/img/default-topeventthumb.png',
    'default_topevent_excerpt_length' => 100,

    /* Image Sizes */
    
    /* Default Thumb Size */
    'default_thumb_width'	    => 300,
    'default_thumb_height'	    => 150,
    'default_thumb_crop'	    => false,
    
    /* Image Sizes for Slider, Name: hero */
    'slider-image-width'	    => 1260,
    'slider-image-height'	    => 350,    
    'slider-image-crop'		    => true,
    
    /* Hero Banner - Name: herobanner */
    'default_startseite-bannerbild-image_width'	    => 1260,
    'default_startseite-bannerbild-image_height'    => 182,
    'default_startseite-bannerbild-image_crop'	    => true,
    
   
    /* Thumb for Image Menus in Content - Name: page-thumb */
    'default_submenuthumb_width'    => 220,
    'default_submenuthumb_height'   => 110,    
    'default_submenuthumb_crop'	    => false,
    
    /* Thumb of Topevent in Sidebar - Name: topevent-thumb */
    'default_topevent_thumb_width'  => 140,
    'default_topevent_thumb_height' => 90,
    'default_topevent_thumb_crop'   => true,  

    /* Thumb for Logos (used in carousel) - Name: logo-thumb */
    'default_logo_carousel_width'	=> 140,
    'default_logo_carousel_height'	=> 110,
    'default_logo_carousel_crop'	=> true,   

    
    /* Thumb for Posts in Lists - Name: post-thumb */
    'default_postthumb_width'	    => 220,
    'default_postthumb_height'	    => 147,
    'default_postthumb_crop'	    => false,
   
     /* Thumb for Posts, displayed in post/page single display - Name: post */
    'default_post_width'	    => 300,
    'default_post_height'	    => 200,
    'default_post_crop'		    => false, 
    
     /* Thumb for person-type; small for sidebar - Name: person-thumb */
    'default_person_thumb_width'    => 60,
    'default_person_thumb_height'   => 80,
    'default_person_thumb_crop'	    => true, 
     
     /* Thumb for person-type; small for content - Name: person-thumb-bigger */
    'default_person_thumb_bigger_width'    => 90,
    'default_person_thumb_bigger_height'   => 120,
    'default_person_thumb_bigger_crop'	    => true,     

     /* Thumb for person-type; small for content - Name: person-thumb-page */
    'default_person_thumb_page_width'    => 200,
    'default_person_thumb_page_height'   => 300,
    'default_person_thumb_page_crop'	 => true,         
    
   
  
    /* Images for gallerys - Name: gallery-full */
    'default_gallery_full_width'	=> 940,
    'default_gallery_full_height'	=> 470,
    'default_gallery_full_crop'		=> false,     
    
    /* Thumbs for gallerys - Name: gallery-thumb */
    'default_gallery_thumb_width'	=> 120,
    'default_gallery_thumb_height'	=> 80,
    'default_gallery_thumb_crop'	=> true,     

    /* Grid-Thumbs for gallerys - Name: gallery-grid */
    'default_gallery_grid_width'	=> 145,
    'default_gallery_grid_height'	=> 120,
    'default_gallery_grid_crop'		=> false,    
    
     /* 2 column Imagelists for gallerys - Name: image-2-col */
    'default_gallery_grid2col_width'	=> 300,
    'default_gallery_grid2col_height'	=> 200,
    'default_gallery_grid2col_crop'		=> true,        

    /* 4 column Imagelists for gallerys - Name: image-4-col */
    'default_gallery_grid4col_width'	=> 140,
    'default_gallery_grid4col_height'	=> 70,
    'default_gallery_grid4col_crop'		=> true,   
    
   
    'breadcrumb_root'			=> __('Startseite', 'fau'),
    'breadcrumb_delimiter'		=> ' <span>/</span>',
    'breadcrumb_beforehtml'		=> '<span class="active">', // '<span class="current">'; // tag before the current crumb
    'breadcrumb_afterhtml'		=> '</span>',
    'breadcrumb_uselastcat'		=> true,
    'breadcrumb_withtitle'		=> false,
    

    'default_logo_src'		    => get_fau_template_uri().'/img/logos/logo-default.png',
    'default_logo_height'	    => 65,
    'default_logo_width'	    => 240,

    
    
    'socialmedia'		    => 0,
    'active_socialmedia_footer'	    => array(0),  
    'socialmedia_buttons_title'	    => __('FAUSocial','fau'),
    
    'menu_pretitle_portal'	    => __('Portal', 'fau'),
    'menu_aftertitle_portal'	    => '',
    
   'contact_address_name'	    => __('Friedrich-Alexander-Universität', 'fau'),
   'contact_address_name2'	    => __('Erlangen-Nürnberg', 'fau'),
   'contact_address_street'	    => __('Schlossplatz 4', 'fau'),
   'contact_address_plz'	    => __('91054', 'fau'),
   'contact_address_ort'	    => __('Erlangen', 'fau'),
   
    'contact_address_country'	    => '',
    'display_nojs_notice'	    => 0,
    'display_nojs_note'		    => __('JavaScript wurde deaktiviert oder Ihr Browser unterstützt kein JavaScript. Alle Inhalte sind erreichbar, jedoch ist die Bedienung teilweise umständlicher.','fau'),
    'google-site-verification'	    => '',
    'default_mainmenu_number'	    => 4,
   

    'default_excerpt_morestring'    => '...',
    'default_excerpt_length'	    => 50,
    'default_anleser_excerpt_length'=> 300,
    'default_search_excerpt_length' => 300,
    
    'default_postthumb_src'	    => get_fau_template_uri().'/img/default-postthumb.png',

    'default_postthumb_always'	    => 1,

    'custom_excerpt_allowtags'	    => 'br',
    'url_banner-ad-notice'	    => 'https://www.fau.de/patente-gruendung-wissenstransfer/service-fuer-unternehmen/werben/',
    'title_banner-ad-notice'	    => __( 'Werbung', 'fau' ),
    
    'title_hero_post_categories'    =>  __( 'FAU aktuell', 'fau' ),
    'title_hero_post_archive'	    =>  __( 'FAU aktuell', 'fau' ),
    'title_hero_search'		    =>  __( 'Suche', 'fau' ),
    'title_hero_events'		    =>  __( 'Veranstaltungskalender','fau'),
    
    'advanced_beitragsoptionen'		=> true,
    'advanced_topevent'			=> true,
    'advanced_activateads'		=> false,
    'galery_link_original'		=> true,
    'advanced_page_start_herojumplink'	=> false,

    'advanced_post_active_subtitle'	=> true,

    'advanced_page_sidebar_titleabove'	=> true,
    'advanced_page_sidebar_titlebelow'	=> true,    
    'advanced_page_sidebar_useeditor_textabove'	=> false,
    'advanced_page_sidebar_useeditor_textbelow'	=> false,

    'advanced_page_sidebar_personen_title'	=> __('Kontakt','fau'), 
    'advanced_page_sidebar_linkblock1_number'	=> 3, 
    'advanced_page_sidebar_linkblock2_number'	=> 3,
    'advanced_page_sidebar_linkblock1_title'	=> __('Weitere Informationen','fau'), 
    'advanced_page_sidebar_linkblock2_title'	=> __('Sonstiges','fau'), 
    'advanced_page_sidebar_order_personlinks'	=> 0,
	// 0 = Kontakte, Links
	// 1 = Links, Kontakte
    'advanced_activate_post_comments'	=> false,
    'advanced_comments_notes_before'	    => __( 'Ihre E-Mail-Adresse wird nicht angezeigt. Verpflichtende Felder werden mit dem folgenden Zeichen markiert: <span class="required">*</span>', 'fau' ),
    'advanced_comments_disclaimer'          => __('Hinweis: Die Kommentare wurden von Lesern geschrieben und spiegeln deren persönliche Meinung wieder. Sie müssen nicht die Meinung der Universität oder der Fakultät repräsentieren.', 'fau' ),
    'advanced_comments_avatar'		    => false,
    'advanced_activate_synonyms'	    => false,
    'advanced_activate_glossary'	    => false,
  

    'post_display_category_below'	=> true,
    'post_display_tags_below'		=> true,
    'search_display_post_thumbnails'	=> true,
    'search_display_post_cats'		=> true,
    'search_display_continue_arrow'		=> true,
    'search_display_excerpt_morestring'		=> '...',
    'search_display_typenote'		=> true,
    'search_post_types'			=> array("page", "post",  "person", "attachment"),
    'search_allowfilter'		=> true,
   
    'plugin_fau_person_headline'	=> true,
    'plugin_fau_person_malethumb'	=> get_fau_template_uri().'/img/platzhalter-mann.png',
    'plugin_fau_person_femalethumb'	=> get_fau_template_uri().'/img/platzhalter-frau.png',
    
    'index_synonym_listall'		=> true,
    'index_glossary_listall'		=> true,
    
    'advanced_reveal_pages_id'		=> true,
    'advanced_images_info_credits'	=> 0,
    'advanced_display_hero_credits'	=> true,   
    'advanced_display_postthumb_credits'    => true,
    
    'advanced_forceclean_homelink'	=> true,
	// Links auf die Startseite werden aus dem Hauptmenu entfernt
    'advanced_forceclean_externlink'	=> true,
	// Links auf externe Seiten werden aus dem Hauptmenu entfernt
    
); 


 $categories=get_categories(array('orderby' => 'name','order' => 'ASC'));
 foreach($categories as $category) {
     if (!is_wp_error( $category )) {
	$currentcatliste[$category->cat_ID] = $category->name.' ('.$category->count.' '.__('Einträge','fau').')';
     }
 }        

$setoptions = array(
    'fau_theme_options'   => array(
       
       'website'   => array(
           'tabtitle'   => __('Webauftritt', 'fau'),
           'fields' => array(
	       
	        'webgroup'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Daten zum Webauftritt', 'fau' ),                      
                ),
	       
	        'website_type'=> array(
		    'type'    => 'select',
		    'title'   => __( 'Typ', 'fau' ),
		    'label'   => __( 'Bitte wählen Sie hier aus, um welcherart Webauftritt es sich handelt.', 'fau' ),
		    'liste'   => array(
				    0 => __('Fakultätsportal','fau'), 
				    1 => __('Department, Lehrstuhl, Einrichtung','fau'),  
				    2 => __('Zentrale Einrichtung','fau') ,
				    3 => __('Website für uniübergreifende Kooperationen mit Externen','fau') ),
		    'default' => $defaultoptions['website_type'],
		    'parent'  => 'webgroup'
		    
		),  
	    'default_faculty_useshorttitle' => array(
		    'type'    => 'bool',
		    'title'   => __( 'Fakultätslink', 'fau' ),
		    'label'   => __( 'Textlink zur Fakultät verkürzen auf Abkürzung. <br>Diese Option ist nur bei Nutzung eines Fakultätsthemes aktiv.', 'fau' ), 
		    'default' => $defaultoptions['default_faculty_useshorttitle'],
		    'parent'  => 'webgroup'
		),      
	       
	       
		'startseite_banner_image' => array(
		    'type'    => 'image',
		    'maxwidth'	=> 1260,
		    'maxheight'	=> 182,
		    'title'   => __( 'Banner Startseite', 'fau' ),
		    'label'   => __( 'Festes Banner für die Startseite (Template für Lehrstühle und Einrichtungen) im Format 1260x182 Pixel', 'fau' ),               
		    'parent'  => 'webgroup'
              ),  
	       
	       
               'pubadresse'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Öffentliche Adresse im Fußteil', 'fau' ),                      
              ),
              'contact_address_name' => array(
                  'type'    => 'text',
                  'title'   => __( 'Adressat', 'fau' ),
                  'label'   => __( 'Erste Zeile der Adresse', 'fau' ),               
                  'default' => $defaultoptions['contact_address_name'],
		  'parent'  => 'pubadresse'
              ),  
	       'contact_address_name2' => array(
                  'type'    => 'text',
                  'title'   => __( 'Adressat (Zusatz)', 'fau' ),
                  'label'   => __( 'Zweite Zeile der Adresse', 'fau' ),               
                  'default' => $defaultoptions['contact_address_name2'],
		    'parent'  => 'pubadresse'
              ),  
	      'contact_address_street' => array(
                  'type'    => 'text',
                  'title'   => __( 'Strasse', 'fau' ),
                  'label'   => __( 'Strasse inkl. Hausnummer', 'fau' ),               
                  'default' => $defaultoptions['contact_address_street'],
		   'parent'  => 'pubadresse'
              ),  
	       'contact_address_plz' => array(
                  'type'    => 'text',
                  'title'   => __( 'PLZ', 'fau' ),
                  'label'   => __( 'Postleitzahl', 'fau' ),               
                  'default' => $defaultoptions['contact_address_plz'],
		    'parent'  => 'pubadresse'
              ),  
	       'contact_address_ort' => array(
                  'type'    => 'text',
                  'title'   => __( 'Ort', 'fau' ),
                  'label'   => __( 'Ortsname', 'fau' ),               
                  'default' => $defaultoptions['contact_address_ort'],
		    'parent'  => 'pubadresse'
              ),  
	       'contact_address_country' => array(
                  'type'    => 'text',
                  'title'   => __( 'Land', 'fau' ),
                  'label'   => __( 'Optionale Landesangabe', 'fau' ),               
                  'default' => $defaultoptions['contact_address_country'],
		  'parent'  => 'pubadresse'
              ),  
	     
   
          )
       ),
       
       'socialmedia'   => array(
           'tabtitle'   => __('Social Media Footer', 'fau'),
           'fields' => array(
              'active_socialmedia_footer' => array(
                  'type'    => 'multiselectlist',
                  'title'   => __( 'Social Media Footer anzeigen', 'fau' ),
                  'label'   => __( 'Auf welchen Seiten soll der Social Media Footer angezeigt werden.', 'fau' ),
		  'liste'   => array(
				1 => __('Startseite','fau'),
      				2 => __('Portalseiten','fau'),
      				3 => __('Suche und Fehlerseiten','fau'),
      				4 => __('Inhaltsseite mit Navi','fau'),
      				5 => __('Standard Seiten','fau'),
      				6 => __('Beiträge','fau'),       
		      ),
                  'default' => $defaultoptions['active_socialmedia_footer'],
              ),  
	       
              'socialmedia' => array(
                  'type'    => 'bool',
                  'title'   => __( 'Buttons anzeigen', 'fau' ),
                  'label'   => __( 'Welche Social Media Buttons sollen auf der Startseite angezeigt werden.', 'fau' ),
                 
                  'default' => $defaultoptions['socialmedia'],
              ),  
	        'socialmedia_buttons_title' => array(
                  'type'    => 'text',
                  'title'   => __( 'Titel Socialmediabereich', 'fau' ),
                  'label'   => __( 'Titel über den Social Media Icons im Social Media Footer.', 'fau' ),               
                  'default' => $defaultoptions['socialmedia_buttons_title'],
		),        
	       
	       
	      'sm-list'  => array(
		  'type'    => 'urlchecklist',
		  'title'   => __( 'Social Media Portale', 'fau' ),
		  'liste'   => $default_socialmedia_liste,
	      ), 
	     'start_link_videoportal_socialmedia'  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Verlinke Videoportal', 'fau' ),
                  'label'   => __( 'Verlinke Videoportal auf dem Social Media Fußteil der Startseite', 'fau' ),
                  'default' => $defaultoptions['start_link_videoportal_socialmedia'],
              ),     
	      'start_title_videoportal_socialmedia' => array(
                  'type'    => 'text',
                  'title'   => __( 'Verlinkungstext Videoportal', 'fau' ),
                  'label'   => __( 'Text mit der auf das Videoportal im Social Media Fußteil verlinkt wird.', 'fau' ),               
                  'default' => $defaultoptions['start_title_videoportal_socialmedia'],
              ), 
	       
	       'start_title_videoportal_url' => array(
                  'type'    => 'url',
                  'title'   => __( 'URL Videoportal', 'fau' ),
                  'label'   => __( 'URL zum Videoportal. Diese sollte normalerweise auf <code>video.fau.de</code> bleiben. Manchmal nutzt man aber vielleicht ein anderes Portal, so dass man hier die URL ändern kann.', 'fau' ),               
                  'default' => $defaultoptions['start_title_videoportal_url'],
              ), 
	       
	       
	       
	       
          )
       ),
       'allgemeines'   => array(
           'tabtitle'   => __('Allgemeine Einstellungen', 'fau'),
	   'user_level'	=> 1,
           'fields' => array(
              
              'menu_pretitle_portal' => array(
                  'type'    => 'text',
                  'title'   => __( 'Menü Portal-Button (Vortitel)', 'fau' ),
                  'label'   => __( 'Begriff vor dem Titel des gewählten Menüs', 'fau' ),               
                  'default' => $defaultoptions['menu_pretitle_portal'],
              ),  
	        'menu_aftertitle_portal' => array(
                  'type'    => 'text',
                  'title'   => __( 'Menü Portal-Button (Nachtitel)', 'fau' ),
                  'label'   => __( 'Begriff nach dem Titel des gewählten Menüs', 'fau' ),               
                  'default' => $defaultoptions['menu_aftertitle_portal'],
              ),  
	       
	      'menu_fallbackquote_show_excerpt' => array(
                  'type'    => 'bool',
                  'title'   => __( 'Zitatersatz', 'fau' ),
                  'label'   => __( 'Wenn bei einem Menupunkt auf oberster Ebene kein Zitat vorgegeben ist, zeige stattdessen einen Auszug der Seite.', 'fau' ),                
                  'default' => $defaultoptions['menu_fallbackquote_show_excerpt'],
              ),  
	       
	       
	     'google-site-verification' => array(
                  'type'    => 'text',
                  'title'   => __( 'Google Site Verification', 'fau' ),
                  'label'   => __( 'Meta-Tag zur Identifikation der Inhaberschaft gegenüber Google. geben Sie hier den Content-Bestand an für die Identifikation mittels Meta-Tag.', 'fau' ),               
                  'default' => $defaultoptions['google-site-verification'],
              ),  
	      'url_banner-ad-notice'	 => array(
                  'type'    => 'url',
                  'title'   => __( 'Werbebanner Infolink', 'fau' ),
                  'label'   => __( 'URL zu einer Seite, die bei einem Klick auf den Hinweis zur Werbung aufgerufen wird.', 'fau' ),               
                  'default' => $defaultoptions['url_banner-ad-notice'],
              ),  
	       'title_banner-ad-notice'	 => array(
                  'type'    => 'text',
                  'title'   => __( 'Hinweistitel für Werbebanner', 'fau' ),
                  'label'   => __( 'Aus gesetzlichen Gründen muss vor Werbebannern ein Hinweis stehen, daß es sich um eben solche Werbung handelt. Üblicherweise reicht ein Titel "Werbung" o.ä.. Dieser Titel kann hier angegeben oder geändert werden.', 'fau' ),               
                  'default' => $defaultoptions['title_banner-ad-notice'],
              ),  
   
		'title_hero_post_categories'	 => array(
		    'type'    => 'text',
		    'title'   => __( 'Bühnentitel Kategorieseiten', 'fau' ),
		    'label'   => __( 'Im Bühnenteil wird ein Titel großflächig hinterlegt. Dieser kann hier für Kategorieseiten von Nachrichten hinterlegt werden.', 'fau' ),               
		    'default' => $defaultoptions['title_hero_post_categories'],
		), 
		'title_hero_post_archive'	 => array(
		    'type'    => 'text',
		    'title'   => __( 'Bühnentitel Beitragsarchiv', 'fau' ),
		    'label'   => __( 'Im Bühnenteil wird ein Titel großflächig hinterlegt. Dieser kann hier für Archivseiten von Nachrichten hinterlegt werden.', 'fau' ),               
		    'default' => $defaultoptions['title_hero_post_archive'],
		), 
	       'title_hero_search'	 => array(
		    'type'    => 'text',
		    'title'   => __( 'Bühnentitel Suche', 'fau' ),
		    'label'   => __( 'Im Bühnenteil wird ein Titel großflächig hinterlegt. Dieser kann hier für Suchergebnisseiten hinterlegt werden.', 'fau' ),               
		    'default' => $defaultoptions['title_hero_search'],
		), 
	       'title_hero_events'	 => array(
		    'type'    => 'text',
		    'title'   => __( 'Bühnentitel Veranstaltungen', 'fau' ),
		    'label'   => __( 'Im Bühnenteil wird ein Titel großflächig hinterlegt. Dieser kann hier für Seiten zu Veranstaltungen hinterlegt werden.', 'fau' ),               
		    'default' => $defaultoptions['title_hero_events'],
		), 
	           
	       
	       

	       
	       
	     'postoptions'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Beiträge', 'fau' ),                      
              ),
	       
	       'post_display_category_below' => array(
                  'type'    => 'bool',
                  'title'   => __( 'Zeige Kategorien', 'fau' ),
                  'label'   => __( 'Liste der Kategorien unter dem Beitrag anzeigen', 'fau' ),                
                  'default' => $defaultoptions['post_display_category_below'],
		  'parent'  => 'postoptions'
              ),  
	       
	
	       
	       
		'suchergebnisse'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Suchergebnisse', 'fau' ),                      
		),
	       
		'search_display_post_thumbnails' => array(
                  'type'    => 'bool',
                  'title'   => __( 'Zeige Thumbs', 'fau' ),
                  'label'   => __( 'Bei den Suchergebnisse Thumbnails anzeigen, wenn diese vorhanden sind', 'fau' ),                
                  'default' => $defaultoptions['search_display_post_thumbnails'],
		  'parent'  => 'suchergebnisse'
		),   
		'search_display_post_cats'  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Zeige Kategorien', 'fau' ),
                  'label'   => __( 'Bei den Suchergebnisse Kategorien der Beiträge anzeigen', 'fau' ),                
                  'default' => $defaultoptions['search_display_post_cats'],
		  'parent'  => 'suchergebnisse'
		),   
		'search_display_continue_arrow' => array(
                  'type'    => 'bool',
                  'title'   => __( 'Weiterlesen-Pfeil', 'fau' ),
                  'label'   => __( 'Zeige verlinkten Pfeil zum Weiterlesen.', 'fau' ),                
                  'default' => $defaultoptions['search_display_continue_arrow'],
		  'parent'  => 'suchergebnisse'
		),   
		'default_search_excerpt_length' => array(
                  'type'    => 'number',
                  'title'   => __( 'Länge Textauszug', 'fau' ),
                  'label'   => __( 'Anzahl der maximalen Zeichen für den Textauszug bei der Ergebnisliste.', 'fau' ),                
                  'default' => $defaultoptions['default_search_excerpt_length'],
		  'parent'  => 'suchergebnisse'
		),   
		'search_display_excerpt_morestring'=> array(
		    'type'    => 'text',
		    'title'   => __( 'Textabbruch', 'fau' ),
		    'label'   => __( 'Falls der Textauszug nach der vorgegebenen Länge abgeschnitten werden muss, können hier Trennzeichen angegeben werden.', 'fau' ),               
		    'default' => $defaultoptions['search_display_excerpt_morestring'],
		), 
		'search_display_typenote' => array(
                  'type'    => 'bool',
                  'title'   => __( 'Typ anzeigen', 'fau' ),
                  'label'   => __( 'Zeige Inhaltstyp des Treffers an.', 'fau' ),                
                  'default' => $defaultoptions['search_display_typenote'],
		  'parent'  => 'suchergebnisse'
		),    
	       'search_allowfilter' => array(
                  'type'    => 'bool',
                  'title'   => __( 'Suche filterbar', 'fau' ),
                  'label'   => __( 'Erlaubt es, Suchergebnisse nach der Art des Dokumenttypes (Seiten, Beiträge, etc.) zu filtern.', 'fau' ),                
                  'default' => $defaultoptions['search_allowfilter'],
		  'parent'  => 'suchergebnisse'
		),    
	       
	          

	       
          )
       ),
        'templates'   => array(
           'tabtitle'   => __('Templates', 'fau'),
	   'user_level'	=> 1,
           'fields' => array(
                            
	      'newsbereich'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Startseite Nachrichtenbereich', 'fau' ),                      
              ),
	       
	       'start_max_newscontent'=> array(
                  'type'    => 'select',
                  'title'   => __( 'Zahl der Artikel (Gesamt)', 'fau' ),
                  'label'   => __( 'Anzahl der News auf der Startseite unterhalb des Sliders', 'fau' ),
		  'liste'   => array(0=> 0, 1=> 1, 2 => 2,3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7),
                  'default' => $defaultoptions['start_max_newscontent'],
		   'parent'  => 'newsbereich'
              ),  
	        'start_prefix_tag_newscontent' => array(
                  'type'    => 'text',
                  'title'   => __( 'Positionierungs-Tag', 'fau' ),
                  'label'   => __( 'Angabe des Tag-Prefixes, mit der die Position von definierten Artikel auf der Startseite gesteuert werden kann. Im Artikel wird dann dieser Tag plus eine Nummer von 1 bis 3 vergeben um die Position festzusetzen. <br>Beispiel bei einem gewählten Tag-Prefix "Startseite": Erster Artikel mit Tag "Startseite1", Zweiter Artikel mit Tag "Startseite2". Wenn mehrere Artikel den Tag "Startseite1" haben und nur eines davon gezeigt werden soll, wird der jüngste Artikel mit dem Tag angezeigt.', 'fau' ),               
                  'default' => $defaultoptions['start_prefix_tag_newscontent'],
		     'parent'  => 'newsbereich'
              ),  
	       
	       
	       'start_max_newspertag'=> array(
                  'type'    => 'select',
                  'title'   => __( 'Artikel mit gleichem Positionierungs-Tag', 'fau' ),
                  'label'   => __( 'Anzahl der Artikel mit dem gleichen Prefix-Tag (Positionierung), die angezeigt werden sollen. Normalerweise sollte hier nur 1 Artikel auf der ersten Position sein.', 'fau' ),
		   'liste'   => array(1 => 1, 2 => 2,3 => 3, 4 => 4, 5 => 5),
                  'default' => $defaultoptions['start_max_newspertag'],
		    'parent'  => 'newsbereich'
              ),  
	       'start_link_news_show' => array(
                  'type'    => 'bool',
                  'title'   => __( 'Artikel verlinken', 'fau' ),
                  'label'   => __( 'Weitere Meldungen verlinken.', 'fau' ),               
                  'default' => $defaultoptions['start_link_news_show'],
		     'parent'  => 'newsbereich'
              ),  
		'start_link_news_cat' => array(
                  'type'    => 'select',
                  'title'   => __( 'Artikel-Kategorie', 'fau' ),
                  'label'   => __( 'Unter den News erscheint ein Link auf eine Übersicht der Artikel. Hier wird die Kategorie dafür ausgewählt. Für den Fall, dass keine Artikel mit einem Prefix-Tag ausgestattet sind, wird diese Kategorie auch bei der Anzeige der ersten News verwendet.', 'fau' ),
                  'liste'   => $currentcatliste,
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
		    'type'    => 'select',
		    'title'   => __( 'Immer ein Artikelbild anzeigen', 'fau' ),
		    'label'   => __( 'Immer ein Artikelbild zu einer Nachricht zeigen. Wenn kein Artikelbild definiert wurde, nehme stattdessen ein Ersatzbild.', 'fau' ),      
		    'liste'   => array(1 => __('Ja', 'fau'), 0 => __('Nein', 'fau')),
		    'default' => $defaultoptions['default_postthumb_always'],
		    'parent'  => 'newsbereich'
              ), 
	        	
	       
	       
	       
           
             'topevents'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Startseite Top Events', 'fau' ),                      
              ), 
	       'start_topevents_active' => array(
                  'type'    => 'bool',
                  'title'   => __( 'Aktivieren', 'fau' ),
                  'label'   => __( 'Anzeige der Top-Events auf der Startseite aktivieren', 'fau' ),               
                  'default' => $defaultoptions['start_topevents_active'],
		  'parent'  => 'topevents'
              ),  
	    'start_topevents_tag' => array(
                  'type'    => 'text',
                  'title'   => __( 'Schlagwort', 'fau' ),
                  'label'   => __( 'Schlagwort mit dem Beiträge als ausgestattet sein müssen, damit sie als Top-Event angezeigt werden.', 'fau' ),               
                  'default' => $defaultoptions['start_topevents_tag'],
		  'parent'  => 'topevents'
              ),  
	       'start_topevents_max'=> array(
                  'type'    => 'select',
                  'title'   => __( 'Zahl der Top-Events', 'fau' ),
                  'label'   => __( 'Wieviele Top-Events sollen maximal auf der Startseite angezeigt werden', 'fau' ),
		  'liste'   => array(1 => 1,2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6),
                  'default' => $defaultoptions['start_topevents_max'],
                  'parent'  => 'topevents'
              ),  
	       'sliderpars'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Startseite Fakultät Slider', 'fau' ),                      
              ),
              
	     'start_header_count'=> array(
                  'type'    => 'select',
                  'title'   => __( 'Zahl der Slides', 'fau' ),
                  'label'   => __( 'Anzahl der Slides von verlinkten Top-Artikeln', 'fau' ),
		  'liste'   => array(2 => 2,3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7),
                  'default' => $defaultoptions['start_header_count'],
                   'parent'  => 'sliderpars'
              ), 

               
              'slider-catid' => array(
                  'type'    => 'select',
                  'title'   => __( 'Kategorie', 'fau' ),
                  'label'   => __( 'Bitte wählen Sie die Kategorie der Artikel aus die im Slider erscheinen sollen.', 'fau' ),
                  'liste'   => $currentcatliste,
                  'default' => $defaultoptions['slider-catid'],
                   'parent'  => 'sliderpars'
              ), 

          )
       ), 
       'advanced'   => array(
           'tabtitle'   => __('Erweitert', 'fau'),
	   'user_level'	=> 1,
           'fields' => array(
               'bedienung'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Backend', 'fau' ),                      
              ),
             'advanced_beitragsoptionen'  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Erweiterte Beitragsoptionen', 'fau' ),
                  'label'   => __( 'Bei der Bearbeitung von Beiträgen die erweiterten Optionen anzeigen.', 'fau' ),                
                  'default' => $defaultoptions['advanced_beitragsoptionen'],
		  'parent'  => 'bedienung'
              ),   
	      'advanced_topevent'  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Top-Events', 'fau' ),
                  'label'   => __( 'Ermöglicht es Beiträge als Top-Event zu deklarieren und entsprechende Optionen freizuschalten.', 'fau' ),                
                  'default' => $defaultoptions['advanced_topevent'],
		  'parent'  => 'bedienung'
              ),   
	      'advanced_activateads' => array(
                  'type'    => 'bool',
                  'title'   => __( 'Werbebanner', 'fau' ),
                  'label'   => __( 'Aktiviert die Möglichkeit, Werbebanner zu verwalten.', 'fau' ),                
                  'default' => $defaultoptions['advanced_activateads'],
		  'parent'  => 'bedienung'
              ),   
	       'advanced_activate_synonyms'  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Synonyme', 'fau' ),
                  'label'   => __( 'Aktiviert die Verwaltung von Synonymen.', 'fau' ),                
                  'default' => $defaultoptions['advanced_activate_synonyms'],
		  'parent'  => 'bedienung'
              ),   
		'advanced_activate_glossary'  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Glossar', 'fau' ),
                  'label'   => __( 'Aktiviert die Verwaltung von Glossareinträgen.', 'fau' ),                
                  'default' => $defaultoptions['advanced_activate_glossary'],
		  'parent'  => 'bedienung'
		),  

	       'advanced_post_active_subtitle'	=> array(
                  'type'    => 'bool',
                  'title'   => __( 'Untertitel (Beiträge)', 'fau' ),
                  'label'   => __( 'Erlaube die Eingabe von Untertitel bei Beiträgen.', 'fau' ),                
                  'default' => $defaultoptions['advanced_post_active_subtitle'],
		  'parent'  => 'bedienung'
              ),   
	       
	      'advanced_reveal_pages_id'	=> array(
                  'type'    => 'bool',
                  'title'   => __( 'Zeige Seiten-Ids', 'fau' ),
                  'label'   => __( 'In der Übersicht der Seiten werden die Ids angezeigt.', 'fau' ),                
                  'default' => $defaultoptions['advanced_reveal_pages_id'],
		  'parent'  => 'bedienung'
              ),    
	       	       
  
	       
	       
	       
	      'design'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Design', 'fau' ),       
		  'user_level'	=> 2,
              ),
	        'advanced_page_start_herojumplink' => array(
                  'type'    => 'bool',
                  'title'   => __( 'Sprunglink unter der Bühne', 'fau' ),
                  'label'   => __( 'Aktiviert die Schaltung eines Sprunglinks unterhalb der Bühne der Startseite, wenn das Browserfenster eine Größe zwischen 700px und 900px Höhe hat.', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_start_herojumplink'],
		  'parent'  => 'design'
		),  

		'galery_link_original'	  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Verlinke Galerybilder', 'fau' ),
                  'label'   => __( 'Bei der Anzeige einer Defaultgalerie unter der Bildunterschrift eine Verlinkung auf das Originalbild einschalten', 'fau' ),                
                  'default' => $defaultoptions['galery_link_original'],
		  'parent'  => 'design'
		),   
	       'advanced_display_hero_credits'	  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Copyright-Hinweis Startseite', 'fau' ),
                  'label'   => __( 'Auf der Startseite wird im Slider bzw. im Banner der Copyright-Hinweis des Bildes angezeigt, wenn vorhanden', 'fau' ),                
                  'default' => $defaultoptions['advanced_display_hero_credits'],
		  'parent'  => 'design'
              ),  
	       'advanced_display_postthumb_credits'	  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Copyright-Hinweis Beiträge', 'fau' ),
                  'label'   => __( 'In Beiträgen wird das Artikelbild mit einem Copyright-Hinweis des Bildes versehen, wenn vorhanden', 'fau' ),                
                  'default' => $defaultoptions['advanced_display_postthumb_credits'],
		  'parent'  => 'design'
              ), 
	          'advanced_images_info_credits' => array(
		    'type'    => 'select',
		    'title'   => __( 'Copyright-Info ermitteln', 'fau' ),
		    'label'   => __( 'Definiert, ob die Copyright-Info eines Bildes sich aus dessen IPTC-Infos ermittelt oder durch die Texteingabe Beschreibung überschrieben werden kann.<br>Reihenfolge via IPTC: 1. IPTC-Copyright, 2. IPTC-Credit, 3. IPTC-Author, 4. Beschreibung, 5. IPTC-Caption, 6. Bildunterschrift. <br>Durch diese Auswahl kann die Beschreibung priorisiert werden.', 'fau' ),      
		    'liste'   => array('0' => __('IPTC-Feld Copyright hat Priorität', 'fau'), 
					'1' => __('Eingabefeld Beschreibung überschreibt IPTC und andere vorangige Felder.', 'fau')),
		    'default' => $defaultoptions['advanced_images_info_credits'],
		    'parent'  => 'design'
              ), 
	       
	        'breadcrumb'  => array(
		    'type'    => 'section',
		    'title'   => __( 'Breadcrumb', 'fau' ),                      
		),
		'breadcrumb_root'	 => array(
		    'type'    => 'text',
		    'title'   => __( 'Titel Startseite in Breadcrumb', 'fau' ),
		    'label'   => __( 'Definiert, wie der Link zur Startseite in der Breadcrumb aussehen soll. Per Default sollte hier die offizielle URL stehen; bspw. <code>phil.fau.de</code>.', 'fau' ),               
		    'default' => $defaultoptions['breadcrumb_root'],
		    'parent'  => 'breadcrumb'
		), 
		'breadcrumb_withtitle'	  => array(
		    'type'    => 'bool',
		    'title'   => __( 'Website-Titel', 'fau' ),
		    'label'   => __( 'Zeige den Website-Titel oberhalb der Breadcrumb', 'fau' ),                
		    'default' => $defaultoptions['breadcrumb_withtitle'],
		    'parent'  => 'breadcrumb'
		),   
	       
	        'topmenulinks'  => array(
		    'type'    => 'section',
		    'title'   => __( 'Hauptmenü', 'fau' ),                      
		),
	       'advanced_forceclean_homelink'	  => array(
		    'type'    => 'bool',
		    'title'   => __( 'Links auf Startseite', 'fau' ),
		    'label'   => __( 'Links auf die Startseite werden aus dem Hauptmenu entfernt, da diese unnötig sind (das Logo der Website verlinkt bereits zur Startseite)', 'fau' ),                
		    'default' => $defaultoptions['advanced_forceclean_homelink'],
		    'parent'  => 'topmenulinks'
		),  
	       'advanced_forceclean_externlink'	  => array(
		    'type'    => 'bool',
		    'title'   => __( 'Externe Links', 'fau' ),
		    'label'   => __( 'Links auf externe Seiten werden aus dem Hauptmenu entfernt. Im Hauptmenü sollen aus Gründen der Usability nur Links auf Seiten des eigenen Angebots stehen. Externe Links gehören in andere Menüs (z.B. Metanavigation, Footer oder Quicklinks) oder in den Text der Seiten.', 'fau' ),                
		    'default' => $defaultoptions['advanced_forceclean_externlink'],
		    'parent'  => 'topmenulinks'
		),  
	    
	       
	       
	       'sidebaropt'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Erweiterte Einstellunge für Sidebars (von Seiten)', 'fau' ),     
		  'user_level'	=> 2,
              ),
	       'advanced_page_sidebar_titleabove'	  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Feld Titel oben', 'fau' ),
                  'label'   => __( 'Fragt ein eigenes Titelfeld über den Texteditor zum Text oben ab (Titel können allerdings auch im Editorfeld eingegeben werden)', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_sidebar_titleabove'],
		  'parent'  => 'sidebaropt'
              ), 
	       'advanced_page_sidebar_titlebelow'	  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Feld Titel unten', 'fau' ),
                  'label'   => __( 'Fragt ein eigenes Titelfeld über den Texteditor zum Text unten ab (Titel können allerdings auch im Editorfeld eingegeben werden)', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_sidebar_titlebelow'],
		  'parent'  => 'sidebaropt'
              ), 
	       
	        'advanced_page_sidebar_useeditor_textabove'		  => array(
                  'type'    => 'bool',
                  'title'   => __( 'WYSIWYG-Editor Text oben', 'fau' ),
                  'label'   => __( 'Erlaubt die Nutzung des WYSWYG-Editors für die Eingabe von Text in der Sidebar. Dies schließt auch HTML-Tags mit Bildern und Links ein. Andernfalls ist nur ein Text mit Absätzen möglich.', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_sidebar_useeditor_textabove'],
		  'parent'  => 'sidebaropt'
              ), 
	    'advanced_page_sidebar_useeditor_textbelow'		  => array(
                  'type'    => 'bool',
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
                  'type'    => 'number',
                  'title'   => __( 'Links im ersten Linkblock', 'fau' ),
                  'label'   => __( 'Wieviele Links können maximal im ersten Linkblock angegeben werden.', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_sidebar_linkblock1_number'],
		  'parent'  => 'sidebaropt'
		), 
	         'advanced_page_sidebar_linkblock2_number'	  => array(
                  'type'    => 'number',
                  'title'   => __( 'Links im zweiten Linkblock', 'fau' ),
                  'label'   => __( 'Wieviele Links können maximal im zweiten Linkblock angegeben werden.', 'fau' ),                
                  'default' => $defaultoptions['advanced_page_sidebar_linkblock2_number'],
		  'parent'  => 'sidebaropt'
		), 
	       

		'kommentare'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Kommentare', 'fau' ),                      
		),
	       'advanced_activate_post_comments'		  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Kommentarfunktion aktivieren', 'fau' ),
                  'label'   => __( 'Schaltet die Kommentarfunktion für Beiträge ein.', 'fau' ),                
                  'default' => $defaultoptions['advanced_activate_post_comments'],
		  'parent'  => 'kommentare'
		), 
	        'advanced_comments_notes_before'	  => array(
                  'type'    => 'text',
                  'title'   => __( 'Hinweistext Eingabeformular', 'fau' ),
                  'label'   => __( 'Informationen über den Eingabefeldern für neue Kommentare.', 'fau' ),                
                  'default' => $defaultoptions['advanced_comments_notes_before'],
		  'parent'  => 'kommentare'
		), 
	        'advanced_comments_disclaimer'	  => array(
                  'type'    => 'text',
                  'title'   => __( 'Kommentar-Disclaimer', 'fau' ),
                  'label'   => __( 'Hinweistext zur Abgrenzung zum Inhalt der Kommentare.', 'fau' ),                
                  'default' => $defaultoptions['advanced_comments_disclaimer'],
		  'parent'  => 'kommentare'
		), 
	       	'dimensions'  => array(
                  'type'    => 'section',
                  'title'   => __( 'Image Dimensions', 'fau' ),
		   'user_level'	=> 3,
		),   
	       
               'default_gallery_full_width' => array(
                  'type'    => 'number',
                  'title'   => __( 'Galleriebilder (Groß) - Breite', 'fau' ),
                  'label'   => __( 'Breite in Pixel für große Galleriebilder.', 'fau' ),
                  'default' => $defaultoptions['default_gallery_full_width'],
                   'parent'  => 'dimensions',
                ), 
                'default_gallery_full_height' => array(
                  'type'    => 'number',
                  'title'   => __( 'Galleriebilder (Groß) - Höhe', 'fau' ),
                  'label'   => __( 'Höhe in Pixel für große Galleriebilder.', 'fau' ),
                  'default' => $defaultoptions['default_gallery_full_height'],
                   'parent'  => 'dimensions',
                ),              
                'default_gallery_full_crop'  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Bilder zuschneiden', 'fau' ),
                  'label'   => __( 'Sollen die großen Galleriebilder zugeschnitten werden um in die Dimensionen zu passen?', 'fau' ),
                  'default' => $defaultoptions['default_gallery_full_crop'],
		  'parent' => 'dimensions',
                ),

		'default_gallery_thumb_width' => array(
                  'type'    => 'number',
                  'title'   => __( 'Galleriebilder  - Breite', 'fau' ),
                  'label'   => __( 'Breite in Pixel für Galleriebilder.', 'fau' ),
                  'default' => $defaultoptions['default_gallery_thumb_width'],
                   'parent'  => 'dimensions',
                ), 
                'default_gallery_thumb_height' => array(
                  'type'    => 'number',
                  'title'   => __( 'Galleriebilder - Höhe', 'fau' ),
                  'label'   => __( 'Höhe in Pixel für Galleriebilder.', 'fau' ),
                  'default' => $defaultoptions['default_gallery_thumb_height'],
                   'parent'  => 'dimensions',
                ),              
                'default_gallery_thumb_crop'  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Bilder zuschneiden', 'fau' ),
                  'label'   => __( 'Sollen die Galleriebilder zugeschnitten werden um in die Dimensionen zu passen?', 'fau' ),
                  'default' => $defaultoptions['default_gallery_thumb_crop'],
		  'parent' => 'dimensions',
                ),
	       
		'default_gallery_grid_width' => array(
                  'type'    => 'number',
                  'title'   => __( 'Galleriebilder (Grid)  - Breite', 'fau' ),
                  'label'   => __( 'Breite in Pixel für Galleriebilder (Grid).', 'fau' ),
                  'default' => $defaultoptions['default_gallery_grid_width'],
                   'parent'  => 'dimensions',
                ), 
                'default_gallery_grid_height' => array(
                  'type'    => 'number',
                  'title'   => __( 'Galleriebilder (Grid) - Höhe', 'fau' ),
                  'label'   => __( 'Höhe in Pixel für Galleriebilder (Grid).', 'fau' ),
                  'default' => $defaultoptions['default_gallery_grid_height'],
                   'parent'  => 'dimensions',
                ),              
                'default_gallery_grid_crop'  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Bilder zuschneiden', 'fau' ),
                  'label'   => __( 'Sollen die Galleriebilder (Grid) zugeschnitten werden um in die Dimensionen zu passen?', 'fau' ),
                  'default' => $defaultoptions['default_gallery_grid_crop'],
		  'parent' => 'dimensions',
                ),
	       
		'default_gallery_grid2col_width' => array(
                  'type'    => 'number',
                  'title'   => __( 'Galleriebilder (2 Spalten)  - Breite', 'fau' ),
                  'label'   => __( 'Breite in Pixel für Galleriebilder (2 Spalten).', 'fau' ),
                  'default' => $defaultoptions['default_gallery_grid2col_width'],
                   'parent'  => 'dimensions',
                ), 
                'default_gallery_grid2col_height' => array(
                  'type'    => 'number',
                  'title'   => __( 'Galleriebilder (2 Spalten) - Höhe', 'fau' ),
                  'label'   => __( 'Höhe in Pixel für Galleriebilder (2 Spalten).', 'fau' ),
                  'default' => $defaultoptions['default_gallery_grid2col_height'],
                   'parent'  => 'dimensions',
                ),              
                'default_gallery_grid2col_crop'  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Bilder zuschneiden', 'fau' ),
                  'label'   => __( 'Sollen die Galleriebilder (2 Spalten) zugeschnitten werden um in die Dimensionen zu passen?', 'fau' ),
                  'default' => $defaultoptions['default_gallery_grid2col_crop'],
		  'parent' => 'dimensions',
                ),
	       	       
	       
		'default_gallery_grid4col_width' => array(
                  'type'    => 'number',
                  'title'   => __( 'Galleriebilder (4 Spalten)  - Breite', 'fau' ),
                  'label'   => __( 'Breite in Pixel für Galleriebilder (4 Spalten).', 'fau' ),
                  'default' => $defaultoptions['default_gallery_grid4col_width'],
                   'parent'  => 'dimensions',
                ), 
                'default_gallery_grid4col_height' => array(
                  'type'    => 'number',
                  'title'   => __( 'Galleriebilder (4 Spalten) - Höhe', 'fau' ),
                  'label'   => __( 'Höhe in Pixel für Galleriebilder (4 Spalten).', 'fau' ),
                  'default' => $defaultoptions['default_gallery_grid4col_height'],
                   'parent'  => 'dimensions',
                ),              
                'default_gallery_grid4col_crop'  => array(
                  'type'    => 'bool',
                  'title'   => __( 'Bilder zuschneiden', 'fau' ),
                  'label'   => __( 'Sollen die Galleriebilder (4 Spalten) zugeschnitten werden um in die Dimensionen zu passen?', 'fau' ),
                  'default' => $defaultoptions['default_gallery_grid4col_crop'],
		  'parent' => 'dimensions',
                ),	       
  
	       'reset_options' => array(
                  'type'    => 'bool',
                  'title'   => __( 'Reset', 'fau' ),
                  'label'   => __( 'Setze alle Einstellungen und Konfigurationen zurück. Achtung: Dies setzt alle Voreinstellungen unwiederbringlich zurück!', 'fau' ),
                  'default' => 0,
		  'mark_option' => 1,
              ),   	       

	       
   
          )
       ),
       'switchusrlvl'    => array(
	   'tabtitle'   => __('Anzeige der Optionen', 'fau'),
	   'fields' => array(
	       

		'admin_user_level' => array(
		    'type' => 'select',
		    'title'   => __( 'Anzeigemodus', 'fau' ),
		    'label'   => __( 'Mit dieser Einstellung können Sie den erweiterten Anzeigemodus oder den Expertenmodus für die Theme Einstellungen ändern.<br>'
			    . 'Durch den Wechsel von der Normal-Ansicht auf die erweiterte Anzeige oder den Expertenmodus erhalten Sie mehr Optionen angezeigt. '
			    . 'Viele der Optionen, insbesondere die des Expertenmodus, sollten nur dann geändert werden, wenn man genau weiß man dabei tut. '
			    . 'Für den Alltagseinsatz eines Theme ist es nicht notwendig auf den erweiterten Anzeigemodus oder den Expertenmodus zu wechseln. '
			    . '<br><b>Bitte verwenden Sie diese Optionen daher nur mit großer Vorsicht und Bedacht!</b> <br>', 'fau' ),
		    'liste'   => array(
				    0 => __('Normal','fau'), 
				    1 => __('Erweitert','fau'),  
				    2 => __('Expertenmodus','fau') ),
		    'default' => 0,
		    
		    ),
	    ),
       ),
       
    )
);
	       