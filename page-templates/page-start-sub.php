<?php
/**
 * Template Name: Startseite
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

    get_header();    
    get_template_part('template-parts/hero', 'banner');
    
    global $options;

?>

	<div id="content">
		<div class="container">
			<?php 
			    echo fau_get_ad('werbebanner_seitlich',false);
			 ?>
			
			<div class="row">
				<div class="startpage-blogroll">
				   
			<main<?php echo fau_get_page_langcode($post->ID);?>>	    
		    <?php 
			wp_reset_postdata();
			wp_reset_query();

			the_content();
			
		
			$number = 0;
			$max = $options['start_max_newspertag'];
			$maxall = $options['start_max_newscontent'];
			    
			if ($maxall > 0) {    
			    
			    $displayedposts = array();
			    for($j = 1; $j <= 3; $j++) {
				    $i = 0;
				    $thistag = $options['start_prefix_tag_newscontent'].$j;    
				    $query = new WP_Query( 'tag='.$thistag );

				     while ($query->have_posts() && ($i<$max) && ($number<$maxall) ) { 
					$query->the_post(); 
					echo fau_display_news_teaser($post->ID);
					$i++;
					$number++;
					$displayedposts[] = $post->ID;
				    }
				    wp_reset_postdata();
				    wp_reset_query();

			    }
			    if (($number==0) || ($number < $maxall)) {

				if ($number < $maxall) {
				    $num = $maxall - $number;
				    if ($num <=0 ) {
					$num=1;
				    }
				    if (isset($options['start_link_news_cat'])) {
					$query = new WP_Query(  array( 'post__not_in' => $displayedposts, 'posts_per_page'  => $num, 'has_password' => false, 'post_type' => 'post', 'cat' => $options['start_link_news_cat']  ) );
				    } else {
					$query = new WP_Query(  array( 'post__not_in' => $displayedposts, 'posts_per_page'  => $num, 'has_password' => false, 'post_type' => 'post'  ) );							    
				    }
				} else {
				     $args = '';
				    if (isset($options['start_link_news_cat'])) {
					$args = 'cat='.$options['start_link_news_cat'];	
				    }
				    if (isset($args)) {
					$args .= '&';
				    }

				    $args .= 'post_type=post&has_password=0&posts_per_page='.$options['start_max_newscontent'];	
				    $query = new WP_Query( $args );
				}
				while ($query->have_posts() ) { 
				    $query->the_post(); 
				    echo fau_display_news_teaser($post->ID);
				     wp_reset_postdata();
				}
			    }

			    if ($options['start_link_news_show']) {
				echo fau_get_category_links();
			    }
			}    
			?>
			</main>	
				</div>
				<div class="startpage-sidebar">
					<?php
					get_template_part('template-parts/sidebar', 'events'); 					
					get_template_part('template-parts/sidebar');
					?>
				</div>
			</div> <!-- /row -->
			<?php  
			
		
			 $menuslug = get_post_meta( $post->ID, 'portalmenu-slug', true );	
			 if ($menuslug) { ?>	
			    <hr>
			    <?php 			
				$nosub  = get_post_meta( $post->ID, 'fauval_portalmenu_nosub', true );
				if ($nosub==1) {
				    $displaysub =0;
				} else {
				    $displaysub =1;
				}
				$nofallbackthumbs  = get_post_meta( $post->ID, 'fauval_portalmenu_nofallbackthumb', true );
				$nothumbnails  = get_post_meta( $post->ID, 'fauval_portalmenu_thumbnailson', true ); 

				fau_get_contentmenu($menuslug,$displaysub,0,$nothumbnails,$nofallbackthumbs);
	
			 }
			 
			echo fau_get_ad('werbebanner_unten',true);
			
			 $logoliste = get_post_meta( $post->ID, 'fauval_imagelink_catid', true );
			 if ($logoliste) { ?>	
			    <hr>
			    <?php 
			    fau_get_imagelinks($logoliste);
			     
			 }
			
			 ?>
			
			
		</div> <!-- /container -->
		<?php get_template_part('template-parts/footer', 'social'); ?>	
		
	</div> <!-- /content -->

<?php 
get_footer(); 

