<?php
/**
 * The template for displaying the search page.
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */



get_header();
get_template_part('template-parts/hero', 'search');  
$notfound = false;
?>
 <div id="content">
    <div class="content-container">	   
	<div class="content-row">
	    <div class="searchpage-content ">
		     <main>

				<h1 class="screen-reader-text"><?php echo __('Webauftritt durchsuchen','fau'); ?></h1>
				
				<?php
				  if(strlen(get_search_query()) > 0) {
				    // Suchbegriff vorhanden
				    if(have_posts()) { 
					// Treffer vorhanden
					global $wp_query, $wp_rewrite;?>
						<h2><?php _e('Suchergebnisse','fau'); ?></h2>
						<?php
						get_template_part('template-parts/search', 'form');  
						
						
						$notice_search = get_theme_mod('search_notice_searchregion');
						if (!fau_empty($notice_search)) { ?>
						<p class="notice-hinweis"><?php echo $notice_search; ?></p>
						<?php } ?>
						<p class="meta-resultinfo"><?php 
						    if ($wp_query->found_posts>1) {
							echo __("Es wurden",'fau');
						    } else {
							echo __("Es wurde",'fau');
						    }
						    echo " ".$wp_query->found_posts.' '.__("Treffer gefunden",'fau').":"; ?>
						</p>
						<?php 
	
						
						 $listtypes = get_theme_mod('search_post_types');
						echo '<ul class="searchresults">';
						while ( have_posts() ) { 
						    the_post(); 
						    echo fau_display_search_resultitem();
						} 
						echo "</ul>";

					    if ( $wp_query->max_num_pages > 1 ) {
						if (absint( get_query_var( 'paged' ))>0) {
						    $paged = absint( get_query_var( 'paged' ));
						} else {
						    $paged =1;
						}
						$pagenum_link = html_entity_decode( get_pagenum_link() );
						$query_args   = array();
						$url_parts    = explode( '?', $pagenum_link );

						if ( isset( $url_parts[1] ) ) {
						    wp_parse_str( $url_parts[1], $query_args );
						}

						$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
						$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

						$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
						$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

						$links = paginate_links( array(
						    'base'     => $pagenum_link,
						    'format'   => $format,
						    'total'    => $wp_query->max_num_pages,
						    'current'  => $paged,
						    'mid_size' => 1,
						    'prev_text' => __( '<span class="meta-nav">&larr;</span> Zurück', 'fau' ),
						    'next_text' => __( 'Weiter <span class="meta-nav">&rarr;</span>', 'fau' ),
						    'before_page_number' => '<span class="screen-reader-text">'.__( 'Seite', 'fau' ).' </span>'
						) );
						?>
						<?php if ( $links ) { ?>
						    <nav id="nav-pages" class="navigation paging-navigation" role="navigation" aria-label="<?php _e( 'Weitere Suchergebnisse', 'fau' ); ?>">
							<div class="nav-links">
							    <?php echo $links; ?>
							</div>
						    </nav>
						<?php } 
					    } 
					
				    } else {
					// keine Treffer
					$notfound = true;
					?>
					<div class="error-notice">
					    <p class="hinweis">
						    <strong><?php _e('Nichts gefunden.','fau'); ?></strong>
					    </p>
					    <p>
						    <?php _e('Leider konnte für Ihren Suchbegriff kein passendes Ergebnis gefunden werden.','fau'); ?>
					    </p>						
					</div>	   
					<?php 
					get_template_part('template-parts/search', 'form');  
				    }
				  } else {
				    // bisher kein SUchbegriff eingegeben
				      ?>
					<div class="error-notice">
					    <p class="hinweis">
						    <strong><?php _e('Kein Suchbegriff.','fau'); ?></strong>
					    </p>
					    <p>
						    <?php _e('Bitte geben Sie einen Suchbegriff in das Suchfeld ein.','fau'); ?>
					    </p>						
					</div>	   
					<?php 
					get_template_part('template-parts/search', 'form');  
				  }
				?>
				
				
					    
			      <?php 
				
				get_template_part('template-parts/search', 'helper'); 
				?>   
		      </main> 
	    </div>
	    <?php if ( (is_plugin_active( 'FAU-Fehlermeldungen/fau-fehlermeldungen.php' ) && ($notfound)) || (is_active_sidebar( 'search-sidebar' )) ) { ?>
	      <aside class="portalpage-sidebar" aria-label="<?php echo __('Sidebar','fau');?>">	 
	      <?php 
		if ( is_plugin_active( 'FAU-Fehlermeldungen/fau-fehlermeldungen.php' ) && ($notfound)) { 
		    echo do_shortcode('[fau_fehlermeldungen type="404" fulltext="false" ]'); 
		}
		if ( is_active_sidebar( 'search-sidebar' ) ) { 	
		    dynamic_sidebar( 'search-sidebar' );
		} ?>
	     </aside>
	    <?php } ?>
	</div>
    </div>
 </div>
<?php
get_template_part('template-parts/footer', 'social'); 
get_footer();

