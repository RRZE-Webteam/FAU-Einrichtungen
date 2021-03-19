<?php
/**
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php get_template_part('template-parts/hero', 'small'); ?>

	<div id="content">
		<div class="container">
			<div class="row">
				 <div <?php post_class( 'entry-content' ); ?>>
				    <main>
				    <?php 
				    $id = $post->ID;
				    if ($id) { ?>
					 <h1 id="droppoint" class="mobiletitle"><?php the_title(); ?></h1>
					<?php echo FAU_Person_Shortcodes::fau_person_page($id);
				    } else { ?>
					<h1 id="droppoint" class="mobiletitle"><?php _e('Fehler','fau'); ?></h1>
					<p class="hinweis">
					    <strong><?php _e('Es tut uns leid.','fau'); ?></strong><br>
					    <?php _e('Für den angegebenen Kontakt können keine Informationen abgerufen werden.','fau'); ?>
					</p>
				    <?php }  ?>
				    </main>
			    </div>
				
			</div>
		</div>
	</div>
	
	
<?php endwhile; 
 get_template_part('template-parts/footer', 'social'); 
 get_footer();