<?php
/**
 * Template Name: Portalindex
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */
global $is_sidebar_active;
    get_header();

    while ( have_posts() ) : the_post();  ?>

    <div id="content" class="portal">
        <div class="content-container">	   
                <?php get_template_part('template-parts/content', 'portalmenu-oben'); ?>
            <div class="content-row">
                <main<?php echo fau_get_page_langcode($post->ID);?>>
                <h1 id="maintop" class="screen-reader-text"><?php the_title(); ?></h1>
                <?php 
                $headline = get_post_meta( $post->ID, 'headline', true );				
                if (!fau_empty($headline)) {
                      echo '<p class="subtitle">'.$headline."</p>\n";  
                } ?>
                <div class="inline-box">			    
                    <?php get_template_part('template-parts/sidebar', 'inline');   ?>
                    <div class="content-inline<?php if ($is_sidebar_active) { echo " with-sidebar"; }?>">               
                        <?php the_content(); ?>
                    </div>
                </div>    
                <?php 
                    get_template_part('template-parts/content', 'portalmenu-unten');
                    get_template_part('template-parts/content', 'imagelink');  
                ?>
                </main>
            </div>
        </div>
    </div>
<?php endwhile; 
get_footer();
