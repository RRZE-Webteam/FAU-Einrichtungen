<?php

/* 
 * Data Defaults for FAU
 */


/*
 * Social Media 
 */
$default_socialmedia_liste = array(
    'delicious' => array(
	'name' => 'Delicious',
	'content'  => '',
	'active' => 0,
    ),
    'diaspora' => array(
	'name' => 'Diaspora',
	'content'  => '',
	'active' => 0,
    ),
    'facebook' => array(
	'name' => 'Facebook',
	'content'  => 'https://de-de.facebook.com/Uni.Erlangen.Nuernberg',
	'active' => 1,
    ),
    'twitter' => array(
	'name' => 'Twitter',
	'content'  => 'https://twitter.com/UniFAU',
	'active' => 1,
    ),
    
    'flickr' => array(
	'name' => 'Flickr',
	'content'  => '',
	'active' => 0,
    ),
  
    'itunes' => array(
	'name' => 'iTunes',
	'content'  => '',
	'active' => 0,
    ),
    'skype' => array(
	'name' => 'Skype',
	'content'  => '',
	'active' => 0,
    ),
    
    'youtube' => array(
	'name' => 'YouTube',
	'content'  => '',
	'active' => 0,
    ),
    'xing' => array(
	'name' => 'Xing',
	'content'  => 'https://www.xing.com/net/alumnifau',
	'active' => 1,
    ),
    'tumblr' => array(
	'name' => 'Tumblr',
	'content'  => '',
	'active' => 0,
    ),
    'github' => array(
	'name' => 'GitHub',
	'content'  => '',
	'active' => 0,
    ),
   
    'feed' => array(
	'name' => 'RSS Feed',
	'content'  => get_bloginfo( 'rss2_url' ),
	'active' => 1,
    ),
   
    'pinterest' => array(
	'name' => 'Pinterest',
	'content'  => 'http://www.pinterest.com/unifau/',
	'active' => 0,
    ),
    'instagram' => array(
	'name' => 'Instagram',
	'content'  => 'https://instagram.com/uni_fau/',
	'active' => 0,
    ),
     'wikipedia' => array(
	'name' => 'Wikipedia',
	'content'  => 'https://de.wikipedia.org/wiki/Friedrich-Alexander-Universit%C3%A4t_Erlangen-N%C3%BCrnberg',
	'active' => 0,
    ),
); 


/* 
 * Default Link List for Submenus , can be overwritten bei Menu  
 */
$default_link_liste = array( 


	'meta' => array(
	    'link1'  => array(
		'name'	    => __('Campo', 'fau' ),
		'content'  => 'https://campo.fau.de/',
	    ),
	    'link2'  => array(
		'name'	    => __('UnivIS', 'fau' ),
		'content'  => 'https://univis.fau.de/',
	    ),
	    'link3'  => array(
		'name'	    => __('Stellenangebote', 'fau' ),
		'content'  => 'https://www.jobs.fau.de/',
	    ),
	    'link4'  => array(
		'name'	    => __('Lageplan', 'fau' ),
		'content'  => 'https://karte.fau.de/',
	    ),
	    'link5'  => array(
		'name'	    => __('Hilfe im Notfall', 'fau' ),
		'content'  => 'https://www.fau.de/notfall/',
	    ),
	   
	),
	'techmenu' => array(    
	    'link1'  => array(
		'name'	    => __('Impressum', 'fau' ),
		'content'  => __('/impressum/', 'fau' ),
	    ),
	    'link2'  => array(
		'name'	    => __('Datenschutz', 'fau' ),
		'content'  => __('/datenschutz/', 'fau' ),
	    ),
	    'link3'  => array(
		'name'	    => __('Barrierefreiheit', 'fau' ),
		'content'  => __('/barrierefreiheit/', 'fau' ),
	    ),	
	  
	),
);


