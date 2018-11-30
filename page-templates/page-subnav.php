<?php
/**
 * Template Name: Inhaltsseite mit Navi
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php get_template_part('template-parts/hero', 'small'); ?>

	<div id="content">
	    <div class="container">			
	    <?php echo fau_get_ad('werbebanner_seitlich',false); ?>
		<div class="row">	
		    <?php echo fau_get_page_subnav($post->ID); ?>		
		    <div class="entry-content">
			<main<?php echo fau_get_page_langcode($post->ID);?>>
			    <h1 class="screen-reader-text"><?php the_title(); ?></h1>
			    <?php 
			    $headline = get_post_meta( $post->ID, 'headline', true );									
			    if (!fau_empty($headline)) {
				echo '<h2 class="subtitle">'.$headline.'</h2>'; 					    
			    }

			    ?>
			    <div class="inline-box">					   	
				<?php get_template_part('template-parts/sidebar', 'inline'); ?> 
				<div class="content-inline">
				<?php the_content(); ?>
				</div>
			    </div>
			    <?php echo wp_link_pages($pagebreakargs); ?>
			</main>    
		      <?php  
			echo fau_get_ad('werbebanner_unten',true); 		
			$logoliste = get_post_meta( $post->ID, 'fauval_imagelink_catid', true );			
			if ($logoliste) { 
			    $logos = fau_get_imagelinks($logoliste, false);
			    if ((isset($logos) && (!empty($logos)))) {
				echo "<hr>\n";
				echo $logos;
			    }
			}	
			?>					    
		    </div>				
		</div>
	    </div>
	</div>
	
	
<?php endwhile; ?>
<?php get_template_part('template-parts/footer', 'social'); ?>	
<?php 
get_footer(); 