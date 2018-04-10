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
    $i= 0;

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
    ?>
    <div class="bs-example">
       <div id="myCarousel" class="carousel slide carousel-fade" data-ride="carousel">
            <div class="carousel-inner"><?php
    foreach($hero_posts as $hero): ?>
	<!--<div class="hero-slide">-->
		 <?php if($i == 0) { ?>
                <div class="item active">
                <?php $i++;} else { ?>
                        <div class="item">  
                <?php } ?>
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
      ?></div></div></div>
    ?>
                
    <!--<div class="bs-example">
       <div id="myCarousel" class="carousel slide" data-ride="carousel">
           <!-- Carousel indicators -->
           <!--<ol class="carousel-indicators">
               <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
               <li data-target="#myCarousel" data-slide-to="1"></li>
               <li data-target="#myCarousel" data-slide-to="2"></li>
           </ol>   
           <!-- Wrapper for carousel items -->
           <!--<div class="carousel-inner">
               <div class="item active">
                   <img src="http://lorempixel.com/1260/350/abstract/3" alt="First Slide">
                    <div class="carousel-caption">
                        <h3>Flowers1</h3>
                        <p>Beautiful flowers in Kolymbari, Crete.</p>
                    </div>
               </div>
               <div class="item">
                   <img src="http://lorempixel.com/1260/350/abstract/3" alt="Second Slide">
                     <div class="carousel-caption">
                        <h3>Flowers2</h3>
                        <p>Beautiful flowers in Kolymbari, Crete.</p>
                    </div>
               </div>
               <div class="item">
                   <img src="http://lorempixel.com/1260/350/abstract/3" alt="Third Slide">
                     <div class="carousel-caption">
                        <h3>Flowers3</h3>
                        <p>Beautiful flowers in Kolymbari, Crete.</p>
                    </div>
               </div>
           </div>-->
           <!--Carousel controls -->
            <button class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                <i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i>
            </button>
            <button class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                <i class="fa fa-arrow-circle-right fa-3x" aria-hidden="true"></i>
            </button>
               <div id="carouselButtons">
                <button id="playButton" type="button" class="btn btn-default">
                    <i class="fa fa-play" aria-hidden="true"></i>
                 </button>
                <button id="pauseButton" type="button" class="btn btn-default">
                    <i class="fa fa-pause" aria-hidden="true"></i>
                </button>
            </div>
       <!-- </div>
    </div> -->      
                
                
    <script type="text/javascript">
        //jQuery(document).ready(function($) { $('#hero-slides').flexslider({selector: '.hero-slide',directionNav: true,pausePlay: true}); });
        jQuery(document).ready(function($) {
        $('#myCarousel').carousel({
            interval:2000,
            pause: "false"
        });
        $('#playButton').click(function () {
            $('#myCarousel').carousel('cycle');
        });
        $("#pauseButton").click(function () {
            $(".carousel").carousel("pause");
        });
    });
    </script>

    <style>
        
        i.fa.fa-play, i.fa.fa-pause {
    color: rgba(0, 51, 102, 1);
    opacity:1;
}
button#playButton, button#pauseButton {
          opacity: 0.70;
    border-radius: 0;
    background-color: #fff;
}

button#playButton:hover, button#pauseButton:hover {
       opacity: 1;
    border-radius: 0;
    background: #fff;
}
.
        
   .carousel-inner>.item>img, .carousel-inner>.item>a>img {
    display: block;
    max-width: none;
    height: auto;
    line-height: 1;
}
        
    #hero-slides .hero-slide-text {
        top: initial;

    }
  #carouselButtons {
    right: 15%;
    top: 86%;
    position: absolute;
    bottom: 0px;
    z-index: 100;
}
    
    .carousel-control.left, .carousel-control.right {
  border:none;
    background-image: none;
    z-index: 100;}
    
    /*
inspired from https://codepen.io/Rowno/pen/Afykb 
*/
.carousel-fade .carousel-inner .item {
  opacity: 0;
  transition-property: opacity;
}

.carousel-fade .carousel-inner .active {
  opacity: 1;
}

.carousel-fade .carousel-inner .active.left,
.carousel-fade .carousel-inner .active.right {
  left: 0;
  opacity: 0;
  z-index: 1;
}

.carousel-fade .carousel-inner .next.left,
.carousel-fade .carousel-inner .prev.right {
  opacity: 1;
}

.carousel-fade .carousel-control {
  z-index: 2;
}

/*
WHAT IS NEW IN 3.3: "Added transforms to improve carousel performance in modern browsers."
now override the 3.3 new styles for modern browsers & apply opacity
*/
@media all and (transform-3d), (-webkit-transform-3d) {
    .carousel-fade .carousel-inner > .item.next,
    .carousel-fade .carousel-inner > .item.active.right {
      opacity: 0;
      -webkit-transform: translate3d(0, 0, 0);
              transform: translate3d(0, 0, 0);
    }
    .carousel-fade .carousel-inner > .item.prev,
    .carousel-fade .carousel-inner > .item.active.left {
      opacity: 0;
      -webkit-transform: translate3d(0, 0, 0);
              transform: translate3d(0, 0, 0);
    }
    .carousel-fade .carousel-inner > .item.next.left,
    .carousel-fade .carousel-inner > .item.prev.right,
    .carousel-fade .carousel-inner > .item.active {
      opacity: 1;
      -webkit-transform: translate3d(0, 0, 0);
              transform: translate3d(0, 0, 0);
    }
}

    </style>
          
            
</div>

    <?php get_template_part('template-parts/hero', 'jumplinks'); ?>	
</section>