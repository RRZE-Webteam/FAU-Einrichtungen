<?php
/**
 * The template for displaying a single post.
 *
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

global $options;
get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php get_template_part('hero', 'small'); ?>

	<section id="content">
		<div class="container">

			<div class="row">
				<div class="span12">
					
					<article>
				    
						<?php 
						
					
					
						$output = '';
					
						
						echo $output;    
						
						
						echo wp_get_attachment_image( $post->ID, 'large' );
						echo '<p class="auszug">';
						the_excerpt();
						echo "<p>\n";
						the_content();
						
						 $imgdata = fau_get_image_attributs($post->ID);
						if ( is_user_logged_in() ) {
						    echo '<p class="attention">'.__('Die folgenden Informationen werden nur für angemeldete Benutzer des CMS angezeigt:','fau').'</p>';
						    echo "<h3>Attribute</h3>";
						   
						    echo fau_array2table($imgdata);

						    echo "<h3>Metadaten des Bildes</h3>";
						    $meta = get_post_meta( $post->ID );

						    if (isset($meta) && isset($meta['_wp_attachment_metadata']) && is_array($meta['_wp_attachment_metadata'])) { 
							$data = unserialize($meta['_wp_attachment_metadata'][0]);

							echo fau_array2table($data['image_meta']);

							echo "<h3>Verfügbare Auflösungen</h3>\n";
							echo fau_array2table($data['sizes']);


						    } else { 
							 echo "<p>Keine Metadaten abrufbar.</p>";
						    }
						  
						 echo '<p class="hinweis">'.__('Die folgenden Informationen werden öffentlich angezeigt:','fau').'</p>';
						}
						?>
						    
						    <h3>Daten zum Bild</h3>
						    
						    <table>
							<tr>
							    <th>Titel</th>
							    <td><?php echo $imgdata['title']; ?></td>
							</tr>
							<?php if(!empty($imgdata['caption'])) { ?>
							<tr>
							    <th>Bildunterschrift</th>
							    <td><?php echo $imgdata['caption']; ?></td>
							</tr>
							<?php } ?>
							<?php if(!empty($imgdata['excerpt'])) { ?>
							<tr>
							    <th>Auszug</th>
							    <td><?php echo $imgdata['excerpt']; ?></td>
							</tr>
							<?php } ?>
							<?php if(!empty($imgdata['description'])) { ?>
							<tr>
							    <th>Beschreibung</th>
							    <td><?php echo $imgdata['description']; ?></td>
							</tr>
							<?php } ?>
							<?php if(!empty($imgdata['copyright'])) { ?>
							<tr>
							    <th>Information zu Urheberrecht / Lizenz</th>
							    <td><?php echo $imgdata['copyright']; ?></td>
							</tr>
							<?php } ?>
						    </table>
						    
						    
						
					    
						
					</article>
					  
				</div>
				
				<?php get_template_part('sidebar', 'news'); ?>
			</div>

		</div>
	</section>
	
<?php endwhile; ?>

<?php get_footer(); 