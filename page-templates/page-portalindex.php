<?php
/**
 * Template Name: Portalindex
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

    <?php get_template_part('hero', 'small'); ?>

    <div id="content" class="content-portal">
	<div class="container">
		
	    <?php 
	       echo fau_get_ad('werbebanner_seitlich',false);
	     ?>
		
		
	    <div class="row">
		<div class="span12">
		    <main>
		    <?php 
			$headline = get_post_meta( $post->ID, 'headline', true );				
			if ( $headline) {
			      echo '<h2 class="subtitle">'.$headline."</h2>\n";  
			} else {
			    echo '<div class="page-nosubtitle">&nbsp;</div>';
			}
			
			
			get_template_part('sidebar', 'inline'); 
			the_content(); 
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

			    fau_get_contentmenu($menuslug,$displaysub,0,0,$nothumbnails,$nofallbackthumbs);
			    $displayedicons = 1;
			}
			   
			$logoliste = get_post_meta( $post->ID, 'fauval_imagelink_catid', true );			
			if ($logoliste) { 
			    $logos = fau_get_imagelinks($logoliste, false);
			    if ((isset($logos) && (!empty($logos)))) {
				if ($displayedicons==1) {
				    echo "<hr>\n";
				}
				echo $logos;
			    }
			 }
			
			
			?>
		    </main>
		     <?php echo fau_get_ad('werbebanner_unten',false); ?>
		</div>
	    </div>
		
	    <?php  

	 
	    ?>

	</div>
	<?php get_template_part('footer', 'social'); ?>	
    </div>
<?php endwhile; ?>

<?php 
get_footer();
