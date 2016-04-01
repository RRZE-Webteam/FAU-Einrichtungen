<?php
/**
 * Template Name: Startseite Fakultät
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header();
global $options;


?>

	<section id="hero">
		<div id="hero-slides">
			
			<?php	
			global $usejslibs;
			
			$usejslibs['flexslider'] = true;
			
            if (isset($options['slider-catid']) && $options['slider-catid'] > 0) {
                $hero_posts = get_posts( array( 'cat' => $options['slider-catid'], 'posts_per_page' => $options['start_header_count']) );
            } else {							    
                $category = get_term_by('slug', $options['slider-category'], 'category');
                if($category) {
                    $query = array(
                        'numberposts' => $options['start_header_count'],
                        'tax_query' => array(
                        array(
                            'taxonomy' => 'category',
                            'field' => 'id', // can be slug or id - a CPT-onomy term's ID is the same as its post ID
                            'terms' => $category->term_id
                            )
                        )
                    );
                } else {
                    $query = array(
                        'numberposts' => $options['start_header_count']
                    );                    
                }
                $hero_posts = get_posts($query); 
            }
            foreach($hero_posts as $hero): ?>
		<div class="hero-slide">
			    <?php 
			    
			    $sliderimage = '';
			    $copyright = '';
			   // $imageid = get_post_meta( $hero->ID, 'fauval_sliderid', true );
			    $imageid = get_post_meta( $hero->ID, 'fauval_slider_image', true );
			    if (isset($imageid) && ($imageid>0)) {
				$sliderimage = wp_get_attachment_image_src($imageid, 'hero'); 
				$imgdata = fau_get_image_attributs($imageid);
				$copyright = trim(strip_tags( $imgdata['credits'] ));
			    } else {
				$post_thumbnail_id = get_post_thumbnail_id( $hero->ID ); 
				if ($post_thumbnail_id) {
				    $sliderimage = wp_get_attachment_image_src( $post_thumbnail_id, 'hero' );
				    $imgdata = fau_get_image_attributs($post_thumbnail_id);
				    $copyright = trim(strip_tags( $imgdata['credits'] ));
				}
			    }

			    if (!$sliderimage || empty($sliderimage[0])) {  
				$slidersrc = '<img src="'.fau_esc_url($options['src-fallback-slider-image']).'" width="'.$options['slider-image-width'].'" height="'.$options['slider-image-height'].'" alt="">';			    
			    } else {
				$slidersrc = '<img src="'.fau_esc_url($sliderimage[0]).'" width="'.$options['slider-image-width'].'" height="'.$options['slider-image-height'].'" alt="">';	
			    }
			    echo $slidersrc."\n"; 
			    if (($options['advanced_display_hero_credits']==true) && (!empty($copyright))) {
				echo '<p class="credits">'.$copyright."</p>";
			    }
			    ?>
			    <div class="hero-slide-text">
				<div class="container">
					    <?php
						echo '<h2><a href="';
						
						$link = get_post_meta( $hero->ID, 'external_link', true );
						$external = 0;
						if (isset($link) && (filter_var($link, FILTER_VALIDATE_URL))) {
						    $external = 1;
						} else {
						    $link = get_permalink($hero->ID);
						}
						echo $link;
						echo '">'.get_the_title($hero->ID).'</a></h2>'."\n";					
	
						$abstract = get_post_meta( $hero->ID, 'abstract', true );
						if (strlen(trim($abstract))<3) {
						   $abstract =  fau_custom_excerpt($hero->ID,$options['default_slider_excerpt_length'],false);
						} ?>
						<br><p><?php echo $abstract; ?></p>
				</div>
			    </div>
		  
		    </div>
	    <?php endforeach; 
              wp_reset_query();
	      ?>
		  <script type="text/javascript">
			jQuery(document).ready(function($) {
			$('#hero-slides').flexslider({
				selector: '.hero-slide',
				directionNav: true,
				pausePlay: true
			});
		    });
		    </script>
		</div>
	
		<div class="container">
			<div class="row">
				<div role="presentation" class="span6 infobar">				    
				    <?php 
				   $header_image = get_header_image();
				    if (!empty( $header_image ) ){	
					echo "<h1>". get_bloginfo( 'title' ). "</h1>\n";
				    }
				    if (null !== get_bloginfo( 'description' )) {
					 echo '<p class="description">'.get_bloginfo( 'description' )."</p>";
				    }
				    ?>
				</div>
				<div class="span3">
					<?php if(has_nav_menu('quicklinks-3')) { ?>
						<h3><?php echo fau_get_menu_name('quicklinks-3'); ?></h3>
						<?php wp_nav_menu( array( 'theme_location' => 'quicklinks-3', 'container' => false, 'items_wrap' => '<ul class="%2$s">%3$s</ul>' ) ); 
					} else {
					    echo fau_get_defaultlinks('centers');
					} ?>
				</div>
				<div class="span3">
					<?php if(has_nav_menu('quicklinks-4')) { ?>
						<h3><?php echo fau_get_menu_name('quicklinks-4'); ?></h3>
						<?php wp_nav_menu( array( 'theme_location' => 'quicklinks-4', 'container' => false, 'items_wrap' => '<ul class="%2$s">%3$s</ul>' ) );  
					} else {
					    echo fau_get_defaultlinks('infos');
					} ?>
				</div>
			</div>
		    <?php if ($options['advanced_page_start_herojumplink']) { ?>
			<a href="#content" class="hero-jumplink-content"></a>
		    <?php } ?>
		</div>
	</section> <!-- /hero -->

	<div id="content">
		<div class="container">
			<?php 
			    echo fau_get_ad('werbebanner_seitlich',false);
			 ?>
			
			<div class="row">
				<div class="span8">
				    <main>
					
					<?php
					
					$number = 0;
					$max = $options['start_max_newspertag'];
					$maxall = $options['start_max_newscontent'];
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

					$category = get_the_category_by_ID($options['start_link_news_cat']);
					if (($category) && ($options['start_link_news_show']==1)) { ?>
					
					<div class="news-more-links">
						<a class="news-more" href="<?php echo get_category_link($options['start_link_news_cat']); ?>"><?php echo $options['start_link_news_linktitle']; ?></a>
						<a class="news-rss" href="<?php echo get_category_feed_link($options['start_link_news_cat']); ?>">RSS</a>
					</div>
					<?php } ?>			    
				    </main>	
				</div>
				<div class="span4">
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
			 if ($logoliste) { 
			    $logos = fau_get_imagelinks($logoliste, false);
			    if ((isset($logos) && (!empty($logos)))) {
				echo "<hr>\n";
				echo $logos;
			    }
			 }
			
			 ?>
			
		</div> <!-- /container -->
		<?php get_template_part('footer', 'social'); ?>	
		
	</div> <!-- /content -->

<?php 
get_footer(); 

