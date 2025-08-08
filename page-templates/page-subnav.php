<?php
/**
 * Template Name: Seite mit Navigation
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */
global $is_sidebar_active;
 if (get_theme_mod('advanced_page_sidebar_display_in_pagesetting') == false) {
     $is_sidebar_active = false;
 }
get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<div id="content" class="subnav">
	    <div class="content-container">			
            <div class="content-row">	
                <?php echo fau_get_page_subnav($post->ID); ?>		
                <div class="entry-content">
                <main<?php echo fau_get_page_langcode($post->ID);?>>
                    <h1 id="maintop" class="screen-reader-text"><?php the_title(); ?></h1>
                    <?php 
                    $headline = get_post_meta( $post->ID, 'headline', true );									
                    if (!fau_empty($headline)) {
                        echo '<p class="subtitle">'.$headline.'</p>'; 					    
                    } ?>
                    <div class="inline-box">					   	
                        <?php get_template_part('template-parts/sidebar', 'inline'); ?>
                        <div class="content-inline<?php if ($is_sidebar_active) { echo " with-sidebar"; }?>"> 
                   
                        <?php 
                        the_content(); 
                        echo wp_link_pages($pagebreakargs);	
                        ?>
                        </div>
                    </div>
                </main>    
                  <?php get_template_part('template-parts/content', 'imagelink');  	?>					    
                </div>				
            </div>
	    </div>
	</div>
	
	
<?php endwhile; 
get_footer(); 