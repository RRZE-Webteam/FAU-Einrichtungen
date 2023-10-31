<?php
/**
 * The template for displaying all pages.
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
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
					
					<?php  
                    $logoliste = get_post_meta( $post->ID, 'fauval_imagelink_catid', true );		
                    if ($logoliste) { 
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
get_footer(); 