$default_fau_orga_data = array(
   'fau' => array(
	    'title'		=> __('Friedrich-Alexander-Universität Erlangen-Nürnberg (FAU)', 'fau'),
	    'shorttitle'	=> __('FAU', 'fau'),
	    'homeurl'		=> 'https://www.fau.de',
	    'homeurl_en'	=> 'https://www.fau.eu',
	    'favion'

    ),
  
    '_faculty'	=> array(
	'med' => array(
		'title'	    => __('Medizinische Fakultät', 'fau'),
		'shorttitle'    => __('Med', 'fau'),
		'homeurl'	    => 'https://med.fau.de',
		'homeurl_en'	    => 'https://med.fau.eu',

	),
	'nat' => array(
		'title'	    => __('Naturwissenschaftliche Fakultät', 'fau'),
		'shorttitle'    => __('Nat', 'fau'),
		'homeurl'	    => 'https://nat.fau.de',
		'homeurl_en'	    => 'https://nat.fau.eu',
	),
	'phil' => array(
		'title'	    => __('Philosophische Fakultät', 'fau'),
		'shorttitle'    => __('Phil', 'fau'),
		'homeurl'	    => 'https://phil.fau.de',
		'homeurl_en'	    => 'https://phil.fau.eu',
	),
	'rw' => array(
		'title'	    => __('Rechts- und Wirtschaftswissenschaftliche Fakultät', 'fau'),
		'shorttitle'    => __('RW', 'fau'),
		'homeurl'	    => 'https://rw.fau.de',
		'homeurl_en'	    => 'https://rw.fau.eu',
	),
	'tf' => array(
		'title'	    => __('Technische Fakultät', 'fau'),
		'shorttitle'    => __('TF', 'fau'),
		'homeurl'	    => 'https://tf.fau.de',
		'homeurl_en'	    => 'http://tf.fau.eu',
	)
    ),
    
    'fb-wiso' => array(
	    'title'	    => __('Fachbereich Wirtschaftswissenschaften', 'fau'),
	    'shorttitle'    => __('FB WiSo', 'fau'),
	    'homeurl'	    => 'https://wiso.rw.fau.de',
	    'homeurl_en'    => 'https://wiso.rw.fau.eu',
    ),
    'fb-jura' => array(
	    'title'	    => __('Fachbereich Rechtswissenschaften', 'fau'),
	    'shorttitle'    => __('FB WiSo', 'fau'),
	    'homeurl'	    => 'https://jura.rw.fau.de',
    ),
    
   
    
);


$default_fau_orga_faculty = array(
    'med', 'nat', 'phil', 'rw', 'tf'
);


$pagebreakargs = array(
    
    'before'   => '<nav class="pagination pagebreaks" aria-label="'.__( 'Seitenüberblick', 'fau' ).'"><p>' . __( 'Seite:', 'fau' ).' <span class="subpages">',
		'after'            => '</span></p></nav>',
		'link_before'      => '<span class="number">',
		'link_after'       => '</span>',
		'next_or_number'   => 'number',
		'separator'        => ' ',
		'nextpagelink'     => __( 'Nächste Seite', 'fau' ),
		'previouspagelink' => __( 'Vorherige Seite', 'fau' ),
		'pagelink'         => '%',
    'echo' => 0);


$default_fau_page_langcodes = array(
	"de" => __('Deutsch','fau'),
	"en" => __('Englisch','fau'),
	"es" => __('Spanisch','fau'),
	"fr" => __('Französisch','fau'),
	"zh" => __('Chinesisch','fau'),
	"ru" => __('Russisch','fau'),
    );

$default_fau_page_menuuebenen = array(
    "1"	=> __('Eine Ebene','fau'),
    "2"	=> __('Zwei Ebenen','fau'),
    "3"	=> __('Drei Ebenen','fau'),
);


$default_fau_svgsymbols = array(
    "arrow-down"	=> '<svg><symbol id="bi-arrow-down" viewBox="0 0 16 16"><title>'.__('Pfeil nach unten','fau').'</title><path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"/></symbol></svg>',
    "arrow-left"	=> '<svg><symbol id="bi-arrow-left" viewBox="0 0 16 16"><title>'.__('Pfeil nach links','fau').'</title><path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/></symbol></svg>',
    "arrow-right"	=> '<svg><symbol id="bi-arrow-right" viewBox="0 0 16 16"><title>'.__('Pfeil nach rechts','fau').'</title><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/></symbol></svg>',
    "arrow-up"		=> '<svg><symbol id="bi-arrow-up" viewBox="0 0 16 16"><title>'.__('Pfeil nach oben','fau').'</title><path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/></symbol></svg>',
    "caret-down-fill"	=> '<svg><symbol id="bi-caret-down-fill" viewBox="0 0 16 16"><title>'.__('Pfeil nach unten','fau').'</title><path d="M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></symbol></svg>',
    "caret-right-fill"	=> '<svg><symbol id="bi-caret-right-fill" viewBox="0 0 16 16"><title>'.__('Pfeil nach rechts','fau').'</title><path d="M12.14 8.753l-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/></symbol></svg>',
    "caret-left-fill"	=> '<svg><symbol id="bi-caret-left-fill" viewBox="0 0 16 16"><title>'.__('Pfeil nach links','fau').'</title><path d="M3.86 8.753l5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/></symbol></svg>',
    "caret-up-fill"	=> '<svg><symbol id="bi-caret-up-fill" viewBox="0 0 16 16"><title>'.__('Pfeil nach oben','fau').'</title><path d="M7.247 4.86l-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/></symbol></svg>',
);

