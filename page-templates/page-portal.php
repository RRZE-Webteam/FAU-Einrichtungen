<?php
/**
 * Template Name: Portalseite 
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

    <?php get_template_part('template-parts/hero', 'small'); ?>

    <div id="content" class="content-portal">
	<div class="container">			   
	    <div class="row">
		<div class="portalpage-content">
		     <main<?php echo fau_get_page_langcode($post->ID);?>>
			<h1 class="screen-reader-text"><?php the_title(); ?></h1>
		    <?php 
		   
		    
			$headline = get_post_meta( $post->ID, 'headline', true );				
			if (!fau_empty($headline)) {
			     echo '<h2 class="subtitle">'.$headline."</h2>\n";  
			}
			the_content(); 
			?>
		    </main> 
		</div>
		<?php get_template_part('template-parts/sidebar', 'portal');?>
		

	    </div>
		
	    <?php  

	    $menuslug = get_post_meta( $post->ID, 'portalmenu-slug', true );	
	    if ($menuslug) { 	
		echo "<hr>";
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

		$logoliste = get_post_meta( $post->ID, 'fauval_imagelink_catid', true );			
		if ($logoliste) { 
		    /* New since 1.10.57 */
		    $logos = fau_imagelink_get(array('size' => "logo-thumb", 'catid' => $logoliste, "autoplay" => true, "dots" => true));
		    if ((isset($logos) && (!empty($logos)))) {
			echo $logos;
		    }	   
		}
	      
	    ?>
	</div>
	
	
    </div>
<?php endwhile; ?>
<?php get_template_part('template-parts/footer', 'social'); ?>	
<?php 
get_footer();
