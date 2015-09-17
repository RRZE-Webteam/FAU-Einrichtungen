<?php
/**
 * The template for displaying all pages.
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php get_template_part('hero', 'small'); ?>

	<div id="content">
		<div class="container">
		    	 <?php 
			    echo fau_get_ad('werbebanner_seitlich',false);
			?>
			<div class="row">
				
				<div class="span12">
				    <main>
					<?php 
					$headline = get_post_meta( $post->ID, 'headline', true );				
					if ( $headline) {
					     echo "<h2>".$headline."</h2>\n";
					}
					
					
					 
					get_template_part('sidebar', 'inline'); 
					the_content(); ?>
				    </main>
				</div>
				
			</div>
		</div>
	    	<?php get_template_part('footer', 'social'); ?>	

	</div>
	
<?php endwhile; ?>

<?php 
get_footer(); 
