<?php
/**
 * The template for displaying all pages.
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

global $pagebreakargs;

get_header(); 


while ( have_posts() ) : 
	the_post(); 
	get_template_part('template-parts/hero', 'small');
	?>
	<div id="content">
		<div class="container">
		    <?php 
		        echo fau_get_ad('werbebanner_seitlich',false);
		    ?>
		    <div class="row">
			<div class="col-xs-12">
			    <main<?php echo fau_get_page_langcode($post->ID);?>>
					<?php 
					$headline = get_post_meta( $post->ID, 'headline', true );				
					if (!fau_empty($headline)) {
					      echo '<h2 class="subtitle">'.$headline."</h2>\n";
					} ?>
					
					<div class="inline-box">					 
					    <?php get_template_part('template-parts/sidebar', 'inline'); ?>
					    <div class="content-inline">
					    <?php the_content(); ?>					
					    </div>
					</div>    
					<?php echo wp_link_pages($pagebreakargs); ?>    
			    </main>
			</div>		
		    </div>
		</div>
	    	<?php get_template_part('template-parts/footer', 'social'); ?>	

	</div>
	
<?php endwhile;

get_footer(); 
