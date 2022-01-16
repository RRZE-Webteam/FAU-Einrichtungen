<?php
/**
 * Template Name: Portalseite 
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

    <?php get_template_part('template-parts/hero', 'small'); ?>

    <div id="content">
	<div class="content-container">	  
            <?php get_template_part('template-parts/content', 'portalmenu-oben'); ?>
	    <div class="content-row">
		<div class="portalpage-content">
		     <main<?php echo fau_get_page_langcode($post->ID);?>>
			<h1 class="screen-reader-text"><?php the_title(); ?></h1>
		    <?php 
		   
		    
			$headline = get_post_meta( $post->ID, 'headline', true );				
			if (!fau_empty($headline)) {
			     echo '<h2 class="subtitle">'.$headline."</h2>\n";  
			}
			the_content(); 
                        
                        get_template_part('template-parts/content', 'portalmenu-unten');
                        	
			    $logoliste = get_post_meta( $post->ID, 'fauval_imagelink_catid', true );			
			    if ($logoliste) { 
				$logos = fau_imagelink_get(array('size' => "logo-thumb", 'catid' => $logoliste, "autoplay" => true, "dots" => true));
				if ((isset($logos) && (!empty($logos)))) {
				    echo $logos;
				}	   
			    }

			?>
		    </main> 
		</div>
		<?php get_template_part('template-parts/sidebar', 'portal');?>
		

	    </div>
		
	    
	</div>
	
	
    </div>
<?php endwhile; ?>
<?php get_template_part('template-parts/footer', 'social'); ?>	
<?php 
get_footer();
