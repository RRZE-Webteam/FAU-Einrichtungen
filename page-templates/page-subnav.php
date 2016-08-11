<?php
/**
 * Template Name: Inhaltsseite mit Navi
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php get_template_part('hero', 'small'); ?>

	<div id="content">
		<div class="container">
			
		<?php 
		echo fau_get_ad('werbebanner_seitlich',false);
		?>

			<div class="row">		
				<div class="span4 span-sm-4">
					<?php 
					$offset = 0;
					if ($options['website_type']==-1) {
						$menulevel = get_post_meta( $post->ID, 'menu-level', true );
						if ($menulevel) {
							$offset = $menulevel;
						}
					}
					$parent_page = get_top_parent_page_id($post->ID, $offset);
					$parent = get_page($parent_page);
					?>
					<h2 class="small menu-header">
						<a href="<?php echo get_permalink($parent->ID); ?>"><?php echo $parent->post_title; ?></a>
					</h2>
					<ul id="subnav">
					<?php wp_list_pages("child_of=$parent_page&title_li="); ?>
					</ul>
				</div>
				
				<div class="span8 span-sm-8">
				    <main>
					<?php 
					$headline = get_post_meta( $post->ID, 'headline', true );									
					if ($headline) { 
					    echo '<h2>'.$headline.'</h2>'; 					    
					} else {
					    echo '<div class="page-nosubtitle">&nbsp;</div>';
					}


					get_template_part('sidebar', 'inline'); 
					the_content(); ?>
				    </main>    
				  <?php  echo fau_get_ad('werbebanner_unten',true); ?>				  
				</div>
				
			</div>
		</div>
  		<?php get_template_part('footer', 'social'); ?>	
	</div>
	
	
<?php endwhile; ?>

<?php 
get_footer(); 