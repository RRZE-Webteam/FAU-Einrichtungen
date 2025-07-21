<?php

/* 
 * Display Title of the webpage as replacement for a logo
 * 
 * FAU THeme, since V2.0:
 *   "Build" Logo together from FAU-Logo + Text
 */

global $defaultoptions;
global $default_fau_orga_faculty;

$faculty = '';
$website_usefaculty = $defaultoptions['website_usefaculty'];
if ( (isset($website_usefaculty)) && (in_array($website_usefaculty,$default_fau_orga_faculty))) {
    $faculty = $website_usefaculty;
    // 'website_usefaculty' = ( nat | phil | med | tf | rw )
}
$website_type = get_theme_mod('website_type');
    /* 
	 * website_type: 
	 *  0 = Fakultaetsportal oder zentrale Einrichtung
	 *	=> Fakultatslogo 
	 *  1 = Lehrstuhl oder eine andere Einrichtung die einer Fakultät zugeordnet ist 
         *	=> FAU Logo mit blauer FAU bezeichnung + Titel in Fakultatsfarbe 
	 *  2 = Zentrale Einrichtungen  
	 *	=> FAU Logo mit blauer FAU bezeichnung + Titel in blau 
	 *  3 = Koopertation mit Externen (neu ab 1.4)
	 *	=> Kein FAU Logo 
	 *  -1 = FAU-Portal
         *	=> FAU Logo mit Schrift 
	 */
  

//  Use Default FAU SVG and add HTML-Text beneath




// Ausschlusskriterien für falsche Wahloptionen
if ((empty($faculty)) && ($website_type==0)) {
    $website_type = 2;
}

$faulogo = true;
$visible_toptitle = 'Friedrich-Alexander-Universität';
$visible_toptitle_secondline = 'Erlangen-Nürnberg';
// only useable in FAU Portal
$visible_shortcut = get_theme_mod('website_shorttitle');
// if set

$visible_title = get_theme_mod('website_logotitle');
if (empty($visible_title)) {
    $visible_title = get_bloginfo( 'name','display' );
}

if ($defaultoptions['debugmode']) {
    $debug_website_fakultaet = get_theme_mod('debug_website_fakultaet');
    if (isset($debug_website_fakultaet)) {
	$faculty = $debug_website_fakultaet;
    }
}


if ($website_type == 0)  {
    // Fakultätsportal
    $visible_toptitle_secondline = '';
    
} elseif ($website_type == 1)  {
    // Einrichtung unterhalb einer Fakultät
    $visible_toptitle_secondline = '';

} elseif ($website_type == 2)  {
     // Einrichtungen, die der FAU zugeordnet sind und nicht einer Fakultät
  //  $faculty = '';
    $visible_toptitle_secondline = '';
    
} elseif ($website_type == -1)  {
    // FAU Portal
    $visible_shortcut = '';
    $visible_title = '';
    
} else {
    // Websites, die zu externen Kooperationen gehören, bei denen daher 
    // kein FAU Logo erscheinen soll.
    $visible_toptitle_secondline = '';
    $visible_toptitle = '';
    $faulogo = false;

}

if ( ! is_front_page() ) {
    echo '<a itemprop="url" rel="home" class="generated" href="'.fau_esc_url(home_url( '/' ) ).'">';
}
echo '<span class="textlogo">';
    
    if ($faulogo) {
	echo '<span class="baselogo">';	
	echo fau_use_svg("fau-logo-2021",153,58,'faubaselogo',false, ['hidden' => true, 'labelledby' => 'website-title','role' => 'img']); 
	echo '</span>';	

    } 
    echo '<span class="text">';
    if ($visible_toptitle) {
	echo '<span class="fau-title"';
	if ($visible_title) {
	    echo ' aria-hidden="true"';
	} else {
	    echo ' id="website-title"';
	}
	echo '>'.$visible_toptitle.'</span> ';
	if ($visible_toptitle_secondline) {
	    echo '<span class="fau-title-place"';
	    if ($visible_title) {
	       echo ' aria-hidden="true"';
	   }
	   echo '>'.$visible_toptitle_secondline.'</span> ';
       }
		
    }
   

    if ($visible_title) {
	echo '<span id="website-title" class="visible-title';
	if (!empty($faculty)) {
	    echo ' '.$faculty;
	}

	echo '" itemprop="name">'.$visible_title.'</span>';
	
	if ($visible_shortcut) {
	    echo ' <span class="website-shortcut';
	    if (!empty($faculty)) {
		echo ' '.$faculty;
	    }

	    echo '">'.$visible_shortcut.'</span>';
	}
	

    }
    echo '</span>';   
echo '</span>';
if ( ! is_front_page() ) {
    echo '</a>';
} 