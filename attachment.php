<?php
/**
 * The template for displaying a single post.
 *
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

get_header(); 

while ( have_posts() ) : the_post(); ?>

	<?php get_template_part('template-parts/hero', 'small'); ?>
	<div id="content">
		<div class="content-container">
		    <div class="content-row">
					
			    <main>
				<h1 id="maintop"  class="screen-reader-text"><?php the_title(); ?></h1>
				    <?php 

				    echo wp_get_attachment_image( $post->ID, 'full' );
				    the_content();

				    $imgdata = fau_get_image_attributs($post->ID);
				    $meta = get_post_meta( $post->ID );


				    if ( is_user_logged_in() ) {
					echo '<p class="attention">'.__('Die folgenden Informationen werden nur für angemeldete Benutzer des CMS angezeigt:','fau').'</p>';
					echo "<h2>Attribute</h2>";

					echo fau_array2table($imgdata);


					if (isset($meta) && isset($meta['_wp_attachment_metadata']) && is_array($meta['_wp_attachment_metadata'])) { 
					    $data = unserialize($meta['_wp_attachment_metadata'][0]);
					    if (isset($data['image_meta'])) {    
						echo "<h2>Metadaten</h2>";
						echo fau_array2table($data['image_meta']);
					    }
					    echo "<h2>Verfügbare Auflösungen</h2>\n";
					    echo fau_array2table($data['sizes']);
					}

				     echo '<p class="hinweis">'.__('Die folgenden Informationen werden öffentlich angezeigt:','fau').'</p>';
				    }
				    ?>

					<h2>Daten zum Bild</h2>

					<table>
					    <tr>
						<th>Titel</th>
						<td><?php echo $imgdata['title']; ?></td>
					    </tr>
					   
					    <?php if(!empty($imgdata['excerpt'])) { ?>
					    <tr>
						<th>Beschriftung</th>
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
					    <?php } 

						$downloadurl = "";
						$parsed = parse_url( wp_get_attachment_url( $post->ID ) );
						$downloadurl    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );

						if (!fau_empty($downloadurl)) { ?>
							<tr>
							    <th><?php _e('Download','fau');?></th>
							    <td><a href="<?php echo $downloadurl; ?>"><?php echo $imgdata['title']; ?></a></td>
							</tr>
						    <?php 
						}
					    ?>

					</table>
			    </main>
			</div>
		</div>
	</div>
	
<?php endwhile; ?>
<?php get_template_part('template-parts/footer', 'social'); ?>	
<?php get_footer(); 