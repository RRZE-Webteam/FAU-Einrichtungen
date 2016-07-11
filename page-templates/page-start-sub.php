<?php
/**
 * Template Name: Startseite
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header();
global $options;


?>

	<section id="hero" class="hero-banner">
		<div class="banner">
		    <div class="introimg">
			    <?php 
			    $copyright = '';
			    if (isset($options['startseite_banner_image_id']) && ($options['startseite_banner_image_id']>0)) {
				$imagedata = wp_get_attachment_image_src( $options['startseite_banner_image_id'], 'herobanner' );
				
				$slidersrcset =  wp_get_attachment_image_srcset($options['startseite_banner_image_id'],'herobanner');
				
				if ($imagedata) {
				    $image = '<img src="'.fau_esc_url($imagedata[0]).'" width="'.$imagedata[1].'" height="'.$imagedata[2].'" alt=""';
				    if ($slidersrcset) {
					$image .= 'srcset="'.$slidersrcset.'"';
				    }
				    $image .= '>';
				    
				}
				$imgdata = fau_get_image_attributs($options['startseite_banner_image_id']);
				$copyright = trim(strip_tags( $imgdata['credits'] ));
			    } elseif ($options['startseite_banner_usedefault']) {
				$image = '<img src="'.fau_esc_url($options['default_startseite-bannerbild-image_src']).'" width="'.$options['default_startseite-bannerbild-image_width'].'" height="'.$options['default_startseite-bannerbild-image_height'].'" alt="">';	
			    } else {
				$image = '';
			    }
			    echo $image."\n"; 
			    if (($options['advanced_display_hero_credits']==true) && (!empty($copyright))) {
				echo '<p class="credits">'.$copyright."</p>";
			    } 
			    ?>
			     <div class="banner-text">
				<div class="container">
				    <div class="row">
					    <div role="presentation" class="span9 infobar">				    
						<?php 
					       $header_image = get_header_image();
						if (!empty( $header_image ) ){	
						    echo "<h1>". get_bloginfo( 'title' ). "</h1>\n";
						}
						$desc = trim(strip_tags(get_bloginfo( 'description' )));
						if (!empty($desc)) {
						    if (!empty( $header_image ) ){	
							echo "<br>";
						    }
						     echo '<p class="description">'.$desc."</p>";
						}
						?>
					    </div>
				    </div>
				<?php if ($options['advanced_page_start_herojumplink']) { ?>
				    <a href="#content" class="hero-jumplink-content"></a>
				<?php } ?>
				</div>
			     </div>

	
				    
		    </div>
		</div>
	
		
	</section> <!-- /hero -->

	<div id="content">
		<div class="container">
			<?php 
			    echo fau_get_ad('werbebanner_seitlich',false);
			 ?>
			
			<div class="row">
				<div class="span8">
				   
				    
		    <?php 
			wp_reset_postdata();
			wp_reset_query();
			
			echo "<main>\n";
			
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
				<div class="span4 sidebar-outline">
					<?php
					if ($options['start_topevents_active']) {
					    get_template_part('sidebar', 'events'); 	
					}				
					get_template_part('sidebar');
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

				fau_get_contentmenu($menuslug,$displaysub,0,0,$nothumbnails,$nofallbackthumbs);
	
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
		<?php get_template_part('footer', 'social'); ?>	
		
	</div> <!-- /content -->

<?php 
get_footer(); 

