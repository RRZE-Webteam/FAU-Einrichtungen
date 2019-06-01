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
	<div id="hero">
	<?php get_template_part('template-parts/hero', 'slider'); ?>
	<?php get_template_part('template-parts/hero', 'jumplinks'); ?>
	</div>
	<div id="content">
		<div class="container">
			<?php  echo fau_get_ad('werbebanner_seitlich',false); ?>
			
			<div class="row">
				<div class="startpage-blogroll">
				    <main<?php echo fau_get_page_langcode($post->ID);?> id="droppoint">
					<h1 class="screen-reader-text"><?php the_title(); ?></h1>
					
					<?php
					
					$number = 0;
					$max = get_theme_mod('start_max_newspertag');
					$maxall = get_theme_mod('start_max_newscontent');
					$displayedposts = array();
					$newscat = get_theme_mod('start_link_news_cat');
					for($j = 1; $j <= 3; $j++) {
						$i = 0;
						$thistag = get_theme_mod('start_prefix_tag_newscontent').$j;    
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
					    }
					}
					$showcatlink = get_theme_mod('start_link_news_show');
					if (($showcatlink==true) && ($newscat>0)) {
					    echo fau_get_category_links();
					}
					?>			    
				    </main>	
				</div>
				<aside class="startpage-sidebar" aria-label="<?php echo __('Sidebar','fau');?>">
					<?php
					get_template_part('template-parts/sidebar', 'events'); 	
					get_template_part('template-parts/sidebar');
					?>
				</aside>
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
			if ($logoliste) { 
			    /* New since 1.10.57 */
			    $logos = fau_imagelink_get(array('size' => "logo-thumb", 'catid' => $logoliste, "autoplay" => true, "dots" => true));
			    if ((isset($logos) && (!empty($logos)))) {
				echo "<hr>\n";
				echo $logos;
			    }
			}		
			 ?>			
		</div> <!-- /container -->
	</div> <!-- /content -->
<?php get_template_part('template-parts/footer', 'social'); ?>	
<?php 
get_footer(); 

