<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
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
			    <?php fau_breadcrumb($options['title_hero_search']); ?>
			</div>
		</div>
		<div class="row">
			<div class="search-title">
			    <h1><?php echo $options['title_hero_search']; ?></h1>
			</div>
			<div class="search-input">
			    <form role="search" method="get" class="searchform" action="<?php echo home_url( '/' )?>">
				<div class="search-text">
				    <label for="suchmaske-hero"><?php _e('Geben Sie hier den Suchbegriff ein','fau'); ?></label>
				    <span class="searchicon"> </span>
				    <input id="suchmaske-hero" type="text" value="<?php the_search_query(); ?>" name="s" placeholder="<?php _e('Suchen nach...','fau'); ?>">
				    <input type="submit" value="<?php _e('Finden','fau'); ?>">
				</div>
				<?php
				if ($options['search_allowfilter']) {
				    echo '<div class="filter">';
				    $query_types = get_query_var('post_type');
				    $listtypes = $options['search_post_types'];
				     $allowed_types = get_post_types(array('public' => true, 'exclude_from_search' => false));

				    foreach ($listtypes as $type) {                                                
					if( in_array( $type, $allowed_types ) ) {
					    $typeinfo = get_post_type_object( $type );
					    $typestr = $typeinfo->labels->name; 	    
					    echo '<div class="nowrap"><input type="checkbox" name="post_type[]" id="label-'.$type.'" value="'.$type.'"';
					    if (in_array($type, $query_types)) { echo ' checked="checked"'; }
					    echo ">";			
					    echo '<label for="label-'.$type.'">'.$typestr.'</label></div>';
					}
				    }
				    echo "</div>\n";
				}
				?>

			    </form>
				
			</div>
		</div>
	</div>
</section>
