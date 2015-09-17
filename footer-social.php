<?php

/* 
 * Social Media Footer
 */

global $options;

$template = get_page_template();
$displayon = $options['active_socialmedia_footer'];

$show =false;
/*
				1 => __('Startseite','fau'),
      				2 => __('Portalseiten','fau'),
      				3 => __('Suche und Fehlerseiten','fau'),
      				4 => __('Inhaltsseite mit Navi','fau'),
      				5 => __('Standard Seiten','fau'),
      				6 => __('Beiträge','fau'),       

*/

 foreach ($displayon as $key) {
    if (($key==1) && (is_page_template( 'page-templates/page-start.php' ))) {
	$show = true;
	break;
    } elseif (($key==2) && (is_page_template( 'page-templates/page-portal.php')))  {
	$show = true;
	break;
    } elseif (($key==3) && (is_search() || is_404() ))  {
	$show = true;
	break;
    } elseif (($key==4) && (is_page_template( 'page-templates/page-subnav.php')))  {
	$show = true;
	break;
    } elseif (($key==5) && (is_page()))  {
	$show = true;
	break;
    } elseif (($key==6) && (is_single()))  {	 
	$show = true;
	break;
    } else {
//	echo "<!-- PAGE TEMPLATE: $template -->";
    }
 }

if ($show) {
?>
		<div id="social">
			<div class="container">
				<div class="row">
					<?php if (isset($options['socialmedia'])): ?>
						<div class="span3">
							<?php 
							if (!empty($options['socialmedia_buttons_title'])) {
							    echo '<h2 class="small">'.$options['socialmedia_buttons_title'].'</h2>';
							}
						
							global $default_socialmedia_liste;

							echo '<nav id="socialmedia" aria-label="'.__('Social Media','fau').'">';
							echo '<ul class="social">';       
							    foreach ( $default_socialmedia_liste as $entry => $listdata ) {        

								$value = '';
								$active = 0;
								if (isset($options['sm-list'][$entry]['content'])) {
									$value = $options['sm-list'][$entry]['content'];
									if (isset($options['sm-list'][$entry]['active'])) {
									    $active = $options['sm-list'][$entry]['active'];
									} 
								} else {
									$value = $default_socialmedia_liste[$entry]['content'];
									$active = $default_socialmedia_liste[$entry]['active'];
								 }

								if (($active ==1) && ($value)) {
								    echo '<li class="social-'.$entry.'"><a href="'.$value.'">';
								    echo $listdata['name'].'</a></li>';
								}
							    }
							    echo '</ul>';
							    echo '</nav>';
							?>

						</div>
					<?php endif; ?>
					<div class="span9">
						<div class="row">
						<?php 
						    if ( is_active_sidebar( 'startpage-socialmediainfo' ) ) { 
							dynamic_sidebar( 'startpage-socialmediainfo' ); 
						    }  ?>
						</div>
						<?php if ($options['start_link_videoportal_socialmedia']) { ?>
						<div class="pull-right link-all-videos">
						    <a href="http://video.fau.de/"><?php echo $options['start_title_videoportal_socialmedia']; ?></a>
						</div>
						<?php } ?>
					</div>						
				</div>
			</div>
		</div> <!-- /social -->	
<?php
}
