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
		    <div class="col-xs-12">
			<?php 
			fau_breadcrumb();
			 
			$metatitel = "";
			if(get_post_type() == 'page') {
				$parent = array_reverse(get_post_ancestors(get_the_ID()));
				if(isset($parent) && is_array($parent) && isset($parent[0])) {
					$first_parent = get_page($parent[0]);
				}
				if (isset($first_parent)) { 
					$metatitel = $first_parent->post_title;
				}
			} else if(get_post_type() == 'post') {
				$metatitel = $options['title_hero_post_archive'];
			}

			if (!fau_empty($metatitel)) { ?>
			    <div class="hero-meta-portal" role="presentation" aria-hidden="true" ><?php echo $metatitel; ?></div>
			<?php } ?>
			
		    </div>
		</div>
		<div class="row">
		    <div class="col-xs-12 col-sm-8">
			<h1<?php echo fau_get_page_langcode($post->ID);?>><?php the_title(); ?></h1>
		    </div>
		</div>
	</div>
    </section>
