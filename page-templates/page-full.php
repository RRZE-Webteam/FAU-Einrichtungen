<?php
/**
 * Template Name: Seite ohne Navigation
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 2.5
 */

global $pagebreakargs;
global $defaultoptions;
global $is_sidebar_active;

get_header(); 

$content_width =$defaultoptions['content-width-fullpage'];

while ( have_posts() ) : 
	the_post(); 	
	?>
	<div id="content">
		<div class="content-container">
		    <div class="content-row">
			    <main<?php echo fau_get_page_langcode($post->ID);?>>
				<h1 id="maintop" class="screen-reader-text"><?php the_title(); ?></h1>
					<?php 
					$headline = get_post_meta( $post->ID, 'headline', true );				
					if (!fau_empty($headline)) {
					      echo '<p class="subtitle">'.$headline."</p>\n";
					} ?>
					
					<div class="inline-box">			    
					    <?php get_template_part('template-parts/sidebar', 'inline');  
					    if ($is_sidebar_active) {
                            echo '<div class="content-inline with-sidebar">';
					    } else {
                            echo '<div class="content-inline">';
					    }
                        the_content();  
					    
					    echo wp_link_pages($pagebreakargs);  					    
					    echo '</div>';
					    ?>
					</div>    
					
                    <?php get_template_part('template-parts/content', 'imagelink');  ?>	
			    </main>
		    </div>
		</div>
	</div>
	
<?php endwhile;
get_footer(); 
