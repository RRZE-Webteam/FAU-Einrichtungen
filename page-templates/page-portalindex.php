<?php
/**
 * Template Name: Portalindex
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header(); 
while ( have_posts() ) : the_post(); 

    get_template_part('template-parts/hero', 'small'); ?>

    <div id="content">
	<div class="content-container">	   
            <?php get_template_part('template-parts/content', 'portalmenu-oben'); ?>
	    <div class="content-row">
		    <main<?php echo fau_get_page_langcode($post->ID);?>>
			<h1 id="maintop" class="screen-reader-text"><?php the_title(); ?></h1>
		    <?php 
			$headline = get_post_meta( $post->ID, 'headline', true );				
			if (!fau_empty($headline)) {
			      echo '<h2 class="subtitle">'.$headline."</h2>\n";  
			} ?>
			<div class="inline-box">			    
			    <?php get_template_part('template-parts/sidebar', 'inline');  
			    if ($is_sidebar_active) {
				echo '<div class="content-inline with-sidebar">';
			    } else {
				echo '<div class="content-inline">';
			    }
			    the_content(); 
			    echo '</div>';
			    ?>
			</div>    
			<?php 
			get_template_part('template-parts/content', 'portalmenu-unten');
			   
			$logoliste = get_post_meta( $post->ID, 'fauval_imagelink_catid', true );			
			if ($logoliste) {
			    /* New since 1.10.57 */
			    $logosize = get_post_meta( $post->ID, 'fauval_imagelink_size', true );
			    $size = $logosize != '' ? esc_attr($logosize) : "logo-thumb";
			    $logos = fau_imagelink_get(array('size' => $size, 'catid' => $logoliste));
			    if ((isset($logos) && (!empty($logos)))) {
				echo $logos;
			    }
			   
			}
			?>
		    </main>
	    </div>

	</div>
	
    </div>
<?php endwhile; 
get_template_part('template-parts/footer', 'social');
get_footer();
