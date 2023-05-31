<?php

/**
 * Template Part for pages with big hero slider
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */

	global $usejslibs;
	global $defaultoptions;
	$i= 0;

	$numberposts = get_theme_mod('start_header_count');
	$catid =  get_theme_mod('slider-catid');
	$usejslibs['heroslider'] = true;

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
		
	
	
	if ($hero_posts && (count($hero_posts) > 0)) { ?>
<div id="hero" class="sliderpage">
	<section id="hero-slides" role="region" aria-roledescription="carousel"  aria-label="<?php echo __('Bedeutende Artikel','fau'); ?>">
	   <div class="slick-slider featured-slider cf" id="mainslider">

	       <?php
		foreach($hero_posts as $hero): 
		    echo '<div class="item" aria-roledescription="slide" role="group" aria-labelledby="label-'.$hero->ID.'">';

		    $sliderimage = $copyright = $slidersrc = $slidersrcset = $slidersrcsizes = '';

		    $imageid = get_post_meta( $hero->ID, 'fauval_slider_image', true );
		    if (isset($imageid) && ($imageid>0)) {
                // Bestfall: Es gibt ein eigenes Bannerbild für den Artikel
                $sliderimage = wp_get_attachment_image_src($imageid, 'hero'); 
                $imgdata = fau_get_image_attributs($imageid);
                $copyright = trim(strip_tags( $imgdata['credits'] ));
                $slidersrcset =  wp_get_attachment_image_srcset($imageid,'hero');
                $slidersrcsizes = wp_get_attachment_image_sizes($imageid,'hero' );
		    } else {
			$post_thumbnail_id = get_post_thumbnail_id( $hero->ID ); 
			if ($post_thumbnail_id) {
			    // Es wird das Artikelbild verwendet, auch wenn es vielleicht nicht
			    // das Format des Banners hat
			    $sliderimage = wp_get_attachment_image_src( $post_thumbnail_id, 'hero' );
			    $imgdata = fau_get_image_attributs($post_thumbnail_id);
			    $copyright = trim(strip_tags( $imgdata['credits'] ));
			    $slidersrcset =  wp_get_attachment_image_srcset($post_thumbnail_id,'hero');
			    $slidersrcsizes = wp_get_attachment_image_sizes($post_thumbnail_id,'hero' );
			} else {
			    $fallbackid = get_theme_mod("fallback-slider-image");			
			    if (isset($fallbackid) && ($fallbackid > 0)) {
				// Es gibt weder Bannerbild noch Artikelbild.
				// Wir nehmen das Fallbackbild aus dem Customizer
				$sliderimage = wp_get_attachment_image_src( $fallbackid, 'hero' );
				if ($sliderimage !== false) {
				    $slidersrcset =  wp_get_attachment_image_srcset($fallbackid,'hero');
				    $slidersrcsizes = wp_get_attachment_image_sizes($fallbackid,'hero' );
				    $imgdata = fau_get_image_attributs($fallbackid);
				    if (preg_match("/^cropped\-/",$imgdata['title'])) {
                        $copyright = get_theme_mod("fallback-slider-image-title");			
				    } else {
                        $copyright = trim(strip_tags( $imgdata['credits'] ));
				    }
				} else {
				    $sliderimage = array($defaultoptions['src-fallback-slider-image'],$defaultoptions['default_image_sizes']['hero']['width'],$$defaultoptions['default_image_sizes']['hero']['height']);  
				}
				
			    } else {
                    // Kein Fallbackbild definiert, also hardcodiertes Fallback des Themes
                    $sliderimage = array($defaultoptions['src-fallback-slider-image'],$defaultoptions['default_image_sizes']['hero']['width'],$defaultoptions['default_image_sizes']['hero']['height']);  
			    }	
			}
		    }


		    $slidersrc = ' 	<img src="'.fau_esc_url($sliderimage[0]).'"'; 
		    // In case of SVG-Images, width and height are empty
		    if ($sliderimage[1] > 1) {
                $slidersrc .= ' width="'.$sliderimage[1].'"';
		    }
		    if ($sliderimage[2] > 1) {
                $slidersrc .= ' height="'.$sliderimage[2].'"';
		    }
		    $slidersrc .= ' alt=""';
			
		    if ($slidersrcset) {
                $slidersrc .= ' srcset="'.$slidersrcset.'"';
                if ($slidersrcsizes) {
                     $slidersrc .= ' sizes="'.$slidersrcsizes.'"';
                }
		    }
		    $slidersrc .= ' role="presentation" loading="lazy">';
		    echo $slidersrc."\n"; 


		    if ((get_theme_mod('advanced_display_hero_credits')==true) && (!empty($copyright))) {
                echo '<p class="credits">'.$copyright."</p>";
		    }
		    ?>
			
		    <div class="hero-slide-text">
                <div class="hero-container">
                    <div class="hero-row">
                        <div class="slider-titel">
                            <?php
                            echo '<header id="label-'.$hero->ID.'"><a href="';

                            $link = get_post_meta( $hero->ID, 'external_link', true );
                            $external = 0;
                            if (isset($link) && (filter_var($link, FILTER_VALIDATE_URL))) {
                                $external = 1;
                            } else {
                                $link = fau_esc_url(get_permalink($hero->ID));
                            }
                            echo $link;
                            echo '">'.get_the_title($hero->ID).'</a></header>'."\n";					
                            ?>
                        </div>
                    </div>
                    <?php
                    $maxlen = get_theme_mod("default_slider_excerpt_length");
                    if ($maxlen > 0) { ?>
                    <div class="hero-row">
                        <div class="slider-text"><?php 
                        $abstract = get_post_meta( $hero->ID, 'abstract', true );			   
                        if (strlen(trim($abstract))<3) {
                           $abstract =  fau_custom_excerpt($hero->ID,$maxlen,false,'',true);
                        } ?>
                        <p><?php //echo $abstract; ?></p>
                        </div>
                    </div>  <?php } ?>		   
                </div>
		    </div>
		</div>
	       
		<?php endforeach; 
		wp_reset_query();
		?>
	    </div>
	    <div class="slider-controls" aria-controls="mainslider" >
            <?php 
           if (('' != get_theme_mod( 'slider-autoplay' )) && (true== get_theme_mod( 'slider-autoplay' )) ) {
			$startstopclass= '';
			$buttontext = $defaultoptions['slider-stoptext'];
			$buttonicon = 'fa-pause'; // use the pause icon if the slider is autoplaying
		} else {
			$startstopclass= ' stopped';
			$buttontext = $defaultoptions['slider-starttext'];
			$buttonicon = 'fa-play'; // use the play icon if the slider is not autoplaying
		}
		
		
		?>
          <button type="button" aria-label="<?php echo $buttontext ?> " class="slick-startstop<?php echo $startstopclass;?>"><i class="fa <?php echo $buttonicon; ?>" aria-hidden="true"></i></button>
	    </div>
	    
	</section> 
	</div>
    <?php 
	} else {
        get_template_part('template-parts/hero', 'banner'); 
	
    }  
   
