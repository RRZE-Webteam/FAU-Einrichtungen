<?php

/**
 * Template Part for pages with big hero slider
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */

?>

<section id="hero">
    <div id="hero-slides">
	<h2 class="screen-reader-text"><?php echo __('Slider','fau'); ?></h2>
    <?php	
    global $usejslibs;
    global $defaultoptions;

    $numberposts = get_theme_mod('start_header_count');
    $catid =  get_theme_mod('slider-catid');
    $usejslibs['flexslider'] = true;

    if (isset($catid) && $catid > 0) {
	$hero_posts = get_posts( array( 
	    'cat' => $catid, 
	    'posts_per_page' => $numberposts) 
	);
    } else {							    
	$query = array(
	    'numberposts' => $numberposts
	);                   
	$hero_posts = get_posts($query); 
    }
    foreach($hero_posts as $hero): ?>
	<div class="hero-slide">
		<?php 

		$sliderimage = '';
		$copyright = '';
	       // $imageid = get_post_meta( $hero->ID, 'fauval_sliderid', true );
		$imageid = get_post_meta( $hero->ID, 'fauval_slider_image', true );
		$slidersrc = '';
		$slidersrcset = '';

		if (isset($imageid) && ($imageid>0)) {
		    $sliderimage = wp_get_attachment_image_src($imageid, 'hero'); 
		    $imgdata = fau_get_image_attributs($imageid);
		    $copyright = trim(strip_tags( $imgdata['credits'] ));
		    $slidersrcset =  wp_get_attachment_image_srcset($imageid,'hero');

		} else {
		    $post_thumbnail_id = get_post_thumbnail_id( $hero->ID ); 
		    if ($post_thumbnail_id) {
			$sliderimage = wp_get_attachment_image_src( $post_thumbnail_id, 'hero' );
			$imgdata = fau_get_image_attributs($post_thumbnail_id);
			$copyright = trim(strip_tags( $imgdata['credits'] ));
			$slidersrcset =  wp_get_attachment_image_srcset($post_thumbnail_id,'hero');
		    }
		}

		if (!$sliderimage || empty($sliderimage[0])) {  
		    $fallbackid = get_theme_mod("fallback-slider-image");
		    if (isset($fallbackid) && ($fallbackid > 0)) {
			$imagedata = wp_get_attachment_image_src( $fallbackid, 'hero' );
			$slidersrcset =  wp_get_attachment_image_srcset($fallbackid,'hero');

			if ($imagedata) {
			    $slidersrc = '<img src="'.fau_esc_url($imagedata[0]).'" width="'.$imagedata[1].'" height="'.$imagedata[2].'" alt=""';
			    if ($slidersrcset) {
				$slidersrc .= 'srcset="'.$slidersrcset.'"';
			    }
			    $slidersrc .= '>';

			}
			$imgdata = fau_get_image_attributs($fallbackid);
			$copyright = trim(strip_tags( $imgdata['credits'] ));
		    
		    } else {
			$slidersrc = '<img src="'.fau_esc_url($defaultoptions['src-fallback-slider-image']).'" width="'.$defaultoptions['slider-image-width'].'" height="'.$defaultoptions['slider-image-height'].'" alt="">';		
		    }		    
		} else {
		    $slidersrc = '<img src="'.fau_esc_url($sliderimage[0]).'" width="'.$defaultoptions['slider-image-width'].'" height="'.$defaultoptions['slider-image-height'].'" alt=""';
		    if ($slidersrcset) {
			$slidersrc .= ' srcset="'.$slidersrcset.'"';
		    }
		    $slidersrc .= '>';
		}


		echo $slidersrc."\n"; 


		if ((get_theme_mod('advanced_display_hero_credits')==true) && (!empty($copyright))) {
		    echo '<p class="credits">'.$copyright."</p>";
		}
		?>
		<div class="hero-slide-text">
		    <div class="container">
			<div class="row">
			    <div class="slider-titel">
				<?php
				    echo '<h3><a href="';

				    $link = get_post_meta( $hero->ID, 'external_link', true );
				    $external = 0;
				    if (isset($link) && (filter_var($link, FILTER_VALIDATE_URL))) {
					$external = 1;
				    } else {
					$link = fau_esc_url(get_permalink($hero->ID));
				    }
				    echo $link;
				    echo '">'.get_the_title($hero->ID).'</a></h3>'."\n";					
				    ?>
				</div>
			    </div>
			<?php
			    $maxlen = get_theme_mod("default_slider_excerpt_length");
			    if ($maxlen > 0) { ?>
			    <div class="row">
				<div class="slider-text"><?php 
				    $abstract = get_post_meta( $hero->ID, 'abstract', true );			   
				    if (strlen(trim($abstract))<3) {
				       $abstract =  fau_custom_excerpt($hero->ID,$maxlen,false);
				    } ?>
				    <p><?php echo $abstract; ?></p>
				</div>
			    </div>  <?php } ?>		   
		    </div>
		</div>
	    </div>
    <?php endforeach; 
      wp_reset_query();
      ?>
	    <script type="text/javascript">
		jQuery(document).ready(function($) { $('#hero-slides').flexslider({selector: '.hero-slide',directionNav: true,pausePlay: true}); });
	    </script>
	    </div>
    <?php get_template_part('template-parts/hero', 'jumplinks'); ?>	
</section>