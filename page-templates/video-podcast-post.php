<?php
/*
*Template Name: Video Podcast
* Template Post Type: post,
* @package WordPress
* @subpackage FAU
* @since FAU 2.3
*/

global $pagebreakargs;
if (is_active_sidebar('news-sidebar')) {
	fau_use_sidebar(true);
}

get_header();
$herotype = get_theme_mod('advanced_header_template');
$titleforscreenreader = true;
if (empty($herotype)) { 
    $titleforscreenreader = true;
    $herotype = 'default';
} elseif (($herotype=='banner') || ($herotype=='slider')) {
   $titleforscreenreader = false;
}

while (have_posts()) : the_post();  ?>
	<div id="content" class="video-podcast herotype-<?php echo $herotype;?>">
		<div class="content-container">
			<div class="post-row">
				<div <?php post_class('entry-content'); ?>>
					<main>

                        <?php if ($titleforscreenreader) { ?>
                            <h1 id="maintop" class="mobiletitle"><?php the_title(); ?></h1>
                        <?php } else { ?>
                            <h1 id="maintop"><?php the_title(); ?></h1>
                        <?php } ?>

						<article class="news-details">
							<?php 

							$output = '<div class="post-meta">';
							$output .= '<span class="post-meta-date"> ' . get_the_date('', $post->ID) . "</span>";
							$output .= '</div>' . "\n";
                            $output .= '<div class="thumbnailregion';

                               
                            $output .= ' vidpod-thumb">';
                            $vidpod_url = get_post_meta($post->ID, 'vidpod_url', true);
                            
                            $output .= do_shortcode('[fauvideo url="' . $vidpod_url . '"]');
                            $vidpod_auth = get_post_meta($post->ID, 'vidpod_auth', true);
                            if (!fau_empty($vidpod_auth)) {
                                $output .= '<span class="post-meta-videopost-author"> '.$vidpod_auth.'</span>';
                            }
                            $output .= '</div>' . "\n";

							$headline = get_post_meta($post->ID, 'fauval_untertitel', true);
							if ($headline) {
								echo '<p class="subtitle">' . $headline . "</p>\n";
							}
							echo $output;
							the_content();

							echo wp_link_pages($pagebreakargs);


							$showfooter = false;
							if (get_theme_mod('post_display_category_below')) {
								$showfooter = true;
								$categories = get_the_category();
								$separator = ",\n ";
								$thiscatstr = '';
								$typestr = '';
								if ($categories) {
									$typestr .= '<span class="post-meta-categories"> ';
									$typestr .= __('Kategorie', 'fau');
									$typestr .= ': ';

									foreach ($categories as $category) {
										$thiscatstr .= '<a href="' . get_category_link($category->term_id) . '" aria-label="' . __('Beiträge aus der Kategorie', 'fau') . ' ' . $category->cat_name . ' ' . __('aufrufen', 'fau') . '">' . $category->cat_name . '</a>' . $separator;
									}
									$typestr .= trim($thiscatstr, $separator);
									$typestr .= '</span> ';
								}
							}
							$taglist = '';
							if (get_theme_mod('post_display_tags_below')) {
								$showfooter = true;
								$taglist = fau_get_the_taglist('<span class="post-meta-tags"> ' . __('Schlagworte', 'fau') . ': ', ', ', '</span>');
							}

							if ($showfooter) {
								$output = '<p class="meta-footer">' . "\n";
								if (!empty($typestr)) {
									$output .= $typestr;
								}
								if (!empty($taglist)) {
									$output .= $taglist;
								}
								$output .= '</p>' . "\n";
								echo $output;
							}
							?>


							<?php

							if (('' != get_theme_mod('post_prev_next')) && (true == get_theme_mod('post_prev_next'))) {
								the_post_navigation(array(
									'prev_text'  => __('%title'),
									'next_text'  => __('%title'),
									'in_same_term' => true,
									'taxonomy' => __('category'),
								));
							}
							?>


						</article>
					</main>
					<?php if ((get_post_type() == 'post') && (get_theme_mod('advanced_activate_post_comments'))) { ?>
						<aside class="post-comments" id="comments" aria-labelledby="comments-title">
							<?php comments_template(); ?>
						</aside>
					<?php } ?>
				</div>
				<?php if (get_post_type() == 'post') {
					get_template_part('template-parts/sidebar', 'posts');
				} ?>
			</div>
		</div>
	</div>
<?php endwhile;
get_footer();
