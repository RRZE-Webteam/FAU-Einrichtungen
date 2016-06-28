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
global $options;
?>

	</div> <!-- /wrap -->

	<footer id="footer">
		<div class="container">
			<div class="row">
				<div class="span3">
					<p><img src="<?php echo get_fau_template_uri(); ?>/img/logo-fau-inverse.png" alt="Friedrich-Alexander-Universität Erlangen-Nürnberg"></p>
				</div>
				<div class="span4">
					
				<p itemscope itemtype="http://schema.org/PostalAddress">
				    <span itemprop="name"><?php echo $options['contact_address_name']; 
				    if (isset($options['contact_address_name2'])) { echo "<br>".$options['contact_address_name2']; } ?></span><br>
				    <span itemprop="streetAddress"><?php echo $options['contact_address_street']; ?></span><br>
				    <span itemprop="postalCode"><?php echo $options['contact_address_plz']; ?></span> <span itemprop="addressLocality"><?php echo $options['contact_address_ort']; ?></span><br>
				    <?php if (isset($options['contact_address_country'])) { ?>
				       <span itemprop="addressCountry"><?php echo $options['contact_address_country']; ?></span>
				    <?php } ?>   
			       </p>
	
				</div>
				<div class="span5">
					<?php 
					if ( has_nav_menu( 'meta-footer' ) ) {
					    wp_nav_menu( array( 'theme_location' => 'meta-footer', 'container' => false, 'items_wrap' => '<ul id="footer-nav" class="%2$s">%3$s</ul>' ) ); 
					} else {
					    echo fau_get_defaultlinks('techmenu', 'menu', 'footer-nav');
					}
					?>
				</div>
			</div>
		</div>
	</footer>
	
	<a href="#wrap" class="top-link"><span class="hidden">Nach oben</span></a>

	<?php wp_footer(); ?>
</body>
</html>