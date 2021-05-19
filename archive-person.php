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
	<div class="content-container">
	    <div class="content-row">
		    <div class="search-resultnested">   
			<main>
			    <h1 class="screen-reader-text"><?php 
				$post_type = get_post_type_object(get_post_type());
				echo $post_type->labels->name;
				?></h1>
			    
			    <?php 
				while ( have_posts() ) { 
				    the_post();  
				    echo FAU_Person_Shortcodes::fau_person(array("id"=> $post->ID, 'format' => 'kompakt', 'showlink' => 1, 'hstart' => 2)); 
				} ?>

				<nav class="navigation">
				    <div class="nav-previous"><?php previous_posts_link(__('<span class="meta-nav">&laquo;</span> Vorherige Einträge', 'fau')); ?></div>
				    <div class="nav-next"><?php next_posts_link(__('Weitere Einträge <span class="meta-nav">&raquo;</span>', 'fau'), '' ); ?></div>
				</nav>
			</main>
		    </div>

		    <?php if ( is_active_sidebar( 'search-sidebar' ) ) { 	?>
		    <div class="search-sidebar">
			<?php dynamic_sidebar( 'search-sidebar' ); ?>
		    </div>	
		    <?php } ?>

	    </div>
	</div>
    </div>


<?php 
get_template_part('template-parts/footer', 'social'); 
get_footer(); 


