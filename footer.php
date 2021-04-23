<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */
?>


	<footer id="footer">
	    <div class="container">
		<div class="footer-row">
		    <?php 
		    $website_type = get_theme_mod('website_type');
		    ?>
		    <div class="footer-logo">
			<?php if (($website_type ==3 ) &&  ( is_active_sidebar( 'footer-block1' ) )) {  
			    dynamic_sidebar( 'footer-block1' );
			} else { 
                            fau_use_svg("fau-logo-text",216,42,'fau-logo-footer'); 
                        } ?>
		    </div>
		    <div class="footer-address">
			<?php 
			if (($website_type ==3 ) &&  ( is_active_sidebar( 'footer-block2' ) )) {  
			     dynamic_sidebar( 'footer-block2' );
			} else {
			    $display_address = get_theme_mod("advanced_footer_display_address");
			    if ($display_address) { ?>
			<address itemscope itemtype="http://schema.org/PostalAddress">
			    <?php
			    $contact_address_name = get_theme_mod("contact_address_name");
			    $contact_address_name2 = get_theme_mod("contact_address_name2");
			    $contact_address_street = get_theme_mod("contact_address_street");
			    $contact_address_plz = get_theme_mod("contact_address_plz");
			    $contact_address_ort = get_theme_mod("contact_address_ort");
			    $contact_address_country = get_theme_mod("contact_address_country");
			    $schemaname = $contact_address_name." ".$contact_address_name2; ?>
			    <meta itemprop="name" content="<?php echo esc_html($schemaname);?>">
			    <span><?php echo $contact_address_name; 
			    if (!fau_empty($contact_address_name2)) { echo "<br>".$contact_address_name2; } ?></span><br>
			    <span itemprop="streetAddress"><?php echo $contact_address_street; ?></span><br>
			    <span itemprop="postalCode"><?php echo $contact_address_plz; ?></span> <span itemprop="addressLocality"><?php echo $contact_address_ort; ?></span><br>
			    <?php if (isset($contact_address_country)) { ?>
			       <span itemprop="addressCountry"><?php echo $contact_address_country; ?></span>
			    <?php } ?>   
		       </address>
			<?php } 
			} ?>   
		    </div>
		    <div class="footer-meta">
			<nav aria-label="<?php echo __('Kontakt, Impressum und Zusatzinformationen','fau'); ?>">
			    <?php 
			    if ( has_nav_menu( 'meta-footer' ) ) {
				wp_nav_menu( array( 'theme_location' => 'meta-footer', 'container' => false, 'items_wrap' => '<ul id="footer-nav" class="%2$s">%3$s</ul>' ) ); 
			    } else {
				echo fau_get_defaultlinks('techmenu', 'menu', 'footer-nav');
			    }
			    ?>
			</nav>
			<?php 
			$display_socialmedia_footer = get_theme_mod("advanced_footer_display_socialmedia");
			if ($display_socialmedia_footer) {
				    global $default_socialmedia_liste;
				    global $defaultoptions;
				    
				    echo '<nav class="svg-socialmedia round hoverbg" aria-label="'.__('Social Media','fau').'">';
				    echo '<div itemscope itemtype="http://schema.org/Organization">';
				    echo fau_create_schema_publisher(false);		
				    echo fau_get_socialmedia_menu($defaultoptions['socialmedia_menu_name'],'',true);
				    echo '</div>';
				    echo '</nav>';

			} ?>
		    </div>
		</div>
	    </div>
	    <a href="#pagewrapper" class="top-link">
		<?php fau_use_svg("chevron-up-solid",38,38); ?>		
		<span class="screen-reader-text"><?php echo __('Nach oben','fau'); ?></span></a>
	</footer>
    </div> 
	<?php wp_footer(); ?>
</body>
</html>