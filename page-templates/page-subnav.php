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

	<?php get_template_part('template-parts/hero', 'small'); ?>

	<div id="content">
		<div class="container">
			
		<?php 
		echo fau_get_ad('werbebanner_seitlich',false);
		?>

			<div class="row">		
				<div class="sidebar-subnav">
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
				
				<div class="entry-content">
				    <main<?php echo fau_get_page_langcode($post->ID);?>>
					<?php 
					$headline = get_post_meta( $post->ID, 'headline', true );									
					if (!fau_empty($headline)) {
					    echo '<h2 class="subtitle">'.$headline.'</h2>'; 					    
					}

					?>
					<div class="inline-box">					   	
					    <?php get_template_part('template-parts/sidebar', 'inline'); ?> 
					    <div class="content-inline">
					    <?php the_content(); ?>
					    </div>
					</div>
					<?php echo wp_link_pages($pagebreakargs); ?>
				    </main>    
				  <?php  echo fau_get_ad('werbebanner_unten',true); ?>				  
				</div>
				
			</div>
		</div>
  		<?php get_template_part('template-parts/footer', 'social'); ?>	
	</div>
	
	
<?php endwhile; ?>

<?php 
get_footer(); 