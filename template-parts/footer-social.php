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
    	    <h1 class="screen-reader-text"><?php _e("Weitere Hinweise zum Webauftritt","fau"); ?></h1>
	<div id="social">
		<div class="container">
			<div class="row">
				<?php 
				if ((($showicons==true) && ($showsocialsidebar==false)) 
				    || (($showicons==false) && ($showsocialsidebar==true)) )  { ?>
				    <div class="col-xs-12">
				<?php     
				} else { 
				?>
				    <div class="col-xs-12 col-sm-3">					
				<?php 
				}
				if ($showicons==true) {
				    echo '<nav class="socialmedia" aria-label="'.__('Social Media','fau').'">';
				    
				    $socialmedia_buttons_title = get_theme_mod('socialmedia_buttons_title');
				    if (!fau_empty($socialmedia_buttons_title)) {
					echo '<h2 class="small">'.$socialmedia_buttons_title.'</h2>';
				    }

				    global $default_socialmedia_liste;

				    
				    echo '<div itemscope itemtype="http://schema.org/Organization">';
				    echo fau_create_schema_publisher(false);		
				    echo fau_get_socialmedia_menu($defaultoptions['socialmedia_menu_name'],'social',true);
				    echo '</div>';
				    echo '</nav>';


				    if ($showsocialsidebar==true) {
					 echo '</div>'; // span3, da beide activ bereiche activ
					 echo '<div class="col-xs-12 col-sm-9">';
				    }
				}
				if ($showsocialsidebar==true) { ?>
					<aside class="row">
					<?php 
					    if ( is_active_sidebar( 'startpage-socialmediainfo' ) ) { 
						dynamic_sidebar( 'startpage-socialmediainfo' ); 
					    }  ?>
					</aside>
					<?php 
					$showlink_videoportal = get_theme_mod("start_link_videoportal_socialmedia");
					$urlvideoportal  = esc_url(get_theme_mod("start_title_videoportal_url"));
					$linktitlevideportal = esc_attr(get_theme_mod("start_title_videoportal_socialmedia"));

					if ($showlink_videoportal) { ?>
					<div class="pull-right link-all-videos">
					    <a href="<?php echo $urlvideoportal; ?>"><?php echo $linktitlevideportal; ?></a>
					</div>
					<?php } ?>
					    
				<?php } ?>

				</div>						
			</div>
		</div>
	</div> <!-- /social -->	
<?php
}
