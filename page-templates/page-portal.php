<?php
/**
 * Template Name: Portalseite 
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header(); 
$herotype = get_theme_mod('advanced_header_template');
if (empty($herotype)) { 
    $herotype = 'default';
}
while ( have_posts() ) : the_post(); ?>
    <div id="content" class="portal herotype-<?php echo $herotype;?>">
        <div class="content-container">	  
            <?php get_template_part('template-parts/content', 'portalmenu-oben'); ?>
            <div class="content-row">
                <div class="portalpage-content">
                    <main<?php echo fau_get_page_langcode($post->ID);?>>
                    <h1 id="maintop" class="screen-reader-text"><?php the_title(); ?></h1>
                    <?php 


                    $headline = get_post_meta( $post->ID, 'headline', true );				
                    if (!fau_empty($headline)) {
                         echo '<p class="subtitle">'.$headline."</p>\n";  
                    }
                    the_content(); 

                    get_template_part('template-parts/content', 'portalmenu-unten');
                    get_template_part('template-parts/content', 'imagelink');  	
                    
                    ?>
                    </main> 
                </div>
                <?php get_template_part('template-parts/sidebar', 'portal');?>
            </div>
        </div>
    </div>
<?php endwhile; 
get_footer();
