<?php

/* 
 * Social Media Footer
 */

global $defaultoptions;

$template = get_page_template();
$displayon = get_theme_mod("active_socialmedia_footer"); 
if (!isset($displayon)) {
    $displayon = $defaultoptions['active_socialmedia_footer'];
}
$show =false;


 foreach ($displayon as $key) {
    if (($key==1) && (is_page_template( 'page-templates/page-start.php' ))) {
	// Startseite Fakultaet / Zentrale
	$show = true;
	break;
    } elseif (($key==1) && (is_page_template( 'page-templates/page-start-sub.php' ))) {
	// Startseite Department / Lehrstuhl
	$show = true;
	break;
    } elseif (($key==2) && (is_page_template( 'page-templates/page-portal.php')))  {
	// Portalseite
	$show = true;
	break;
    } elseif (($key==3) && (is_search() || is_404() ))  {
	// Fehlerseiten
	$show = true;
	break;
    } elseif (($key==4) && (is_page_template( 'page-templates/page-subnav.php')))  {
	// Seiten mit Navigation links
	$show = true;
	break;
    } elseif (($key==5) && (is_page()))  {
	// Seiten
	$show = true;
	break;
    } elseif (($key==6) && (is_single()))  {	
	// Beitraege
	$show = true;
	break;
    } elseif ($key==-1) {
	// Alle Seiten
	$show = true;
	break;
    }
 }

 $showicons = false;
 $showsocialsidebar = false;

 $showicons = get_theme_mod("socialmedia");
 

 if ( is_active_sidebar( 'startpage-socialmediainfo' ) ) { 
     $showsocialsidebar = true;
 }
 
 if (($showicons==false) && ($showsocialsidebar==false)) {
     $show = false;
 }
 
if ($show) {
?>    
	<aside id="social" aria-labelledby="socialbartitle">
	    <div class="container">
		<div class="flex-four-widgets">
		    <h2 id="socialbartitle" class="screen-reader-text"><?php _e("Weitere Hinweise zum Webauftritt","fau"); ?></h2>
		    <?php 
		  
		    if ($showicons==true) {
			$socialmedia_buttons_title = get_theme_mod('socialmedia_buttons_title');
			
			if (!fau_empty($socialmedia_buttons_title)) {
			    echo '<nav class="socialmedia" aria-labelledby="socialmediatitle">';
			    echo '<h3 id="socialmediatitle">'.$socialmedia_buttons_title.'</h3>';
			} else {
			    echo '<nav class="socialmedia" aria-label="'.__('Social Media','fau').'">';
			}

			global $default_socialmedia_liste;


			echo '<div itemscope itemtype="http://schema.org/Organization">';
			echo fau_create_schema_publisher(false);		
			echo fau_get_socialmedia_menu($defaultoptions['socialmedia_menu_name'],'social',true);
			echo '</div>';
			echo '</nav>';
		    }
		    if (($showsocialsidebar==true) && is_active_sidebar( 'startpage-socialmediainfo' ) ) { 
			dynamic_sidebar( 'startpage-socialmediainfo' ); 
		    }  ?>

		</div>
	    </div>
	</aside> 
<?php
}
