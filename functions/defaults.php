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
    'gplus' => array(
	'name' => 'Google Plus',
	'content'  => '',
	'active' => 0,
    ),
    'flattr' => array(
	'name' => 'Flattr',
	'content'  => '',
	'active' => 0,
    ),
    'flickr' => array(
	'name' => 'Flickr',
	'content'  => '',
	'active' => 0,
    ),
  
    'identica' => array(
	'name' => 'Identica',
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
    'appnet' => array(
	'name' => 'App.Net',
	'content'  => '',
	'active' => 0,
    ),
    'feed' => array(
	'name' => 'RSS Feed',
	'content'  => get_bloginfo( 'rss2_url' ),
	'active' => 1,
    ),
    'friendica' => array(
	'name' => 'Friendica',
	'content'  => '',
	'active' => 0,
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
); 


/* 
 * Default Link List for Submenus , can be overwritten bei Menu  
 */
$default_link_liste = array( 
	
	'centers' => array(    
	    '_title'	=> __('Einrichtungen','fau'),
	    'link1'  => array(
		'name'	    => __('Universitätsbibliothek', 'fau' ),
		'content'  => 'http://www.ub.fau.de/',
	    ),
	    'link2'  => array(
		'name'	    => __('Rechenzentrum', 'fau' ),
		'content'  => 'https://www.rrze.fau.de/',
		'class'	    => 'rwfak',
	    ),
	    'link3'  => array(
		'name'	    => __('Sprachenzentrum', 'fau' ),
		'content'  => 'http://www.sz.uni-erlangen.de/',
		'class'	    => 'medfak',
	    ),
	    'link4'  => array(
		'name'	    => __('Graduiertenschule', 'fau' ),
		'content'  => 'http://www.promotion.fau.de/',
		'class'	    => 'natfak',
	    ),
	  
	),
	
	'infos' => array(    
	    '_title'	=> __('Informationen für','fau'),
	  
	    'link1'  => array(
		'name'	    => __('Studierende', 'fau' ),
		'content'  => 'https://www.fau.de/studium/',
	    ),
	  
	    'link2'  => array(
		'name'	    => __('Schülerinnen und Schüler', 'fau' ),
		'content'  => 'https://www.fau.de/schulportal-der-fau/',
	    ),	
	    'link3'  => array(
		'name'	    => __('Alumni', 'fau' ),
		'content'  => 'https://www.fau.de/alumni/',
	    ),	
	    'link4'  => array(
		'name'	    => __('Unternehmen', 'fau' ),
		'content'  => 'https://www.fau.de/patente-gruendung-wissenstransfer/service-fuer-unternehmen/',
	    ),	
	    	
	 
	),
	'meta' => array(
	    'link1'  => array(
		'name'	    => __('Mein Campus', 'fau' ),
		'content'  => 'https://campus.fau.de/',
	    ),
	    'link2'  => array(
		'name'	    => __('UnivIS', 'fau' ),
		'content'  => 'http://univis.fau.de/',
	    ),
	    'link3'  => array(
		'name'	    => __('Anfahrt und Lageplan', 'fau' ),
		'content'  => 'http://karte.fau.de/',
	    ),
	   
	),
	'techmenu' => array(    
	    'link1'  => array(
		'name'	    => __('Stellenangebote', 'fau' ),
		'content'  => 'https://www.fau.de/universitaet/stellen-praktika-und-jobs/',
	    ),
	    'link2'  => array(
		'name'	    => __('Presse', 'fau' ),
		'content'  => 'https://www.fau.de/presseportal-der-fau/',
	    ),
	    'link3'  => array(
		'name'	    => __('Intranet', 'fau' ),
		'content'  => 'https://www.fau.de/intranet/',
	    ),	
	    'link4'  => array(
		'name'	    => __('Impressum', 'fau' ),
		'content'  => 'https://www.fau.de/impressum/',
	    ),	
	),
);


$default_fau_orga_data = array(
   'fau' => array(
	    'title'	    => __('Friedrich-Alexander-Universität Erlangen-Nürnberg (FAU)', 'fau'),
	    'shorttitle'    => __('FAU', 'fau'),
	    'homeurl'	    => 'https://www.fau.de',
	    'url'           => '%s/img/logo-fau.png',
	    'home_imgsrc'   => get_fau_template_uri().'/img/logo-fau-37x16.png',
	    'thumbnail'	=> '%s/img/logos/logo-fau.png',
    ),
  
    '_faculty'	=> array(
	'med' => array(
		'title'	    => __('Medizinische Fakultät', 'fau'),
		'shorttitle'    => __('Med', 'fau'),
		'homeurl'	    => 'https://med.fau.de',
		'url'           => '%s/img/logos/logo-med.png',
		'thumbnail'	=> '%s/img/logos/logo-med.png',
	),
	'nat' => array(
		'title'	    => __('Naturwissenschaftliche Fakultät', 'fau'),
		'shorttitle'    => __('Nat', 'fau'),
		'homeurl'	    => 'https://nat.fau.de',	
		'url'           => '%s/img/logos/logo-nat.png',
		'thumbnail'	=> '%s/img/logos/logo-nat.png',
	),
	'phil' => array(
		'title'	    => __('Philosophische Fakultät', 'fau'),
		'shorttitle'    => __('Phil', 'fau'),
		'homeurl'	    => 'https://phil.fau.de',
		'url'           => '%s/img/logos/logo-phil.png',
		'thumbnail'	=> '%s/img/logos/logo-phil.png',
	),
	'rw' => array(
		'title'	    => __('Rechts- und Wirtschaftswissenschaftliche Fakultät', 'fau'),
		'shorttitle'    => __('RW', 'fau'),
		'homeurl'	    => 'https://rw.fau.de',
		'url'           => '%s/img/logos/logo-rw.png',
		 'thumbnail'	=> '%s/img/logos/logo-rw.png',
	),
	'tf' => array(
		'title'	    => __('Technische Fakultät', 'fau'),
		'shorttitle'    => __('TF', 'fau'),
		'homeurl'	    => 'https://tf.fau.de',
		'url'           => '%s/img/logos/logo-tf.png',
		'thumbnail'	=> '%s/img/logos/logo-tf.png',
	)
    ),
    '_center'	=> array(
	'rrze' => array(
		'title'	    => __('Regionales Rechenzentrum Erlangen (RRZE)', 'fau'),
		'shorttitle'    => __('RRZE', 'fau'),
		'homeurl'	    => 'https://rrze.fau.de',
		'url'           => '%s/img/logos/logo-rrze.png',
		'thumbnail'	=> '%s/img/logos/logo-rrze.png',
	),

    )
    
);


$default_fau_orga_faculty = array(
    'med', 'nat', 'phil', 'rw', 'tf'
);

