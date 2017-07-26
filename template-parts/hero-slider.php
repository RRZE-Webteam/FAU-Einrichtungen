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
    <?php	
    global $usejslibs;
    global $options;

    $usejslibs['flexslider'] = true;

    if (isset($options['slider-catid']) && $options['slider-catid'] > 0) {
	$hero_posts = get_posts( array( 'cat' => $options['slider-catid'], 'posts_per_page' => $options['start_header_count']) );
    } else {							    
	$category = get_term_by('slug', $options['slider-category'], 'category');
	if($category) {
	    $query = array(
		'numberposts' => $options['start_header_count'],
		'tax_query' => array(
		array(
		    'taxonomy' => 'category',
		    'field' => 'id', // can be slug or id - a CPT-onomy term's ID is the same as its post ID
		    'terms' => $category->term_id
		    )
		)
	    );
	} else {
	    $query = array(
		'numberposts' => $options['start_header_count']
	    );                    
	}
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
			$slidersrc = '<img src="'.fau_esc_url($options['src-fallback-slider-image']).'" width="'.$options['slider-image-width'].'" height="'.$options['slider-image-height'].'" alt="">';			    
		    } else {
			$slidersrc = '<img src="'.fau_esc_url($sliderimage[0]).'" width="'.$options['slider-image-width'].'" height="'.$options['slider-image-height'].'" alt=""';
			if ($slidersrcset) {
			    $slidersrc .= ' srcset="'.$slidersrcset.'"';
			}
			$slidersrc .= '>';
		    }


		    echo $slidersrc."\n"; 
		    if (($options['advanced_display_hero_credits']==true) && (!empty($copyright))) {
			echo '<p class="credits">'.$copyright."</p>";
		    }
		    ?>
		    <div class="hero-slide-text">
			<div class="container">
			    <div class="row">
				<div class="slider-titel">
				    <?php
					echo '<h2><a href="';

					$link = get_post_meta( $hero->ID, 'external_link', true );
					$external = 0;
					if (isset($link) && (filter_var($link, FILTER_VALIDATE_URL))) {
					    $external = 1;
					} else {
					    $link = fau_esc_url(get_permalink($hero->ID));
					}
					echo $link;
					echo '">'.get_the_title($hero->ID).'</a></h2>'."\n";					
					?>
				    </div>
				</div>
				<div class="row">
				    <div class="slider-text">	    
			    <?php 
					$abstract = get_post_meta( $hero->ID, 'abstract', true );
					if (strlen(trim($abstract))<3) {
					   $abstract =  fau_custom_excerpt($hero->ID,$options['default_slider_excerpt_length'],false);
					} ?>
					<p><?php echo $abstract; ?></p>
				    </div>
				</div>	
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

</section> <!-- /hero -->