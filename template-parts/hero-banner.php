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
$startseite_banner_image_id = get_theme_mod("startseite_banner_image_id"); 

if (isset($banner) && ($banner > 0)) {

    $image = fau_get_image_htmlcode($banner, 'herobanner', '');
    
    $imgdata = fau_get_image_attributs($banner);
    $copyright = trim(strip_tags( $imgdata['credits'] ));
} elseif (isset($startseite_banner_image_id) && ($startseite_banner_image_id>0)) {
    /* Diese Bedingung dient der Abwärtscompatibilität; Früher haben wir die Option-Table statt theme_mods verwendet */
    
    $image = fau_get_image_htmlcode($startseite_banner_image_id, 'herobanner', '');
    $imgdata = fau_get_image_attributs($startseite_banner_image_id);
    $copyright = trim(strip_tags( $imgdata['credits'] ));
} elseif ($use_bannerdefault) {
    $url = get_theme_mod("default_startseite-bannerbild-image_src");
    $width = $defaultoptions['default_image_sizes']['herobanner']['width']; 
    $height = $defaultoptions['default_image_sizes']['herobanner']['height'];
    $crop = $defaultoptions['default_image_sizes']['herobanner']['crop'];

    $image = '<img src="'.fau_esc_url($url).'" width="'.$width.'" height="'.$height.'" alt="" loading="lazy">';	
} else {
    $image = '';
}

if ((filter_var($copyright, FILTER_VALIDATE_URL)) && (preg_match('/\/cropped\-/',$copyright))) {
    // if Image is cropped image, then copyright text contains the url of the cropped image. 
    // this is not a copyright text we want
    $copyright = '';
}
?>

    <section id="hero" class="hero-banner">
	<div class="banner" aria-hidden="true" role="presentation">
	    <div class="introimg">
		<?php 
		
		echo $image."\n"; 
		if (($show_copyright) && (!empty($copyright))) {
		    echo '<p class="credits">'.$copyright."</p>";
		} 
		?>
		 <div class="banner-text">
		    <div class="hero-container">
			<div class="hero-row">
                            
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
				    if (!fau_empty($title)) {	
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
		    </div>
		 </div>    
	    </div>
	</div>
    </section>