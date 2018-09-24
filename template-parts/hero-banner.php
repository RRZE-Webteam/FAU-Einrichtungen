<?php

/**
 * Template Part hero banner
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */

$banner = get_theme_mod("startseite_banner_image");
$copyright = '';

$show_copyright  = get_theme_mod("advanced_display_hero_credits"); 
$use_bannerdefault = get_theme_mod("startseite_banner_usedefault"); 
$show_jumplink = get_theme_mod("advanced_page_start_herojumplink"); 

$startseite_banner_image_id = get_theme_mod("startseite_banner_image_id"); 
if (isset($banner) && ($banner > 0)) {
    $imagedata = wp_get_attachment_image_src( $banner, 'herobanner' );
    $slidersrcset =  wp_get_attachment_image_srcset($banner,'herobanner');

    if ($imagedata) {
	$image = '<img src="'.fau_esc_url($imagedata[0]).'" width="'.$imagedata[1].'" height="'.$imagedata[2].'" alt=""';
	if ($slidersrcset) {
	    $image .= 'srcset="'.$slidersrcset.'"';
	}
	$image .= '>';

    }
    $imgdata = fau_get_image_attributs($banner);
    $copyright = trim(strip_tags( $imgdata['credits'] ));
} elseif (isset($startseite_banner_image_id) && ($startseite_banner_image_id>0)) {
    /* Diese Bedingung dient der Abwärtscompatibilität; Früher haben wir die Option-Table statt theme_mods verwendet */
    $imagedata = wp_get_attachment_image_src( $startseite_banner_image_id, 'herobanner' );
    $slidersrcset =  wp_get_attachment_image_srcset($startseite_banner_image_id,'herobanner');

    if ($imagedata) {
	$image = '<img src="'.fau_esc_url($imagedata[0]).'" width="'.$imagedata[1].'" height="'.$imagedata[2].'" alt=""';
	if ($slidersrcset) {
	    $image .= 'srcset="'.$slidersrcset.'"';
	}
	$image .= '>';

    }
    $imgdata = fau_get_image_attributs($startseite_banner_image_id);
    $copyright = trim(strip_tags( $imgdata['credits'] ));
} elseif ($use_bannerdefault) {
    $url = get_theme_mod("default_startseite-bannerbild-image_src");
    $width = get_theme_mod('default_startseite-bannerbild-image_width');
    $height = get_theme_mod('default_startseite-bannerbild-image_height');
    $crop = get_theme_mod('default_startseite-bannerbild-image_crop');

    $image = '<img src="'.fau_esc_url($url).'" width="'.$width.'" height="'.$height.'" alt="">';	
} else {
    $image = '';
}

?>

    <div id="hero" class="hero-banner">
	<div class="banner" aria-hidden="true" role="presentation">
	    <div class="introimg">
		<?php 
		
		echo $image."\n"; 
		if (($show_copyright) && (!empty($copyright))) {
		    echo '<p class="credits">'.$copyright."</p>";
		} 
		?>
		 <div class="banner-text">
		    <div class="container">
			<div class="row">
                            
                            <?php  
                                $title = get_bloginfo( 'title' );
                                $header_image = get_header_image();
                                $infobarclass= "infobar";
                                $length = 0;
                               
                                if ((!empty( $header_image ) && (!fau_empty($title)) )){
                                   $length = strlen($title);
                                   if ($length > 50) {
                                       $infobarclass .= " fullsize";
                                   }
                                }
                            ?>
				<div class="<?php echo $infobarclass;?>">				    
				    <?php 
				    if ((!empty( $header_image ) && (!fau_empty($title)) )){	
					echo '<p class="sitetitle">'. $title. '</p>';
				    }
				    $desc = strip_tags(get_bloginfo( 'description' ));
				    if (!fau_empty($desc)) {
                                    
					echo '<div class="slogan"><p class="description';
                                        if ($length > 80) {
                                            echo " screen-reader-text";
                                        }
                                        echo '">'.$desc."</p></div>";
				    }
				    ?>
				</div>
			</div>
		    <?php if ($show_jumplink) { ?>
			<a href="#content" class="hero-jumplink-content"></a>
		    <?php } ?>
		    </div>
		 </div>    
	    </div>
	</div>
    </div>