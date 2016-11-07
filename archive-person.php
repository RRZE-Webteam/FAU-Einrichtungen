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
	get_template_part('hero', 'personindex'); 
	$active_sidebar = false;
	?>

	<div id="content">
		<div class="container">
		
			<div class="row">
			     <?php 
				if (is_active_sidebar( 'search-sidebar' ) ) { 	
					$active_sidebar = true; ?>
				<div class="span3">
					<div class="search-sidebar">
					    <?php dynamic_sidebar( 'search-sidebar' ); ?>
					</div>
				</div>
				<div class="span9">			
			     <?php } else { ?>
				<div class="span12">	
			     <?php }  ?>
				    <main>
					<?php 

					    while ( have_posts() ) { 
						the_post();  
						 echo FAU_Person_Shortcodes::fau_person(array("id"=> $post->ID, 'format' => 'kompakt', 'showlink' => 1, 'showlist' => 1 )); 				    
					    } ?>
					    <nav class="navigation">
						<div class="nav-previous"><?php previous_posts_link(__('<span class="meta-nav">&laquo;</span> Vorherige Einträge', 'fau')); ?></div>
						<div class="nav-next"><?php next_posts_link(__('Weitere Einträge <span class="meta-nav">&raquo;</span>', 'fau'), '' ); ?></div>
					    </nav>

	
					
					
				    </main>
				</div>
				
				    <?php if(get_post_type() == 'post') {
					 get_template_part('sidebar', 'news');
				    } ?>
				    
			</div>

		</div>
		<?php get_template_part('footer', 'social'); ?>	
	</div>


<?php 
get_footer(); 

