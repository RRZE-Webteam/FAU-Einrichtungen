<?php
/**
 * Template part for small hero
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */
 
?>

    <div id="hero" class="hero-small">
	<div class="container hero-content">
		<div class="row">
		    <div class="col-xs-12">
			<?php 
			fau_breadcrumb();
			 
			$metatitel = "";
			if(get_post_type() == 'page') {
			    if (get_theme_mod('breadcrumb_withtitle_parent_page')==true) {
				$parent = array_reverse(get_post_ancestors(get_the_ID()));
				if(isset($parent) && is_array($parent) && isset($parent[0])) {
					$first_parent = get_page($parent[0]);
				}
				if (isset($first_parent)) { 
					$metatitel = $first_parent->post_title;
				}
			    }
			} else if(get_post_type() == 'post') {
				$metatitel = get_theme_mod('title_hero_post_archive');
			}

			if (!fau_empty($metatitel)) { ?>
			    <div class="hero-meta-portal" role="presentation" aria-hidden="true" ><?php echo $metatitel; ?></div>
			<?php } ?>
			
		    </div>
		</div>
		<div class="row" aria-hidden="true" role="presentation">
		    <div class="col-xs-12">
			<p class="presentationtitle" <?php echo fau_get_page_langcode($post->ID);?>><?php the_title(); ?></p>
		    </div>
		</div>
	</div>
       <?php get_template_part('template-parts/hero', 'siegel'); ?>
    </div>
