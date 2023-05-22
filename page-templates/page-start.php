<?php
/**
 * Template Name: Startseite Fakultät
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header();

?>

	<div id="content" class="start">
		<div class="content-container">	   
                    <?php get_template_part('template-parts/content', 'portalmenu-oben'); ?>
		    <div class="content-row">
			<div class="portalpage-content">    
				    <main<?php echo fau_get_page_langcode($post->ID);?>>
                        <h1 id="maintop" class="screen-reader-text"><?php the_title(); ?></h1>

                        <?php

                        if (get_theme_mod('advanced_template_page_start_display_content')==true) {
                            the_content();
                        }

                        $number = 0;
                        $displayedposts = array();
                        $max = get_theme_mod('start_max_newspertag');
                        $maxall = get_theme_mod('start_max_newscontent');
                        if ($maxall > 0) {
                           
                    
                            $maxpositioncount = get_theme_mod('start_max_tagposition');
                            $showsticky  = get_theme_mod('advance_show_sticky_posts');
                            $maxsticky = get_theme_mod('advance_show_sticky_posts_max');

                            if ($showsticky) {
                                $sticky = get_option( 'sticky_posts' );
                                if (!empty($sticky)) {
                                   // Sticky posts exists
                                    $sticky_query = new WP_Query( array(
                                        'post__in' => get_option( 'sticky_posts' ),
                                        'ignore_sticky_posts' => false,
                                        'posts_per_page' => $maxsticky,
                                            // stickys have their own count
                                        'post__not_in' => $displayedposts,
                                    ) );

                                    while ( $sticky_query->have_posts() && ( $number < $maxall ) ) {
                                        $sticky_query->the_post();
                                        echo fau_display_news_teaser( $post->ID );
                                        $number++;
                                        $displayedposts[] = $post->ID;
                                    }
                                    wp_reset_postdata();

                                }
                            }

                            // retrieve regular posts with specific tags
                            // The number to the position is fixed to $maxpositioncount
                            // so that we look for Tags e.g. startseite1, startseite2, startseite3, if 
                            // $maxpositioncount = 3
                            // For each tag, there could be $max entries.

                            for ( $j = 1; $j <= $maxpositioncount; $j++ ) {
                                $i = 0;
                                $thistag = get_theme_mod( 'start_prefix_tag_newscontent' ) . $j;

                                $query = new WP_Query( array(
                                    'tag' => $thistag,
                                    'ignore_sticky_posts' => false,
                                    'post__not_in' => $displayedposts,
                                ) );

                                while ( $query->have_posts() && ( $i < $max ) && ( $number < $maxall ) ) {
                                    $query->the_post();
                                    echo fau_display_news_teaser( $post->ID );
                                    $i++;
                                    $number++;
                                    $displayedposts[] = $post->ID;
                                }
                                wp_reset_postdata();
                                wp_reset_query();
                            }


                            $newscat = get_theme_mod('start_link_news_cat');
                            if (($number==0) || ($number < $maxall)) {   
                                if ($number < $maxall) {
                                    $num = $maxall - $number;
                                    if ($num <=0 ) {
                                        $num=1;
                                    }
                                    if (isset($newscat)) {
                                        $query = new WP_Query(  array( 'post__not_in' => $displayedposts, 'posts_per_page'  => $num, 'has_password' => false, 'post_type' => 'post', 'cat' => $newscat  ) );
                                    } else {
                                        $query = new WP_Query(  array( 'post__not_in' => $displayedposts, 'posts_per_page'  => $num, 'has_password' => false, 'post_type' => 'post'  ) );							    
                                    }
                                } else {
                                    $args = '';

                                    if (isset($newscat)) {
                                        $args = 'cat='.$newscat;	
                                    }
                                    if (isset($args)) {
                                        $args .= '&';
                                    }

                                    $args .= 'post_type=post&has_password=0&posts_per_page='.get_theme_mod('start_max_newscontent');	
                                    $query = new WP_Query( $args );
                                }
                                while ($query->have_posts() ) { 
                                    $query->the_post(); 
                                    echo fau_display_news_teaser($post->ID);
                                     wp_reset_postdata();
                                     $number = 1;
                                }
                            }
                            if ($number > 0) {
                                $showcatlink = get_theme_mod('start_link_news_show');
                                if (($showcatlink==true) && ($newscat>0)) {
                                    echo fau_get_category_links();
                                }
                            } else {
                                echo '<div class="alert alert-warning">';
                                echo __('Es konnten keine öffentlichen Beiträge gefunden werden.','fau');
                                echo '</div>';
                            }
                        } 
					?>			    
				    </main>	
				</div>
				<?php get_template_part('template-parts/sidebar', 'portal');?>
			</div> <!-- /row -->
			<?php  
			
			get_template_part('template-parts/content', 'portalmenu-unten');

			$logoliste = get_post_meta( $post->ID, 'fauval_imagelink_catid', true );			
			if ($logoliste) { 
			    /* New since 1.10.57 */
			    $logosize = get_post_meta( $post->ID, 'fauval_imagelink_size', true );
			    $size = $logosize != '' ? esc_attr($logosize) : "logo-thumb";
			    $logos = fau_imagelink_get(array('size' => $size, 'catid' => $logoliste));
			    if ((isset($logos) && (!empty($logos)))) {
				echo "<hr>\n";
				echo $logos;
			    }
			}		
			 ?>			
		</div> <!-- /container -->
	</div> <!-- /content -->
<?php 
get_footer(); 

