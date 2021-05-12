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

    <div id="content" class="content-portal">
	<div class="container">	
	    <div class="row">
		    <main<?php echo fau_get_page_langcode($post->ID);?>>
			<h1 class="screen-reader-text"><?php the_title(); ?></h1>
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
			$displayedicons = 0;
			$menuslug = get_post_meta( $post->ID, 'portalmenu-slug', true );	
			if ($menuslug) { 
  
			    $nosub  = get_post_meta( $post->ID, 'fauval_portalmenu_nosub', true );
			    if ($nosub==1) {
				$displaysub =0;
			    } else {
				$displaysub =1;
			    }
			    $nofallbackthumbs  = get_post_meta( $post->ID, 'fauval_portalmenu_nofallbackthumb', true );
			    $nothumbnails  = get_post_meta( $post->ID, 'fauval_portalmenu_thumbnailson', true ); 

			    fau_get_contentmenu($menuslug,$displaysub,0,$nothumbnails,$nofallbackthumbs);
			    $displayedicons = 1;
			}
			   
			$logoliste = get_post_meta( $post->ID, 'fauval_imagelink_catid', true );			
			if ($logoliste) { 
			    /* New since 1.10.57 */
			    $logos = fau_imagelink_get(array('size' => "logo-thumb", 'catid' => $logoliste, "autoplay" => true, "dots" => true));
			    if ((isset($logos) && (!empty($logos)))) {
				if ($displayedicons==1) {
				    echo "<hr>\n";
				}
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
