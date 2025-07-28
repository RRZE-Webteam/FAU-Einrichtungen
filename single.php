<?php

/**
 * The template for displaying a single post.
 *
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
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
	<div id="content" class="herotype-<?php echo $herotype;?>">
		<div class="content-container">
			<div class="post-row">
				<div <?php post_class('entry-content'); ?>>
					<main>
                        <?php 
                         if ($titleforscreenreader) {   ?>
                            <h1 id="maintop"  class="mobiletitle"><?php the_title(); ?></h1>
                             <?php } else { ?>
                            <h1 id="maintop" ><?php the_title(); ?></h1>
                             <?php } ?>
                            

						<article class="news-details">
							<?php if (has_post_thumbnail() && !post_password_required()) {

								$post_thumbnail_id = get_post_thumbnail_id();
								if ($post_thumbnail_id && !metadata_exists('post', $post->ID, 'vidpod_url')) {
									$value = get_post_meta( $post->ID, '_hide_featured_image', true );
									$imgdata = fau_get_image_attributs($post_thumbnail_id);
									$full_image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'full');
									if ($full_image_attributes) {
										$altattr = trim(strip_tags($imgdata['alt']));
										if ((fau_empty($altattr)) && (get_theme_mod("advanced_display_postthumb_alt-from-desc"))) {
											$altattr = trim(strip_tags($imgdata['description']));
										}
										if (fau_empty($altattr)) {
											// falls es noch immer leer ist, geben wir an, dass dieses Bild ein Symbolbild ist und 
											// der Klick das Bild größer macht.
											$altattr = __('Symbolbild zum Artikel. Der Link öffnet das Bild in einer großen Anzeige.', 'fau');
										}
										if ($value) {
											$post_thumbnail_id = get_post_thumbnail_id($post->ID);
											$post_thumbnail_url = wp_get_attachment_image_src($post_thumbnail_id, 'full')[0];
											echo '<style type="text/css">.post-image { display: none; }</style>';
											echo '<img src="' . esc_url($post_thumbnail_url) . '" class="post-image" />';
										}
										echo '<div class="post-image">';
										echo '<figure>';
										echo '<a class="lightbox" href="' . fau_esc_url($full_image_attributes[0]) . '">';
										echo fau_get_image_htmlcode($post_thumbnail_id, 'rwd-480-3-2', $altattr);
										echo '</a>';

										$bildunterschrift = get_post_meta($post->ID, 'fauval_overwrite_thumbdesc', true);
										if (isset($bildunterschrift) && strlen($bildunterschrift) > 1) {
											$imgdata['fauval_overwrite_thumbdesc'] = $bildunterschrift;
										}
										echo fau_get_image_figcaption($imgdata);
										echo '</figure>';
										echo '</div>';
									}
								}
							}

							$output = '<div class="post-meta">';
							$output .= '<span class="post-meta-date"> ' . get_the_date('', $post->ID) . "</span>";
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
									'prev_text'  => __('%title', 'fau'),
									'next_text'  => __('%title', 'fau'),
									'in_same_term' => true,
									'taxonomy' => __('Kategorie', 'fau'),
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
