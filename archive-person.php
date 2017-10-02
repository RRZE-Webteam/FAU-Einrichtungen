<?php
/**
 * The main template file.
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header(); 

	$posttype = get_post_type();
	get_template_part('template-parts/hero', 'index'); 
	$active_sidebar = false;
	?>

	<div id="content">
		<div class="container">
		
			<div class="row">
			    <?php if ( is_active_sidebar( 'search-sidebar' ) ) { 	
				// add sidebar and nest content in sub-row
				$active_sidebar = 1; ?>
				<div class="search-sidebar">
					    <?php dynamic_sidebar( 'search-sidebar' ); ?>
				</div>	
				<div class="search-resultnested">   
				    <div class="row">
			    <?php } ?>
			    
				    <main>
					
					<?php 
					    
					    while ( have_posts() ) { 
						the_post();  
						echo FAU_Person_Shortcodes::fau_person(array("id"=> $post->ID, 'format' => 'kompakt', 'showlink' => 1)); 
					    } ?>
				
					    <nav class="navigation">
						<div class="nav-previous"><?php previous_posts_link(__('<span class="meta-nav">&laquo;</span> Vorherige Einträge', 'fau')); ?></div>
						<div class="nav-next"><?php next_posts_link(__('Weitere Einträge <span class="meta-nav">&raquo;</span>', 'fau'), '' ); ?></div>
					    </nav>

				    </main>
				    
				     <?php if ( is_active_sidebar( 'search-sidebar' ) ) { 	?>
				    </div>
				 </div>
				 <?php  } ?>
				    
				</div>
				
				    <?php if(get_post_type() == 'post') {
					 get_template_part('template-parts/sidebar', 'news');
				    } ?>
				    
			</div>

		</div>
		<?php get_template_part('template-parts/footer', 'social'); ?>	
	</div>


<?php 
get_footer(); 

