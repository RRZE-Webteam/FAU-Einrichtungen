<?php
/**
 * Template part for small hero
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */
 
global $options; 
?>

    <section id="hero" class="hero-small">
	<div class="container">
		<div class="row">
		    <div class="span12">
			<?php 
			 fau_breadcrumb();
			 ?>

			<div class="hero-meta-portal">
			    <?php
				if(get_post_type() == 'page') {
					$parent = array_reverse(get_post_ancestors(get_the_ID()));
					if(isset($parent) && is_array($parent) && isset($parent[0])) {
						$first_parent = get_page($parent[0]);
					}

					if (isset($first_parent)) { //  && ($first_parent->ID != get_the_ID())) {
						echo $first_parent->post_title;
					}
				} else if(get_post_type() == 'post') {
					echo $options['title_hero_post_archive'];
				}
			    ?>
			</div>
		    </div>
		</div>
		<div class="row">
		    <div class="span6">
			<h1><?php the_title(); ?></h1>
		    </div>
		</div>
	</div>
    </section>
