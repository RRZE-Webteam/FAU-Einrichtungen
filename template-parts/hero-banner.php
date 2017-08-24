<?php

/**
 * Template Part hero banner
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */

global $options;

?>

	    <section id="hero" class="hero-banner">
		<div class="banner">
		    <div class="introimg">
			<?php 
			$copyright = '';
			if (isset($options['startseite_banner_image_id']) && ($options['startseite_banner_image_id']>0)) {
			    $imagedata = wp_get_attachment_image_src( $options['startseite_banner_image_id'], 'herobanner' );

			    $slidersrcset =  wp_get_attachment_image_srcset($options['startseite_banner_image_id'],'herobanner');

			    if ($imagedata) {
				$image = '<img src="'.fau_esc_url($imagedata[0]).'" width="'.$imagedata[1].'" height="'.$imagedata[2].'" alt=""';
				if ($slidersrcset) {
				    $image .= 'srcset="'.$slidersrcset.'"';
				}
				$image .= '>';

			    }
			    $imgdata = fau_get_image_attributs($options['startseite_banner_image_id']);
			    $copyright = trim(strip_tags( $imgdata['credits'] ));
			} elseif ($options['startseite_banner_usedefault']) {
			    $image = '<img src="'.fau_esc_url($options['default_startseite-bannerbild-image_src']).'" width="'.$options['default_startseite-bannerbild-image_width'].'" height="'.$options['default_startseite-bannerbild-image_height'].'" alt="">';	
			} else {
			    $image = '';
			}
			echo $image."\n"; 
			if (($options['advanced_display_hero_credits']==true) && (!empty($copyright))) {
			    echo '<p class="credits">'.$copyright."</p>";
			} 
			?>
			 <div class="banner-text">
			    <div class="container">
				<div class="row">
					<div role="presentation" class="span9 infobar">				    
					    <?php 
					   $header_image = get_header_image();
					   $title = get_bloginfo( 'title' );
					    if ((!empty( $header_image ) && (!fau_empty($title)) )){	
						echo "<h1>". $title. "</h1>\n";
					    }
					    $desc = trim(strip_tags(get_bloginfo( 'description' )));
					    if (!empty($desc)) {
						if (!empty( $header_image ) ){	
						    echo "<br>";
						}
						 echo '<p class="description">'.$desc."</p>";
					    }
					    ?>
					</div>
				</div>
			    <?php if ($options['advanced_page_start_herojumplink']) { ?>
				<a href="#content" class="hero-jumplink-content"></a>
			    <?php } ?>
			    </div>
			 </div>    
		    </div>
		</div>
	    </section> <!-- /hero -->