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
get_header(); 

while ( have_posts() ) : the_post(); 
	get_template_part('template-parts/hero', 'small'); ?>
	<div id="content">
		<div class="container">
			<div class="row">			    
			    <?php if(get_post_type() == 'post') { ?>
			    <div class="entry-content">
			    <?php } else { ?>
			    <div class="col-xs-12">
			    <?php } ?>
				<main>
				    <h1 id="droppoint" class="screen-reader-text"><?php the_title(); ?></h1>
				    <article class="news-details">
					    <?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
						<div class="post-image">
						    <?php 
						    $bildunterschrift = get_post_meta( $post->ID, 'fauval_overwrite_thumbdesc', true );
						    $post_thumbnail_id = get_post_thumbnail_id(); 
						    
						    
						    if ($post_thumbnail_id) {
							$imgdata = fau_get_image_attributs($post_thumbnail_id);
							$full_image_attributes = wp_get_attachment_image_src( $post_thumbnail_id, 'full');
							$altattr = trim(strip_tags($imgdata['alt']));
							if ((fau_empty($altattr)) && (get_theme_mod("advanced_display_postthumb_alt-from-desc"))) {
							    $altattr = trim(strip_tags($imgdata['description']));
							}
							echo '<figure>';
							echo '<a class="lightbox" href="'.fau_esc_url($full_image_attributes[0]).'">';							
							$imagehtml = fau_get_image_htmlcode($post_thumbnail_id, 'rwd-480-3-2', $altattr);
							echo $imagehtml;
							echo '</a>';
							echo '<figcaption class="post-image-caption">';
							if (isset($bildunterschrift) && strlen($bildunterschrift)>1) {
							    echo $bildunterschrift;
							} else {		  
							    $info = "";
							    $credits = '';
							    if ($imgdata) {
								if ($imgdata['excerpt'] &&  !fau_empty($imgdata['excerpt'])) {
								    $info = trim(strip_tags( $imgdata['excerpt'] ));	
								}

								$displaycredits = get_theme_mod("advanced_display_postthumb_credits");
								if ($displaycredits==true) {
								    $credits = trim(strip_tags(  $imgdata['credits']));    
								}
							    }

							    if (  (!empty($info)) || (!empty($credits)) ) {
								if (!empty($info)) {
								    echo $info;
								}
								if (!empty($credits)) {
								    if ((!empty($info)) && ($credits != $info)) {
									echo "<br>";
									echo $credits;
								    } elseif (empty($info)) {
									echo $credits;
								    }
								}
							    } 
							} 
							echo '</figcaption>';
							echo '</figure>';
						    }

						    ?>
						</div>
					    <?php endif; 

					    $output = '<div class="post-meta">';
					    $output .= '<span class="post-meta-date"> '.get_the_date('',$post->ID)."</span>";
					    $output .= '</div>'."\n";

					    $headline = get_post_meta( $post->ID, 'fauval_untertitel', true );				
					    if ( $headline) {
						 echo '<h2 class="subtitle">'.$headline."</h2>\n";
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
						if($categories){
						    $typestr .= '<span class="post-meta-categories"> ';
						    $typestr .= __('Kategorie', 'fau');
						    $typestr .= ': ';

						    foreach($categories as $category) {
							$thiscatstr .= '<a href="'.get_category_link( $category->term_id ).'">'.$category->cat_name.'</a>'.$separator;
						    }
						    $typestr .= trim($thiscatstr, $separator);
						    $typestr .= '</span> ';
						}
					    }
					    if (get_theme_mod('post_display_tags_below')) {
						    $showfooter = true;
						    if(get_the_tag_list()) {
							$taglist = get_the_tag_list('<span class="post-meta-tags"> '.__('Schlagworte', 'fau').': ',', ','</span>');
						    }   
					    }

					    if ($showfooter) {   
						$output = '<p class="meta-footer">'."\n";
						if (!empty($typestr)) {
						    $output .= $typestr;
						}
						if (!empty($taglist)) {
						    $output .= $taglist;
						}
						$output .= '</p>'."\n";
						echo $output;   
					    }
					    ?>


				    </article>
				</main>
				<?php if ((get_post_type() == 'post') && (get_theme_mod('advanced_activate_post_comments'))) { ?>
				     <div class="post-comments" id="comments"> 
					<?php  comments_template(); ?>
				     </div>
				<?php } ?>
			    </div>
			    <?php if(get_post_type() == 'post') { get_template_part('template-parts/sidebar', 'news'); } ?>
			</div>
		</div>	
	</div>
<?php endwhile; 
get_template_part('template-parts/footer', 'social');
get_footer();
